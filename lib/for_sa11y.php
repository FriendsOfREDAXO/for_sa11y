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
            $js = '      
      <link rel="stylesheet" href="' . $addon->getAssetsUrl("vendor/sa11y/dist/css/sa11y.min.css") . '"/>
      <script src="' . $addon->getAssetsUrl("vendor/sa11y/dist/js/sa11y.umd.min.js") . '"></script>
      <script src="' . $addon->getAssetsUrl("vendor/sa11y/dist/js/lang/" . $lang['js'] . ".umd.js") . '"></script>
  
  <script nonce="<?=rex_response::getNonce()?>">     
  Sa11y.Lang.addI18n(Sa11yLang' . $lang["setup"] . '.strings);
  
  // Standard-Konfiguration mit benutzerdefinierten Einstellungen mergen
  const sa11yConfig = {
    checkRoot: \'' . $root . '\',
    readabilityLang: \'' . $lang["text"] . '\',
    containerIgnore: \'' . $ignore . '\',
    exportResultsPlugin: true
  };
  
  ' . (!empty($custom) ? '
  // Custom settings hinzufügen
  const customConfig = {' . $custom . '};
  Object.assign(sa11yConfig, customConfig);
  ' : '') . '
  
  const sa11y = new Sa11y.Sa11y(sa11yConfig);
</script>        
';
            return $js;
        }
        return '';
    }
}
