<?php

/**
 * Link-Checker: externe Links serverseitig prüfen.
 *
 * Der Browser kann den Status fremder Seiten nicht lesen (CORS). Gemischte Inhalte (http auf https-Seite),
 * Tracking-Schutz und Inhaltsblocker lassen Abrufe zudem scheitern, obwohl die Seite erreichbar ist.
 * Deshalb fragt das Skript hier an und bekommt den echten HTTP-Status.
 *
 * Nur für angemeldete Backend-Nutzer mit Sa11y-Recht. Ziele in privaten oder reservierten Netzen werden
 * abgelehnt (auch nach Weiterleitungen), damit der Endpunkt nicht ins interne Netz fragen kann.
 *
 * Antwort: {"status": <int>} – HTTP-Status, -1 Zeitüberschreitung/Verbindungsfehler, -2 Domain unbekannt,
 * -3 abgelehnt (ungültig/intern).
 */
class rex_api_for_sa11y_link_check extends rex_api_function
{
    protected $published = true;

    private const TIMEOUT = 8;
    private const MAX_REDIRECTS = 5;
    private const CACHE_TTL = 3600;

    public function execute(): rex_api_result
    {
        $user = rex_backend_login::createUser();
        if (!$user || !rex_backend_login::hasSession() || (!$user->isAdmin() && !$user->hasPerm('for_sa11y[sa11yCheck]'))) {
            $this->send(['status' => -3], 403);
        }

        $url = trim(rex_get('url', 'string', ''));
        $cacheFile = rex_path::addonCache('for_sa11y', 'links/' . sha1($url) . '.json');
        $cached = is_file($cacheFile) ? json_decode((string) rex_file::get($cacheFile), true) : null;
        if (is_array($cached) && isset($cached['status'], $cached['ts']) && time() - (int) $cached['ts'] < self::CACHE_TTL) {
            $this->send(['status' => (int) $cached['status'], 'cached' => true]);
        }

        $status = self::check($url);
        rex_file::put($cacheFile, (string) json_encode(['status' => $status, 'ts' => time()]));
        $this->send(['status' => $status]);
    }

    protected function requiresCsrfProtection(): bool
    {
        // nur lesend; Zugriff ist auf angemeldete Backend-Nutzer beschränkt
        return false;
    }

    /**
     * @param array<string, mixed> $data
     */
    private function send(array $data, int $code = 200): never
    {
        rex_response::cleanOutputBuffers();
        if (200 !== $code) {
            rex_response::setStatus($code . ' ' . (403 === $code ? 'Forbidden' : 'Error'));
        }
        rex_response::sendJson($data);
        exit;
    }

    private static function check(string $url): int
    {
        for ($hop = 0; $hop <= self::MAX_REDIRECTS; ++$hop) {
            $target = self::validate($url);
            if (is_int($target)) {
                return $target;
            }

            $status = self::request($url, 'HEAD');
            // HEAD nicht erlaubt oder von Schutzsystemen anders beantwortet → mit GET nachfragen
            if (in_array($status['code'], [-1, 400, 403, 404, 405, 406, 429, 500, 501, 503], true)) {
                $get = self::request($url, 'GET');
                if ($get['code'] > 0 || $status['code'] < 0) {
                    $status = $get;
                }
            }

            if ($status['code'] >= 300 && $status['code'] < 400 && '' !== $status['location']) {
                $url = self::resolve($url, $status['location']);
                continue;
            }

            return $status['code'];
        }

        return -1;
    }

    /**
     * @return int|true true = in Ordnung, sonst Fehlercode
     */
    private static function validate(string $url): int|bool
    {
        $parts = parse_url($url);
        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        $host = (string) ($parts['host'] ?? '');
        if (!in_array($scheme, ['http', 'https'], true) || '' === $host) {
            return -3;
        }

        $ips = filter_var($host, FILTER_VALIDATE_IP) ? [$host] : array_merge(
            (array) @gethostbynamel($host),
            array_column((array) @dns_get_record($host, DNS_AAAA), 'ipv6'),
        );
        $ips = array_filter($ips);
        if ([] === $ips) {
            return -2;
        }
        foreach ($ips as $ip) {
            if (false === filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return -3;
            }
        }

        return true;
    }

    /**
     * @return array{code: int, location: string}
     */
    private static function request(string $url, string $method): array
    {
        // cURL bevorzugt: rex_socket schreibt bei jedem http-Link eine Warnung ins System-Log
        if (function_exists('curl_init')) {
            $location = '';
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_NOBODY => 'HEAD' === $method,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => false, // Weiterleitungen selbst verfolgen und jede Station prüfen
                CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
                CURLOPT_CONNECTTIMEOUT => self::TIMEOUT,
                CURLOPT_TIMEOUT => self::TIMEOUT,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; REDAXO for_sa11y link check)',
                CURLOPT_HTTPHEADER => ['Accept: text/html,application/xhtml+xml,application/pdf,*/*;q=0.8'],
                // nur die Antwortkopfzeilen auswerten, den Inhalt nach den ersten Bytes verwerfen
                CURLOPT_RANGE => 'GET' === $method ? '0-1023' : null,
                CURLOPT_HEADERFUNCTION => static function ($ch, string $header) use (&$location): int {
                    if (0 === stripos($header, 'location:')) {
                        $location = trim(substr($header, 9));
                    }

                    return strlen($header);
                },
            ]);
            curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            curl_close($ch);

            return ['code' => $code > 0 ? $code : -1, 'location' => $location];
        }

        try {
            $socket = rex_socket::factoryUrl($url);
            $socket->setTimeout(self::TIMEOUT);
            $socket->addHeader('User-Agent', 'Mozilla/5.0 (compatible; REDAXO for_sa11y link check)');
            $response = $socket->doRequest($method);

            return ['code' => $response->getStatusCode(), 'location' => (string) $response->getHeader('location', '')];
        } catch (Throwable) {
            return ['code' => -1, 'location' => ''];
        }
    }

    private static function resolve(string $base, string $location): string
    {
        if (preg_match('~^https?://~i', $location)) {
            return $location;
        }
        $parts = parse_url($base);
        $origin = $parts['scheme'] . '://' . $parts['host'] . (isset($parts['port']) ? ':' . $parts['port'] : '');
        if (str_starts_with($location, '//')) {
            return $parts['scheme'] . ':' . $location;
        }
        if (str_starts_with($location, '/')) {
            return $origin . $location;
        }
        $dir = preg_replace('~/[^/]*$~', '/', (string) ($parts['path'] ?? '/'));

        return $origin . $dir . $location;
    }
}
