<?php

use FriendsOfRedaxo\Sa11y\Sa11y;

if (rex::getUser() && rex_backend_login::hasSession())
{
    rex_perm::register('for_sa11y[sa11yCheck]');

    if (rex::isBackend() && rex_be_controller::getCurrentPagePart(1) === 'for_sa11y') {
        rex_view::addJsFile(rex_addon::get('for_sa11y')->getAssetsUrl('backend.js'));
    }
}

// Option „Automatisch einbinden“: Sa11y vor </body> setzen, ohne Eintrag im Template.
// Steht Sa11y::get() schon im Template, passiert nichts. Sa11y::get() prüft Anmeldung und Rechte selbst.
if (rex::isFrontend() && 1 === (int) rex_addon::get('for_sa11y')->getConfig('auto_inject', 0)) {
    rex_extension::register('OUTPUT_FILTER', static function (rex_extension_point $ep) {
        return Sa11y::injectIntoOutput((string) $ep->getSubject());
    }, rex_extension::LATE);
}
