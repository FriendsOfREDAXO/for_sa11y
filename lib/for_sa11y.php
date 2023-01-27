<?php
class for_sa11y
{
  public static function get(): void
  {
    if (rex_backend_login::createUser() !== null && rex_backend_login::hasSession()) {

      $addon = rex_addon::get('for_sa11y');
      $js = '      
      <link rel="stylesheet" href="' . $addon->getAssetsUrl("dist/css/sa11y.min.css") . '"/>
      <script src="' . $addon->getAssetsUrl("dist/js/sa11y.umd.min.js") . '"></script>
      <script src="' . $addon->getAssetsUrl("dist/js/lang/en.umd.js") . '"></script>
       <script src="' . $addon->getAssetsUrl("dist/js/sa11y-custom-checks.umd.min.js") . '"></script>
  <script>     
  Sa11y.Lang.addI18n(Sa11yLangDe.strings);
  const sa11y = new Sa11y.Sa11y({
    customChecks: new CustomChecks,
    checkRoot: \'body\',
    readabilityLang:	\'de\',
    containerIgnore: \'.rex-minibar,.sa11y-ignore\',
  });
</script>        
';
      echo $js;
    }
  }
}
