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

        if ((!$user->isAdmin() && !$user->hasPerm('for_sa11y[sa11yCheck]')) || 'false' === rex_addon::get('for_sa11y')->getConfig('active')) {
            return '';
        }

        $root = (string) rex_escape(rex_addon::get('for_sa11y')->getConfig('root', 'body'));
        $ignore = (string) rex_escape(rex_addon::get('for_sa11y')->getConfig('ignore'));
        $custom = (string) rex_addon::get('for_sa11y')->getConfig('custom_settings');

        // Config-Hash generieren für LocalStorage-Invalidierung
        $configHash = md5(serialize([
            'root' => $root,
            'ignore' => $ignore,
            'custom' => $custom,
            'version' => rex_addon::get('for_sa11y')->getVersion(),
        ]));

        $supportedLanguages = [
            'de_de' => ['js' => 'de', 'setup' => 'De'],
            'en_gb' => ['js' => 'en', 'setup' => 'En'],
            'es_es' => ['js' => 'es', 'setup' => 'Es'],
            'pt_br' => ['js' => 'pt', 'setup' => 'Pt'],
            'it_it' => ['js' => 'it', 'setup' => 'It'],
            'sv_se' => ['js' => 'sv', 'setup' => 'Sv'],
        ];

        $userLanguage = $user->getLanguage();
        if (array_key_exists($userLanguage, $supportedLanguages)) {
            $lang = $supportedLanguages[$userLanguage];
        } else {
            // Standard-Spracheinstellungen, falls die Benutzersprache nicht unterstützt wird
            $lang = ['js' => 'de', 'setup' => 'De'];
        }

        $lang['text'] = rex_clang::getCurrent()->getCode();

        $addon = rex_addon::get('for_sa11y');
        $js = '      
      <link rel="stylesheet" href="' . $addon->getAssetsUrl('vendor/sa11y/dist/css/sa11y.min.css') . '"/>
      <script src="' . $addon->getAssetsUrl('vendor/sa11y/dist/js/sa11y.umd.min.js') . '"></script>
      <script src="' . $addon->getAssetsUrl('vendor/sa11y/dist/js/lang/' . $lang['js'] . '.umd.js') . '"></script>
  
  <script nonce="' . rex_response::getNonce() . '">
  // LocalStorage-Invalidierung bei Config-Änderungen
  (function() {
    const currentConfigHash = "' . $configHash . '";
    const storedConfigHash = localStorage.getItem("sa11y_config_hash");
    
    if (storedConfigHash && storedConfigHash !== currentConfigHash) {
      // Config hat sich geändert - LocalStorage aufräumen
      const keysToRemove = [];
      for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        if (key && key.startsWith("sa11y")) {
          keysToRemove.push(key);
        }
      }
      keysToRemove.forEach(key => localStorage.removeItem(key));
    }
    
    // Aktuellen Hash speichern
    localStorage.setItem("sa11y_config_hash", currentConfigHash);
  })();
     
  Sa11y.Lang.addI18n(Sa11yLang' . $lang['setup'] . '.strings);
  const sa11y = new Sa11y.Sa11y({
    checkRoot: \'' . $root . '\',
    readabilityLang: \'' . $lang['text'] . '\',
    containerIgnore: \'' . $ignore . '\',
    exportResultsPlugin: true,
    ' . $custom . '
  });
</script>        
';
        return $js;
    }
}
