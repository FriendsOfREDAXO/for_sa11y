<?php
class for_sa11y
{
  /**
   * @return string
   */
  public static function get(): string
  {
    $addon = rex_addon::get('for_sa11y');
    $js = '      
      <link rel="stylesheet" href="' . $addon->getAssetsUrl("dist/css/sa11y.min.css") . '"/>
      <script src="' . $addon->getAssetsUrl("dist/js/sa11y.umd.min.js") . '"></script>
      <script src="' . $addon->getAssetsUrl("dist/js/lang/en.umd.js") . '"></script>

       <script src="' . $addon->getAssetsUrl("dist/js/sa11y-custom-checks.umd.min.js") . '"></script>
  <script>     
  Sa11y.Lang.addI18n(Sa11yLangEn.strings);
  const sa11y = new Sa11y.Sa11y({
    customChecks: new CustomChecks, // Optional
    checkRoot: "body",
    containerIgnore: \'.rex-minibar\',
  });
</script>        
';
    return $js;
  }
}
