<?php

namespace FriendsOfRedaxo\Sa11y;

use rex;
use rex_addon;
use rex_backend_login;
use rex_clang;
use rex_response;

use function array_key_exists;

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
  const sa11y = new Sa11y.Sa11y({
    checkRoot: \'' . $root . '\',
    readabilityLang: \'' . $lang['text'] . '\',
    containerIgnore: \'' . $ignore . '\',
    ' . $jsOptions . '
    ' . $custom . '
  });
</script>
';
        return $js;
    }

    /**
     * Baut die JS-Optionen aus den Addon-Konfigurationswerten zusammen.
     */
    private static function buildJsOptions(rex_addon $addon): string
    {
        $options = [];

        // Panel-Position
        $panelPosition = (string) $addon->getConfig('panel_position', 'bottom-right');
        if ($panelPosition !== 'bottom-right') {
            $options[] = 'panelPosition: "' . rex_escape($panelPosition) . '"';
        }

        // Verzögerung
        $delayCheck = (int) $addon->getConfig('delay_check', 0);
        if ($delayCheck > 0) {
            $options[] = 'delayCheck: ' . $delayCheck;
        }

        // Plugins
        $contrastPlugin = (int) $addon->getConfig('contrast_plugin', 0);
        if ($contrastPlugin === 1) {
            $options[] = 'contrastPlugin: true';
        }

        $formLabelsPlugin = (int) $addon->getConfig('form_labels_plugin', 0);
        if ($formLabelsPlugin === 1) {
            $options[] = 'formLabelsPlugin: true';
        }

        $readabilityPlugin = (int) $addon->getConfig('readability_plugin', 0);
        if ($readabilityPlugin === 1) {
            $options[] = 'readabilityPlugin: true';
            $readabilityRoot = (string) $addon->getConfig('readability_root', 'body');
            if ($readabilityRoot !== '' && $readabilityRoot !== 'body') {
                $options[] = 'readabilityRoot: "' . rex_escape($readabilityRoot) . '"';
            }
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

        // Links to flag
        $linksToFlag = (string) $addon->getConfig('links_to_flag', '');
        if ($linksToFlag !== '') {
            $options[] = 'linksToFlag: "' . rex_escape($linksToFlag) . '"';
        }

        // About Content
        $aboutContent = (string) $addon->getConfig('about_content', '');
        if ($aboutContent !== '') {
            $options[] = "aboutContent: '" . str_replace("'", "\\'", $aboutContent) . "'";
        }

        if ($options === []) {
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
            'de_de' => ['js' => 'de', 'setup' => 'De'],
            'en_gb' => ['js' => 'en', 'setup' => 'En'],
            'es_es' => ['js' => 'es', 'setup' => 'Es'],
            'pt_br' => ['js' => 'pt', 'setup' => 'Pt'],
            'it_it' => ['js' => 'it', 'setup' => 'It'],
            'sv_se' => ['js' => 'sv', 'setup' => 'Sv'],
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
