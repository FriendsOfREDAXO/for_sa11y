<?php
namespace FriendsOfRedaxo\Sa11y;
use rex;
use rex_addon;
use rex_clang;
use rex_backend_login;
use rex_category;

class Sa11y
{
    /**
     * @api
     */
    public static function get(): string
    {
        if ((rex_backend_login::createUser() && !rex::getUser()->isAdmin() && !rex::getUser()->hasPerm('for_sa11y[sa11yCheck]')) || rex_addon::get('for_sa11y')->getConfig('active')  === 'false') {
            return '';
        }

        $root = rex_escape(rex_addon::get('for_sa11y')->getConfig('root','body'));
        $ignore = rex_escape(rex_addon::get('for_sa11y')->getConfig('ignore')); 
        $custom = rex_addon::get('for_sa11y')->getConfig('custom_settings'); 
        
        if (rex_backend_login::createUser() !== null && rex_backend_login::hasSession()) {
            $user = rex_backend_login::createUser();
            $supportedLanguages = [
                'de_de' => ['js' => 'de', 'setup' => 'De'],
                'en_gb' => ['js' => 'en', 'setup' => 'En'],
                'es_es' => ['js' => 'es', 'setup' => 'Es'],
                'pt_br' => ['js' => 'pt', 'setup' => 'Pt'],
                'it_it' => ['js' => 'it', 'setup' => 'It'],
                'sv_se' => ['js' => 'sv', 'setup' => 'Sv']
            ];

            $userLanguage = $user->getLanguage();
            if (array_key_exists($userLanguage, $supportedLanguages)) {
                $lang = $supportedLanguages[$userLanguage];
            } else {
                // Standard-Spracheinstellungen, falls die Benutzersprache nicht unterstützt wird
                $lang = ['js' => 'de', 'setup' => 'De'];
            }

            $lang["text"] = rex_clang::getCurrent()->getCode();

            $addon = rex_addon::get('for_sa11y');
            
            // Create configuration hash to detect changes and clear storage when needed
            $configHash = md5($root . '|' . $ignore . '|' . $custom);
            
            $js = '      
      <link rel="stylesheet" href="' . $addon->getAssetsUrl("vendor/sa11y/dist/css/sa11y.min.css") . '"/>
      <script src="' . $addon->getAssetsUrl("vendor/sa11y/dist/js/sa11y.umd.min.js") . '"></script>
      <script src="' . $addon->getAssetsUrl("vendor/sa11y/dist/js/lang/" . $lang['js'] . ".umd.js") . '"></script>
  
  <script nonce="<?=rex_response::getNonce()?>">     
  // Clear Sa11y storage if configuration has changed
  (function() {
    const currentConfigHash = \'' . $configHash . '\';
    const storedConfigHash = localStorage.getItem(\'sa11y-config-hash\') || sessionStorage.getItem(\'sa11y-config-hash\');
    
    if (storedConfigHash !== currentConfigHash) {
      // Configuration has changed, clear Sa11y storage
      const sa11yKeys = [\'sa11y-panel\', \'sa11y-dismissed\', \'sa11y-latest-dismissed\', \'sa11y-dismiss-item\', \'sa11y-developer\'];
      sa11yKeys.forEach(key => {
        try {
          localStorage.removeItem(key);
          sessionStorage.removeItem(key);
        } catch (e) {
          // Ignore storage errors
        }
      });
      
      // Store new configuration hash
      try {
        localStorage.setItem(\'sa11y-config-hash\', currentConfigHash);
      } catch (e) {
        try {
          sessionStorage.setItem(\'sa11y-config-hash\', currentConfigHash);
        } catch (e) {
          // Ignore storage errors
        }
      }
    }
  })();
  
  Sa11y.Lang.addI18n(Sa11yLang' . $lang["setup"] . '.strings);
  const sa11y = new Sa11y.Sa11y({
    checkRoot: \'' . $root . '\',
    readabilityLang: \'' . $lang["text"] . '\',
    containerIgnore: \'' . $ignore . '\',
    exportResultsPlugin: true,
    ' . $custom . '
  });
</script>        
';
            return $js;
        }
        return '';
    }
}
