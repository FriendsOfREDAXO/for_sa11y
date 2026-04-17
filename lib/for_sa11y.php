<?php

namespace FriendsOfRedaxo\Sa11y;

use rex;
use rex_addon;
use rex_addon_interface;
use rex_backend_login;
use rex_clang;
use rex_response;

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
        $custom = (string) $addon->getConfig('custom_settings');

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
        if ((int) $addon->getConfig('link_checker', 0) === 1) {
            $lcIgnore   = rex_escape((string) $addon->getConfig('link_checker_ignore', ''));
            $lcExternal = (int) $addon->getConfig('link_checker_check_external', 0) === 1 ? '1' : '0';
            // "listen" mode: Sa11y wartet auf sa11y-resume Event vom Link-Checker.
            // delayCustomCheck: 20s Timeout damit Sa11y nicht abbricht während Netzwerk-Checks laufen.
            $linkCheckerOptions = '
    customChecks: "listen",
    delayCustomCheck: 20000,';
        }

        $js = '
      <link rel="stylesheet" href="' . $addon->getAssetsUrl('vendor/sa11y/dist/css/sa11y.min.css') . '"/>
      <script src="' . $addon->getAssetsUrl('vendor/sa11y/dist/js/sa11y.umd.min.js') . '"></script>
      <script src="' . $addon->getAssetsUrl('vendor/sa11y/dist/js/lang/' . $lang['js'] . '.umd.js') . '"></script>

  <script nonce="' . rex_response::getNonce() . '">
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
  window.sa11yInstance = new Sa11y.Sa11y({
    checkRoot: \'' . $root . '\',
    readabilityLang: \'' . $lang['text'] . '\',
    containerIgnore: \'' . $ignore . '\',
    ' . $jsOptions . $linkCheckerOptions . '
    ' . $custom . '
  });
</script>
';

        // Link-Checker Script
        if ((int) $addon->getConfig('link_checker', 0) === 1) {
            $js .= '<div id="sa11y-lc-config" hidden data-ignore="' . $lcIgnore . '" data-external="' . $lcExternal . '"></div>';
            $js .= '<script src="' . $addon->getAssetsUrl('sa11y-linkchecker.js') . '"></script>';
        }

        return $js;
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
