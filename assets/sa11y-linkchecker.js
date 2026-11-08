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
 * - Cross-origin (opt-in): checked on the server (rex-api-call=for_sa11y_link_check) with the real
 *   HTTP status. The browser cannot read foreign status codes (CORS), and mixed content, tracking
 *   protection or content blockers made reachable links look broken.
 * - Only clear cases are reported: 404, 410, 5xx (except 503) and unknown domains. 401/403/429/503
 *   and timeouts are usually bot protection, not broken links.
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
    var ROOT        = configEl.getAttribute('data-root') || 'body';
    var IGNORE      = configEl.getAttribute('data-ignore') || '';
    var CHECK_EXT   = configEl.getAttribute('data-external') === '1';
    var ENDPOINT    = configEl.getAttribute('data-endpoint') || '';
    var CONCURRENCY = 6;
    var TIMEOUT_MS  = 8000;
    var CACHE_TTL   = 5 * 60 * 1000; // 5 minutes
    var CACHE_PFX   = 'sa11y_lc2_';

    /* ------------------------------------------------------------------ */
    /* State                                                                */
    /* ------------------------------------------------------------------ */
    var isSecondPass  = false;   // true when triggered by our own resetAll+checkAll
    var checkRunning  = false;   // prevent concurrent background runs
    var sameTarget    = {};      // href without #fragment => all anchors with this target

    /* Mark or unmark every anchor that points to the same target */
    function markBroken(anchor, status) {
        var group = sameTarget[anchor.href.split('#')[0]] || [anchor];
        group.forEach(function (a) {
            if (status === null) {
                a.removeAttribute('data-sa11y-broken');
            } else {
                a.setAttribute('data-sa11y-broken', String(status));
            }
        });
    }

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
        return status === 404 || status === 410 || (status >= 500 && status !== 503) || status === -2;
    }

    /* ------------------------------------------------------------------ */
    /* Collect checkable anchors from the page                             */
    /* ------------------------------------------------------------------ */
    function collectAnchors() {
        /* Nur Links in den eingestellten Bereichen (kommagetrennte Selektoren); ungültig/leer = ganze Seite */
        var roots = [];
        try {
            roots = Array.from(document.querySelectorAll(ROOT));
        } catch (e) {
            roots = [];
        }
        if (!roots.length) {
            roots = [document.body];
        }
        var allAnchors = [];
        roots.forEach(function (root) {
            root.querySelectorAll('a[href]').forEach(function (a) {
                if (allAnchors.indexOf(a) === -1) {
                    allAnchors.push(a);
                }
            });
        });

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
            if ((!CHECK_EXT || !ENDPOINT) && isExternal(a)) {
                return false;
            }
            return true;
        });

        /* Deduplicate by resolved href without #fragment: one request per page, result for all anchors */
        var groups = {};
        var unique = [];
        allAnchors.forEach(function (a) {
            var key = a.href.split('#')[0];
            if (!groups[key]) {
                groups[key] = [];
                unique.push(a);
            }
            groups[key].push(a);
        });
        sameTarget = groups;
        return unique;
    }

    /* ------------------------------------------------------------------ */
    /* Single link check                                                    */
    /* ------------------------------------------------------------------ */
    function checkLink(anchor) {
        var href = anchor.href.split('#')[0];
        var ext  = isExternal(anchor);

        /* Cache hit */
        var cached = cacheGet(href);
        if (cached !== null) {
            if (isBroken(cached.status)) {
                markBroken(anchor, cached.status);
            } else {
                markBroken(anchor, null);
            }
            return Promise.resolve();
        }

        if (ext) {
            return checkExternal(anchor, href);
        }

        var controller = new AbortController();
        var timerId    = setTimeout(function () { controller.abort(); }, TIMEOUT_MS);

        var opts = {
            method: 'HEAD',
            signal: controller.signal,
            cache: 'no-store',
            redirect: 'follow',
        };

        return fetch(href, opts)
            .then(function (res) {
                clearTimeout(timerId);

                /* Opaque = external server responded – cannot read status, assume OK */
                if (res.type === 'opaque') {
                    cacheSet(href, 0);
                    markBroken(anchor, null);
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
                            markBroken(anchor, r.status);
                        } else {
                            markBroken(anchor, null);
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
                    markBroken(anchor, res.status);
                } else {
                    markBroken(anchor, null);
                }
            })
            .catch(function () {
                /* Netzwerkfehler auf der eigenen Seite (z. B. Zeitüberschreitung) nicht als defekt melden */
                clearTimeout(timerId);
                cacheSet(href, -1);
                markBroken(anchor, null);
            });
    }

    /* All links in one request: the server checks them in parallel */
    function checkBatch(anchors) {
        var todo = [];
        anchors.forEach(function (a) {
            var href = a.href.split('#')[0];
            var cached = cacheGet(href);
            if (cached !== null) {
                markBroken(a, isBroken(cached.status) ? cached.status : null);
            } else {
                todo.push(a);
            }
        });
        if (!todo.length) {
            return Promise.resolve();
        }
        var urls = todo.map(function (a) { return a.href.split('#')[0]; });
        return fetch(ENDPOINT, {
            method: 'POST',
            credentials: 'same-origin',
            cache: 'no-store',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ urls: urls }),
        })
            .then(function (res) { return res.ok ? res.json() : { results: {} }; })
            .then(function (data) {
                var results = (data && data.results) || {};
                todo.forEach(function (a) {
                    var href = a.href.split('#')[0];
                    if (!Object.prototype.hasOwnProperty.call(results, href)) {
                        return;
                    }
                    var status = parseInt(results[href], 10) || 0;
                    cacheSet(href, status);
                    markBroken(a, isBroken(status) ? status : null);
                });
            })
            .catch(function () { /* Netzwerkfehler: nichts melden */ });
    }

    /* External link: ask the server for the real status */
    function checkExternal(anchor, href) {
        var sep = ENDPOINT.indexOf('?') === -1 ? '?' : '&';
        return fetch(ENDPOINT + sep + 'url=' + encodeURIComponent(href), {
            credentials: 'same-origin',
            cache: 'no-store',
            headers: { 'Accept': 'application/json' },
        })
            .then(function (res) { return res.ok ? res.json() : { status: 0 }; })
            .then(function (data) {
                var status = parseInt(data && data.status, 10) || 0;
                cacheSet(href, status);
                if (isBroken(status)) {
                    markBroken(anchor, status);
                } else {
                    markBroken(anchor, null);
                }
            })
            .catch(function () {
                markBroken(anchor, null);
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
                unreachable: 'Link check: Domain not found (DNS) \u2013 the address does not exist.',
                notFound: 'Link check: Link not found (HTTP ',
                serverError: 'Link check: Server error (HTTP ',
                unknown: 'Link check: This link appears to be unreachable.',
            },
            de: {
                timeout: 'Link-Check: Timeout \u2013 der Link hat nicht innerhalb von 8 Sekunden geantwortet.',
                unreachable: 'Link-Check: Domain nicht gefunden (DNS) \u2013 diese Adresse gibt es nicht.',
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

        /* Mit Server-Endpunkt: alle Links in einer Anfrage (parallel auf dem Server, wenige Sekunden).
           Ohne Endpunkt: wie bisher einzeln im Browser (nur eigene Seiten). */
        (ENDPOINT ? checkBatch(anchors) : runQueue(anchors, CONCURRENCY))
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
