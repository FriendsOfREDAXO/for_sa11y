/* FOR Sa11y – Link Checker (non-blocking)
 *
 * Integrates with Sa11y 5's "listen" customChecks API.
 *
 * Flow:
 *  1. Sa11y fires "sa11y-custom-checks" → we dispatch "sa11y-resume" immediately
 *     (so Sa11y renders standard results without delay) and start async link checks.
 *  2. When all link checks are complete and broken links were found:
 *     → call sa11yInstance.resetAll(false) + checkAll() for a second pass.
 *  3. On the second "sa11y-custom-checks" event we push our broken-link result
 *     objects into sa11yInstance.results (the live n.results reference) and
 *     dispatch "sa11y-resume" → Sa11y renders with broken-link annotations.
 *
 * - Deduplicates links by resolved URL
 * - Caches results in sessionStorage (5 min TTL)
 * - Same-origin: full HTTP status (GET fallback if HEAD blocked)
 * - Cross-origin (opt-in): detects unreachable servers (DNS/network errors)
 *   and opaque responses (server responded, status unreadable due to CORS)
 */
(function () {
    'use strict';

    var configEl = document.getElementById('sa11y-lc-config');
    if (!configEl) {
        return;
    }

    /* ------------------------------------------------------------------ */
    /* Config                                                               */
    /* ------------------------------------------------------------------ */
    var IGNORE      = configEl.getAttribute('data-ignore') || '';
    var CHECK_EXT   = configEl.getAttribute('data-external') === '1';
    var CONCURRENCY = 3;
    var TIMEOUT_MS  = 8000;
    var CACHE_TTL   = 5 * 60 * 1000; // 5 minutes
    var CACHE_PFX   = 'sa11y_lc_';

    /* ------------------------------------------------------------------ */
    /* State                                                                */
    /* ------------------------------------------------------------------ */
    var isSecondPass  = false;   // true when triggered by our own resetAll+checkAll
    var checkRunning  = false;   // prevent concurrent background runs

    /* ------------------------------------------------------------------ */
    /* SessionStorage cache                                                 */
    /* ------------------------------------------------------------------ */
    function cacheGet(href) {
        try {
            var raw = sessionStorage.getItem(CACHE_PFX + href);
            if (!raw) {
                return null;
            }
            var entry = JSON.parse(raw);
            if (Date.now() - entry.ts > CACHE_TTL) {
                sessionStorage.removeItem(CACHE_PFX + href);
                return null;
            }
            return entry;
        } catch (e) {
            return null;
        }
    }

    function cacheSet(href, status) {
        try {
            sessionStorage.setItem(CACHE_PFX + href, JSON.stringify({ status: status, ts: Date.now() }));
        } catch (e) {
            /* Storage quota exceeded – ignore */
        }
    }

    /* ------------------------------------------------------------------ */
    /* Helpers                                                              */
    /* ------------------------------------------------------------------ */
    function shouldSkip(href) {
        if (!href) {
            return true;
        }
        var lower = href.toLowerCase().replace(/^[\s]+/, '');
        return lower.startsWith('#') ||
               lower.startsWith('mailto:') ||
               lower.startsWith('tel:') ||
               lower.startsWith('javascript:') ||
               lower.startsWith('data:') ||
               lower.startsWith('blob:');
    }

    function isExternal(anchor) {
        return anchor.origin !== location.origin;
    }

    function isBroken(status) {
        return status >= 400 || status === -1 || status === -2;
    }

    /* ------------------------------------------------------------------ */
    /* Collect checkable anchors from the page                             */
    /* ------------------------------------------------------------------ */
    function collectAnchors() {
        var allAnchors = Array.from(document.querySelectorAll('a[href]'));

        /* Apply ignore selectors */
        var ignored = new Set();
        if (IGNORE) {
            IGNORE.split(',').forEach(function (sel) {
                sel = sel.trim();
                if (!sel) {
                    return;
                }
                try {
                    document.querySelectorAll(sel).forEach(function (el) {
                        ignored.add(el);
                    });
                } catch (e) {
                    /* Invalid selector – skip silently */
                }
            });
        }

        /* Filter ignored, external (if disabled), and unskippable hrefs */
        allAnchors = allAnchors.filter(function (a) {
            if (ignored.has(a)) {
                return false;
            }
            if (shouldSkip(a.getAttribute('href'))) {
                return false;
            }
            if (!CHECK_EXT && isExternal(a)) {
                return false;
            }
            return true;
        });

        /* Deduplicate by resolved href */
        var seen = new Set();
        return allAnchors.filter(function (a) {
            var href = a.href;
            if (seen.has(href)) {
                return false;
            }
            seen.add(href);
            return true;
        });
    }

    /* ------------------------------------------------------------------ */
    /* Single link check                                                    */
    /* ------------------------------------------------------------------ */
    function checkLink(anchor) {
        var href = anchor.href;
        var ext  = isExternal(anchor);

        /* Cache hit */
        var cached = cacheGet(href);
        if (cached !== null) {
            if (isBroken(cached.status)) {
                anchor.setAttribute('data-sa11y-broken', String(cached.status));
            } else {
                anchor.removeAttribute('data-sa11y-broken');
            }
            return Promise.resolve();
        }

        var controller = new AbortController();
        var timerId    = setTimeout(function () { controller.abort(); }, TIMEOUT_MS);

        var opts = {
            method: 'HEAD',
            signal: controller.signal,
            cache: 'no-store',
            redirect: 'follow',
        };

        /* External: no-cors → opaque response (server up) or TypeError (server down) */
        if (ext) {
            opts.mode = 'no-cors';
        }

        return fetch(href, opts)
            .then(function (res) {
                clearTimeout(timerId);

                /* Opaque = external server responded – cannot read status, assume OK */
                if (res.type === 'opaque') {
                    cacheSet(href, 0);
                    anchor.removeAttribute('data-sa11y-broken');
                    return;
                }

                /* HEAD blocked (405) → retry with GET if same-origin */
                if (res.status === 405 && !ext) {
                    var getController = null;
                    var getTimerId = null;
                    var getSignal;

                    if (AbortSignal.timeout) {
                        getSignal = AbortSignal.timeout(TIMEOUT_MS);
                    } else {
                        getController = new AbortController();
                        getSignal = getController.signal;
                        getTimerId = setTimeout(function () {
                            getController.abort();
                        }, TIMEOUT_MS);
                    }

                    return fetch(href, {
                        method: 'GET',
                        signal: getSignal,
                        cache: 'no-store',
                        redirect: 'follow',
                    }).then(function (r) {
                        if (getTimerId) {
                            clearTimeout(getTimerId);
                        }
                        cacheSet(href, r.status);
                        if (isBroken(r.status)) {
                            anchor.setAttribute('data-sa11y-broken', String(r.status));
                        } else {
                            anchor.removeAttribute('data-sa11y-broken');
                        }
                    }).catch(function (err) {
                        if (getTimerId) {
                            clearTimeout(getTimerId);
                        }
                        throw err;
                    });
                }

                cacheSet(href, res.status);
                if (isBroken(res.status)) {
                    anchor.setAttribute('data-sa11y-broken', String(res.status));
                } else {
                    anchor.removeAttribute('data-sa11y-broken');
                }
            })
            .catch(function (err) {
                clearTimeout(timerId);

                var errStatus = (err && err.name === 'AbortError') ? -1 : -2;
                cacheSet(href, errStatus);
                anchor.setAttribute('data-sa11y-broken', String(errStatus));
            });
    }

    /* ------------------------------------------------------------------ */
    /* Concurrency queue                                                    */
    /* ------------------------------------------------------------------ */
    function runQueue(anchors, concurrency) {
        var i = 0;

        function next() {
            if (i >= anchors.length) {
                return Promise.resolve();
            }
            var anchor = anchors[i++];
            return checkLink(anchor).then(next);
        }

        var workers = [];
        var limit   = Math.min(concurrency, anchors.length);
        for (var w = 0; w < limit; w++) {
            workers.push(next());
        }
        return Promise.all(workers);
    }

    /* ------------------------------------------------------------------ */
    /* Build human-readable message for a given broken status code         */
    /* ------------------------------------------------------------------ */
    /* Build human-readable message for a given broken status code         */
    /* ------------------------------------------------------------------ */
    function getSa11yLanguage() {
        var instLang = window.sa11yInstance && typeof window.sa11yInstance.lang === 'string'
            ? window.sa11yInstance.lang
            : '';
        var docLang = document.documentElement && typeof document.documentElement.lang === 'string'
            ? document.documentElement.lang
            : '';
        var lang = (instLang || docLang || 'en').toLowerCase();
        if (lang.indexOf('de') === 0) {
            return 'de';
        }
        return 'en';
    }

    function getBrokenLinkMessages() {
        var language = getSa11yLanguage();
        var messages = {
            en: {
                timeout: 'Link check: Timeout \u2013 the link did not respond within 8 seconds.',
                unreachable: 'Link check: Server unreachable (DNS error or connection refused).',
                notFound: 'Link check: Link not found (HTTP ',
                serverError: 'Link check: Server error (HTTP ',
                unknown: 'Link check: This link appears to be unreachable.',
            },
            de: {
                timeout: 'Link-Check: Timeout \u2013 der Link hat nicht innerhalb von 8 Sekunden geantwortet.',
                unreachable: 'Link-Check: Server nicht erreichbar (DNS-Fehler oder Verbindung abgewiesen).',
                notFound: 'Link-Check: Link nicht gefunden (HTTP ',
                serverError: 'Link-Check: Serverfehler (HTTP ',
                unknown: 'Link-Check: Dieser Link scheint nicht erreichbar zu sein.',
            },
        };
        return messages[language] || messages.en;
    }

    function brokenLinkMessage(status) {
        /* Use Sa11y's native badge+link-icon CSS classes (available in tooltip shadow DOM) */
        var badge = '<strong class="badge error-badge">'
            + '<span class="link-icon"></span>'
            + '<span class="visually-hidden">Link</span>'
            + '</strong> ';
        var m = getBrokenLinkMessages();

        if (status === -1) {
            return badge + m.timeout;
        }
        if (status === -2) {
            return badge + m.unreachable;
        }
        if (status >= 400 && status < 500) {
            return badge + m.notFound + status + ')';
        }
        if (status >= 500) {
            return badge + m.serverError + status + ')';
        }
        return badge + m.unknown;
    }
    /* ------------------------------------------------------------------ */
    /* Push broken link results into Sa11y's live results array            */
    /* ------------------------------------------------------------------ */
    function pushBrokenLinkResults() {
        var inst = window.sa11yInstance;
        if (!inst || typeof inst.results !== 'object') {
            return;
        }
        var brokenAnchors = Array.from(document.querySelectorAll('a[data-sa11y-broken]'));
        brokenAnchors.forEach(function (a) {
            var status = parseInt(a.getAttribute('data-sa11y-broken') || '0', 10);
            inst.results.push({
                element: a,
                type: 'error',
                /* content uses Sa11y's sanitizer (vn); badge/link-icon classes live in tooltip shadow DOM */
                content: brokenLinkMessage(status),
                developer: false,
            });
        });
    }

    /* ------------------------------------------------------------------ */
    /* Sa11y "listen" mode event handler                                   */
    /* ------------------------------------------------------------------ */
    document.addEventListener('sa11y-custom-checks', function () {
        if (isSecondPass) {
            /* ── Pass 2: background check is done, push results and finish ── */
            isSecondPass = false;
            pushBrokenLinkResults();
            document.dispatchEvent(new Event('sa11y-resume'));
            return;
        }

        /* ── Pass 1: unblock Sa11y immediately, start background checks ── */
        document.dispatchEvent(new Event('sa11y-resume'));

        if (checkRunning) {
            /* Concurrent call (e.g. rapid SPA navigation) – skip */
            return;
        }
        checkRunning = true;

        /* Clear stale broken-link marks from a previous run */
        document.querySelectorAll('a[data-sa11y-broken]').forEach(function (a) {
            a.removeAttribute('data-sa11y-broken');
        });

        var anchors = collectAnchors();

        runQueue(anchors, CONCURRENCY)
            .then(function () {
                checkRunning = false;

                var hasBroken = document.querySelector('a[data-sa11y-broken]') !== null;
                if (!hasBroken) {
                    /* No broken links – nothing to add to the report */
                    return;
                }

                /* Trigger second pass: resetAll keeps panel open, checkAll re-runs */
                var inst = window.sa11yInstance;
                if (inst && typeof inst.resetAll === 'function' && typeof inst.checkAll === 'function') {
                    isSecondPass = true;
                    inst.resetAll(false).then(function () {
                        inst.checkAll();
                    });
                }
            })
            .catch(function () {
                checkRunning  = false;
                isSecondPass  = false;
            });
    });
}());
