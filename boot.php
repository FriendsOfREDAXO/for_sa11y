<?php
$addon = rex_addon::get('for_sa11y');
if (rex::isBackend() && is_object(rex::getUser())) {
    rex_view::addCssFile($addon->getAssetsUrl('dist/css/sa11y.min.css'));
    rex_view::addJsFile($addon->getAssetsUrl('dist/js/sa11y.umd.min.js'));   
    rex_view::addJsFile($addon->getAssetsUrl('dist/js/lang/en.umd.js'));
    rex_view::addJsFile($addon->getAssetsUrl('dist/js/sa11y-custom-checks.umd.min.js'));
    rex_view::addJsFile($addon->getAssetsUrl('backend_init.js'));
}
