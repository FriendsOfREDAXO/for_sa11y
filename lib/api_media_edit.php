<?php

/**
 * „Bearbeiten“ in der Bildübersicht von Sa11y → Datei im Medienpool öffnen.
 *
 * Sa11y hängt den Pfad hinter /media/ an (z. B. „is_ratio_16_9__800/foto.jpg“ oder „foto.jpg“).
 * Der Dateiname wird ermittelt und ins Backend weitergeleitet; die Anmeldung prüft das Backend selbst.
 */
class rex_api_for_sa11y_media_edit extends rex_api_function
{
    protected $published = true;

    public function execute(): rex_api_result
    {
        $path = rex_get('path', 'string', '');
        $path = (string) parse_url($path, PHP_URL_PATH);
        $fileName = rex_path::basename($path);

        $params = [];
        if ('' !== $fileName && null !== rex_media::get($fileName)) {
            $params['file_name'] = $fileName;
        }

        rex_response::sendRedirect(rex_url::backendPage('mediapool/media', $params, false));
    }

    protected function requiresCsrfProtection(): bool
    {
        return false;
    }
}
