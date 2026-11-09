<?php

namespace FriendsOfRedaxo\Sa11y;

use rex;
use rex_addon;
use rex_addon_interface;
use rex_backend_login;
use rex_clang;
use rex_response;
use rex_url;

use function array_key_exists;
use function rex_get;

class Sa11y
{
    /**
     * @api
     */
    public static function get(): string
    {
        $user = rex_backend_login::createUser();
        if (!$user || !rex_backend_login::hasSession()) {
            return '';
        }

        $addon = rex_addon::get('for_sa11y');

        if ((!$user->isAdmin() && !$user->hasPerm('for_sa11y[sa11yCheck]')) || 'false' === $addon->getConfig('active')) {
            return '';
        }

        // URL-Parameter-Ausschluss: Sa11y nicht ausführen wenn bestimmte Parameter gesetzt sind
        $excludeParams = (string) $addon->getConfig('exclude_url_params', '');
        if ($excludeParams !== '') {
            foreach (array_map('trim', explode(',', $excludeParams)) as $param) {
                if ($param === '') {
                    continue;
                }
                if (str_contains($param, '=')) {
                    [$key, $val] = explode('=', $param, 2);
                    if (rex_get(trim($key), 'string', '') === trim($val)) {
                        return '';
                    }
                } else {
                    if ('' !== rex_get($param, 'string', '')) {
                        return '';
                    }
                }
            }
        }

        $root = (string) rex_escape($addon->getConfig('root', 'body'));
        $ignore = (string) rex_escape($addon->getConfig('ignore'));
        // Eigene Einstellungen: Sa11y-4-Werte (panelPosition bottom-right/bottom-left) auf Sa11y 5 umschreiben,
        // sonst überschreiben sie die Position aus dem Formular und Sa11y fällt still auf „right“ zurück.
        $custom = self::migrateCustomSettings((string) $addon->getConfig('custom_settings'));
        $nonce = ' nonce="' . rex_response::getNonce() . '"';

        // Konfigurierbare Optionen sammeln
        $jsOptions = self::buildJsOptions($addon);

        // Config-Hash generieren für LocalStorage-Invalidierung
        $configHash = md5(serialize([
            'root' => $root,
            'ignore' => $ignore,
            'custom' => $custom,
            'options' => $jsOptions,
            'version' => $addon->getVersion(),
        ]));

        $lang = self::detectLanguage($user->getLanguage());

        // Link-Checker: Sa11y "listen" mode aktivieren wenn Link-Checker aktiv
        $linkCheckerOptions = '';
        if (in_array((int) $addon->getConfig('link_checker', 0), [1, 2], true)) {
            $lcIgnore   = rex_escape((string) $addon->getConfig('link_checker_ignore', ''));
            // Bereich für den Link-Checker; leer = Prüfbereich von Sa11y („Prüfen ab Selektor“)
            $lcRoot     = trim((string) $addon->getConfig('link_checker_root', ''));
            $lcRoot     = rex_escape('' !== $lcRoot ? $lcRoot : (string) $addon->getConfig('root', 'body'));
            $lcExternal = (int) $addon->getConfig('link_checker_check_external', 0) === 1 ? '1' : '0';
            // "listen" mode: Sa11y wartet auf sa11y-resume Event vom Link-Checker.
            // delayCustomCheck: 20s Timeout damit Sa11y nicht abbricht während Netzwerk-Checks laufen.
            $linkCheckerOptions = '
    customChecks: "listen",
    delayCustomCheck: 20000,';
        }

        $js = '
      <link rel="stylesheet" href="' . $addon->getAssetsUrl('vendor/sa11y/dist/css/sa11y.min.css') . '"/>
      <script' . $nonce . ' src="' . $addon->getAssetsUrl('vendor/sa11y/dist/js/sa11y.umd.min.js') . '"></script>
      <script' . $nonce . ' src="' . $addon->getAssetsUrl('vendor/sa11y/dist/js/lang/' . $lang['js'] . '.umd.js') . '"></script>

  <script' . $nonce . '>
  (function() {
    const currentConfigHash = "' . $configHash . '";
    const storedConfigHash = localStorage.getItem("sa11y_config_hash");

    if (storedConfigHash && storedConfigHash !== currentConfigHash) {
      const keysToRemove = [];
      for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        if (key && key.startsWith("sa11y")) {
          keysToRemove.push(key);
        }
      }
      keysToRemove.forEach(key => localStorage.removeItem(key));
    }

    localStorage.setItem("sa11y_config_hash", currentConfigHash);
  })();

