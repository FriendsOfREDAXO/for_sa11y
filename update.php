<?php

/**
 * FOR Sa11y – Update-Skript.
 *
 * Wird bei jedem Update ausgeführt. Migriert Konfigurationswerte
 * aus älteren Versionen auf das neue Format.
 */

$addon = rex_addon::get('for_sa11y');

// ---------------------------------------------------------------
// Migration: panel_position
// Sa11y 4.x verwendete 'bottom-right' / 'bottom-left'.
// Sa11y 5.0 kennt nur noch 'right' / 'left' / 'top-right' / 'top-left'.
// ---------------------------------------------------------------
$panelPosition = (string) $addon->getConfig('panel_position', '');
if ($panelPosition !== '') {
    $migrated = str_replace(
        ['bottom-right', 'bottom-left'],
        ['right', 'left'],
        $panelPosition,
    );
    if ($migrated !== $panelPosition) {
        $addon->setConfig('panel_position', $migrated);
    }
}

// ---------------------------------------------------------------
// Migration: links_to_flag entfernen
// Das Prop wurde in Sa11y 5.0 ersatzlos entfernt.
// ---------------------------------------------------------------
if ($addon->hasConfig('links_to_flag')) {
    $addon->removeConfig('links_to_flag');
}
