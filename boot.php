<?php 
if (rex::getUser() && rex_backend_login::hasSession())
{ 
    rex_perm::register('for_sa11y[sa11yCheck]');
}