  Sa11y.Lang.addI18n(Sa11yLang' . $lang['setup'] . '.strings);
  const sa11yOptions = {
    checkRoot: \'' . $root . '\',
    readabilityLang: \'' . $lang['text'] . '\',
    containerIgnore: \'' . $ignore . '\',
    ' . $jsOptions . $linkCheckerOptions . '
    ' . $custom . '
  };
  // Prüfungen aus dem Formular; ein checks-Block in den eigenen Einstellungen hat Vorrang und wird ergänzt, nicht ersetzt
  sa11yOptions.checks = Object.assign(' . self::buildChecks($addon) . ', sa11yOptions.checks || {});
  window.sa11yInstance = new Sa11y.Sa11y(sa11yOptions);
</script>
';

        // Link-Checker Script
        if (in_array((int) $addon->getConfig('link_checker', 0), [1, 2], true)) {
            // Externe Links prüft der Server (echter HTTP-Status statt CORS-Raten im Browser)
            $lcEndpoint = rex_url::frontendController(['rex-api-call' => 'for_sa11y_link_check'], false);
            $lcEndpoint = str_starts_with($lcEndpoint, './') ? substr($lcEndpoint, 1) : $lcEndpoint;
            $js .= '<div id="sa11y-lc-config" hidden data-root="' . $lcRoot . '" data-ignore="' . $lcIgnore . '" data-external="' . $lcExternal . '" data-endpoint="' . rex_escape($lcEndpoint) . '" data-mode="' . (2 === (int) $addon->getConfig('link_checker', 0) ? 'manual' : 'auto') . '"></div>';
            // Nonce auch hier: wird der Link-Checker von einer CSP blockiert, wartet Sa11y im Modus „listen“
            // bis zu 20 Sekunden, bevor es Markierungen setzt.
            // Zeitstempel gegen veraltete Dateien im Browser-Cache (Safari hält Skripte ohne Version lange)
            $lcVersion = (string) @filemtime($addon->getAssetsPath('sa11y-linkchecker.js'));
            $js .= '<script' . $nonce . ' src="' . $addon->getAssetsUrl('sa11y-linkchecker.js') . '?v=' . $lcVersion . '"></script>';
        }

