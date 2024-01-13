<?php
class for_sa11y
{
    public static function get(): void
    {
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
                $lang = ['js' => 'en', 'setup' => 'En'];
            }

            $lang["text"] = rex_clang::getCurrent()->getCode();

            $addon = rex_addon::get('for_sa11y');
            $js = '      
      <link rel="stylesheet" href="' . $addon->getAssetsUrl("dist/css/sa11y.min.css") . '"/>
      <script src="' . $addon->getAssetsUrl("dist/js/sa11y.umd.min.js") . '"></script>
      <script src="' . $addon->getAssetsUrl("dist/js/lang/" . $lang['js'] . ".umd.js") . '"></script>
  
  <script>     
  Sa11y.Lang.addI18n(Sa11yLang' . $lang["setup"] . '.strings);
  const sa11y = new Sa11y.Sa11y({
    checkRoot: \'body\',
    readabilityLang: \'' . $lang["text"] . '\',
    containerIgnore: \'.rex-minibar,.consent_manager-wrapper,.sa11y-ignore\',
  });
</script>        
';
            echo $js;
        }
    }
}
