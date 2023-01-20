<?php
if (rex::isBackend() && is_object(rex::getUser())) {
    rex_view::addCssFile($plyr->getAssetsUrl('dist/css/sa11y.min.css'));
    rex_view::addJsFile($plyr->getAssetsUrl('sa11y.umd.min.js'));   
    rex_view::addJsFile($plyr->getAssetsUrl('dist/js/lang/en.umd.js'));
    rex_view::addJsFile($plyr->getAssetsUrl('dist/js/sa11y-custom-checks.umd.min.js'));
    rex_view::addJsFile($plyr->getAssetsUrl('backend_init.js'));
}