        return $js;
    }

    /**
     * Schreibt Sa11y-4-Werte in den eigenen Einstellungen auf Sa11y 5 um.
     *
     * @api
     */
    public static function migrateCustomSettings(string $custom): string
    {
        return (string) preg_replace_callback(
            '~(panelPosition\s*:\s*["\'])bottom-(right|left)(["\'])~',
            static fn (array $m): string => $m[1] . $m[2] . $m[3],
            $custom,
        );
    }

    /**
     * Abgeschaltete Prüfungen und „verbotene Link-Ziele“ als JS-Objekt für die checks-Option.
     */
    private static function buildChecks(rex_addon_interface $addon): string
    {
        $checks = [];
        foreach (Checks::parse($addon->getConfig('disabled_checks', '')) as $check) {
            $checks[$check] = false;
        }

        // QA_BAD_LINK: Links auf diese Ziele (CSS-Selektoren) melden, z. B. Testserver
        $badLinks = trim((string) $addon->getConfig('bad_link_sources', ''));
        if ($badLinks !== '' && !isset($checks['QA_BAD_LINK'])) {
            $checks['QA_BAD_LINK'] = ['sources' => $badLinks];
        }

        return [] === $checks ? '{}' : (string) json_encode($checks, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Fügt Sa11y vor </body> ein (Option „Automatisch einbinden“), wenn es nicht schon im Template steht.
     *
     * @api
     */
    public static function injectIntoOutput(string $html): string
    {
        if (str_contains($html, 'sa11y.umd.min.js')) {
            return $html;
        }
        $pos = strripos($html, '</body>');
        if (false === $pos) {
            return $html;
        }
        $snippet = self::get();
        if ('' === $snippet) {
            return $html;
        }

        return substr($html, 0, $pos) . $snippet . substr($html, $pos);
    }

    /**
     * Baut die JS-Optionen aus den Addon-Konfigurationswerten zusammen.
     */
    private static function buildJsOptions(rex_addon_interface $addon): string
    {
        $options = [];

        // Panel-Position (Sa11y 5: right, left, top-right, top-left)
        $panelPosition = (string) $addon->getConfig('panel_position', 'right');
        // Migrate old Sa11y 4 values
        $panelPosition = str_replace(['bottom-right', 'bottom-left'], ['right', 'left'], $panelPosition);
        if ($panelPosition !== 'right') {
            $options[] = 'panelPosition: "' . rex_escape($panelPosition) . '"';
        }

        // Verzögerung
        $delayCheck = (int) $addon->getConfig('delay_check', 0);
        if ($delayCheck > 0) {
            $options[] = 'delayCheck: ' . $delayCheck;
        }

        // Plugins
        // Sa11y 5 Defaults: contrastPlugin=true, formLabelsPlugin=true, readabilityPlugin=true
        // → muss explizit auf false gesetzt werden wenn deaktiviert
        $contrastPlugin = (int) $addon->getConfig('contrast_plugin', 0);
        if ($contrastPlugin === 1) {
            $contrastAlgorithm = strtoupper((string) $addon->getConfig('contrast_algorithm', 'AA'));
            if ($contrastAlgorithm !== 'AA' && in_array($contrastAlgorithm, ['AAA', 'APCA'], true)) {
                $options[] = 'contrastAlgorithm: "' . $contrastAlgorithm . '"';
            }
        } else {
            $options[] = 'contrastPlugin: false';
        }

        $formLabelsPlugin = (int) $addon->getConfig('form_labels_plugin', 0);
        if ($formLabelsPlugin !== 1) {
            $options[] = 'formLabelsPlugin: false';
        }

        $readabilityPlugin = (int) $addon->getConfig('readability_plugin', 0);
        if ($readabilityPlugin === 1) {
            $readabilityRoot = (string) $addon->getConfig('readability_root', 'body');
            if ($readabilityRoot !== '' && $readabilityRoot !== 'body') {
                $options[] = 'readabilityRoot: "' . rex_escape($readabilityRoot) . '"';
            }
            $readabilityIgnore = (string) $addon->getConfig('readability_ignore', '');
            if ($readabilityIgnore !== '') {
                $options[] = 'readabilityIgnore: "' . rex_escape($readabilityIgnore) . '"';
            }
        } else {
            $options[] = 'readabilityPlugin: false';
        }

        $exportResultsPlugin = (int) $addon->getConfig('export_results_plugin', 1);
        $options[] = 'exportResultsPlugin: ' . ($exportResultsPlugin === 1 ? 'true' : 'false');

        // Anzeige-Optionen
        $showGoodImageButton = (int) $addon->getConfig('show_good_image_button', 1);
        if ($showGoodImageButton !== 1) {
            $options[] = 'showGoodImageButton: false';
        }

        $showGoodLinkButton = (int) $addon->getConfig('show_good_link_button', 1);
        if ($showGoodLinkButton !== 1) {
            $options[] = 'showGoodLinkButton: false';
        }

        // SPA Routing
        $detectSpaRouting = (int) $addon->getConfig('detect_spa_routing', 0);
        if ($detectSpaRouting === 1) {
            $options[] = 'detectSPArouting: true';
        }

        // About Content
        $aboutContent = (string) $addon->getConfig('about_content', '');
        if ($aboutContent !== '') {
            $options[] = 'aboutContent: ' . json_encode($aboutContent);
        }

        // Language of Parts Plugin (Sa11y 5.0 - experimentell)
        $langOfPartsPlugin = (int) $addon->getConfig('lang_of_parts_plugin', 0);
        if ($langOfPartsPlugin === 1) {
            $options[] = 'langOfPartsPlugin: true';
            $langOfPartsCache = (int) $addon->getConfig('lang_of_parts_cache', 1);
            if ($langOfPartsCache !== 1) {
                $options[] = 'langOfPartsCache: false';
            }
        }

        // Seiten/Sektionen komplett ausschließen
        $doNotRun = (string) $addon->getConfig('do_not_run', '');
        if ($doNotRun !== '') {
            $options[] = 'doNotRun: "' . rex_escape($doNotRun) . '"';
        }

        // Developer Plugin
        $developerPlugin = (int) $addon->getConfig('developer_plugin', 0);
        if ($developerPlugin !== 1) {
            $options[] = 'developerPlugin: false';
        } else {
            $developerChecksOnByDefault = (int) $addon->getConfig('developer_checks_on_by_default', 0);
            if ($developerChecksOnByDefault === 1) {
                $options[] = 'developerChecksOnByDefault: true';
            }
        }

        // Shadow DOM / Web Components
        $autoDetectShadow = (int) $addon->getConfig('auto_detect_shadow_components', 0);
        if ($autoDetectShadow === 1) {
            $options[] = 'autoDetectShadowComponents: true';
        }

        // Ausschlüsse je Prüfungsbereich (Sa11y-Props; leer = Sa11y-Standard)
        $selectorOptions = [
            'contrast_ignore' => 'contrastIgnore',
            'link_ignore' => 'linkIgnore',
            'image_ignore' => 'imageIgnore',
            'header_ignore' => 'headerIgnore',
            'outline_ignore' => 'outlineIgnore',
            'paragraph_ignore' => 'paragraphIgnore',
            'ignore_hidden_overflow' => 'ignoreHiddenOverflow',
        ];
        foreach ($selectorOptions as $configKey => $prop) {
            $value = trim((string) $addon->getConfig($configKey, ''));
            if ($value !== '') {
                $options[] = $prop . ': ' . json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }

        // Erste Überschriftenebene (z. B. 2, wenn die H1 im Template steht)
        $initialHeadingLevel = (int) $addon->getConfig('initial_heading_level', 0);
        if ($initialHeadingLevel >= 1 && $initialHeadingLevel <= 6) {
            $options[] = 'initialHeadingLevel: [' . $initialHeadingLevel . ']';
        }

        // Panel verschieben / Hinweise ignorieren
        if ((int) $addon->getConfig('show_move_panel_toggle', 1) !== 1) {
            $options[] = 'showMovePanelToggle: false';
        }
        if ((int) $addon->getConfig('dismiss_annotations', 1) !== 1) {
            $options[] = 'dismissAnnotations: false';
        }

        // „Bearbeiten“ in der Bildübersicht → Medienpool
        if ((int) $addon->getConfig('media_edit_link', 1) === 1) {
            // rex_url liefert im Frontend „./…“; auf Seiten wie /kb/artikel/ würde das falsch aufgelöst → ab Wurzel
            $rootPath = static fn (string $url): string => str_starts_with($url, './') ? substr($url, 1) : $url;
            $editUrl = $rootPath(rex_url::frontendController(['rex-api-call' => 'for_sa11y_media_edit'], false)) . '&path=';
            $options[] = 'editImageURLofCMS: ' . json_encode($editUrl, JSON_UNESCAPED_SLASHES);
            $options[] = 'relativePathImageSRC: ' . json_encode($rootPath(rex_url::media()), JSON_UNESCAPED_SLASHES);
        }

        if (empty($options)) {
            return '';
        }
        return implode(",\n    ", $options) . ',';
    }

    /**
     * Ermittelt die Spracheinstellungen basierend auf der Benutzersprache.
     *
     * @return array{js: string, setup: string, text: string}
     */
    private static function detectLanguage(string $userLanguage): array
    {
        $supportedLanguages = [
            'de_de' => ['js' => 'de',   'setup' => 'De'],
            'en_gb' => ['js' => 'en',   'setup' => 'En'],
            'en_us' => ['js' => 'enUS', 'setup' => 'EnUS'],
            'es_es' => ['js' => 'es',   'setup' => 'Es'],
            'fr_fr' => ['js' => 'fr',   'setup' => 'Fr'],
            'it_it' => ['js' => 'it',   'setup' => 'It'],
            'nl_nl' => ['js' => 'nl',   'setup' => 'Nl'],
            'pl_pl' => ['js' => 'pl',   'setup' => 'Pl'],
            'pt_br' => ['js' => 'ptBR', 'setup' => 'PtBR'],
            'pt_pt' => ['js' => 'ptPT', 'setup' => 'PtPT'],
            'sv_se' => ['js' => 'sv',   'setup' => 'Sv'],
            'uk_ua' => ['js' => 'ua',   'setup' => 'Ua'],
        ];

        if (array_key_exists($userLanguage, $supportedLanguages)) {
            $lang = $supportedLanguages[$userLanguage];
        } else {
            $lang = ['js' => 'de', 'setup' => 'De'];
        }

        $lang['text'] = rex_clang::getCurrent()->getCode();

        return $lang;
    }
}
