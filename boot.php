<?php 
if (rex::getUser() && rex_backend_login::hasSession())
{ 
    rex_perm::register('for_sa11y[sa11yCheck]');

    if (rex::isBackend() && rex_be_controller::getCurrentPagePart(1) === 'for_sa11y') {
        rex_view::addJsFile(rex_addon::get('for_sa11y')->getAssetsUrl('backend.js'));
    }
}
