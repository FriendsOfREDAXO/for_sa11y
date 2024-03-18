<?php
$package = rex_addon::get('for_sa11y');
echo rex_view::title($package->i18n('for_sa11y_title')); 
rex_be_controller::includeCurrentPageSubPath();
