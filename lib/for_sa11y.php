<?php
class for_sa11y
{
  public static function get(): void
  {
    if (rex_backend_login::createUser() !== null && rex_backend_login::hasSession()) {

   
    $lang = [];
    $lang["js"] = 'de';
    $lang["setup"] = 'De'; 
    $lang["text"] = rex_clang::getCurrent()->getCode();
    
    $user = rex_backend_login::createUser();
    if ($user->getLanguage() === 'en_gb')
    {
      $lang["js"] = 'en';
      $lang["setup"] = 'En';
    }
      $addon = rex_addon::get('for_sa11y');
      $js = '      
      <link rel="stylesheet" href="' . $addon->getAssetsUrl("dist/css/sa11y.min.css") . '"/>
      <script src="' . $addon->getAssetsUrl("dist/js/sa11y.umd.min.js") . '"></script>
      <script src="' . $addon->getAssetsUrl("dist/js/lang/".$lang['js'].".umd.js") . '"></script>
  
  <script>     
  Sa11y.Lang.addI18n(Sa11yLang'.$lang["setup"].'.strings);
  const sa11y = new Sa11y.Sa11y({
    checkRoot: \'body\',
    contrastPlugin: false,
    readabilityLang:	\''.$lang["text"].'\',
    containerIgnore: \'.rex-minibar,.sa11y-ignore\',
  });
</script>        
';
      echo $js;
    }
  }
}
