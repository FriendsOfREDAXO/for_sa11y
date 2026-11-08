<?php

/**
 * Link-Checker: Links serverseitig prüfen (alle Links einer Seite in einer Anfrage, parallel per cURL).
 *
 * Der Browser kann den Status fremder Seiten nicht lesen (CORS); gemischte Inhalte, Tracking-Schutz und
 * Inhaltsblocker ließen erreichbare Links defekt aussehen. Eigene Seiten einzeln im Browser abzurufen dauerte
 * bei vielen Links zu lange. Deshalb prüft der Server alle Links gleichzeitig – so, wie ein Besucher ohne
 * Anmeldung sie sieht (ein Link auf eine Offline-Seite gilt also als defekt).
 *
 * Aufruf: POST JSON {"urls": [...]} → {"results": {"<url>": <status>}}  (auch GET ?url=… für eine einzelne URL)
 * Status: HTTP-Code, -1 Zeitüberschreitung/Verbindungsfehler, -2 Domain unbekannt, -3 abgelehnt (ungültig/intern).
 *
 * Nur für angemeldete Backend-Nutzer mit Sa11y-Recht. Ziele in privaten oder reservierten Netzen werden
 * abgelehnt, auch nach Weiterleitungen (jede Station wird geprüft), damit der Endpunkt nicht ins interne Netz fragt.
 */
class rex_api_for_sa11y_link_check extends rex_api_function
{
    protected $published = true;

    private const TIMEOUT = 8;
    private const MAX_REDIRECTS = 5;
    private const MAX_URLS = 400;
    private const PARALLEL = 12;
    private const CACHE_TTL = 3600;
    private const USER_AGENT = 'Mozilla/5.0 (compatible; REDAXO for_sa11y link check)';

    /** @var array<string, int|true> Host => Prüfergebnis */
    private static array $hostCache = [];

    public function execute(): rex_api_result
    {
        $user = rex_backend_login::createUser();
        if (!$user || !rex_backend_login::hasSession() || (!$user->isAdmin() && !$user->hasPerm('for_sa11y[sa11yCheck]'))) {
            $this->send(['error' => 'forbidden'], 403);
        }

        $urls = [];
        if ('POST' === rex_request_method() || 'post' === rex_request_method()) {
            $input = json_decode((string) file_get_contents('php://input'), true);
            $urls = is_array($input['urls'] ?? null) ? $input['urls'] : [];
        } else {
            $urls = [rex_get('url', 'string', '')];
        }
        $urls = array_slice(array_values(array_unique(array_filter(array_map(
            static fn ($u): string => is_string($u) ? trim($u) : '',
            $urls,
        )))), 0, self::MAX_URLS);

        $results = [];
        $todo = [];
        foreach ($urls as $url) {
            $cached = self::cacheGet($url);
            if (null !== $cached) {
                $results[$url] = $cached;
            } else {
                $todo[] = $url;
            }
        }
        foreach (self::checkMany($todo) as $url => $status) {
            $results[$url] = $status;
            self::cacheSet($url, $status);
        }

        if ('GET' === strtoupper(rex_request_method())) {
            $this->send(['status' => $results[$urls[0] ?? ''] ?? -3]);
        }
        $this->send(['results' => (object) $results]);
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
        // Status immer setzen: YRewrite markiert /index.php vorab als 404, sonst ginge die Antwort mit 404 raus
        rex_response::setStatus(200 === $code ? rex_response::HTTP_OK : $code . ' ' . (403 === $code ? 'Forbidden' : 'Error'));
        rex_response::sendJson($data);
        exit;
    }

    /**
     * Prüft viele URLs parallel, Weiterleitungen Station für Station.
     *
     * @param list<string> $urls
     * @return array<string, int>
     */
    private static function checkMany(array $urls): array
    {
        $final = [];
        $current = array_combine($urls, $urls) ?: []; // Ausgangs-URL => aktuelle Station

        for ($hop = 0; $hop <= self::MAX_REDIRECTS && [] !== $current; ++$hop) {
            foreach ($current as $origin => $url) {
                $valid = self::validate($url);
                if (true !== $valid) {
                    $final[$origin] = $valid;
                    unset($current[$origin]);
                }
            }

            $responses = self::requestMany($current, 'HEAD');
            // HEAD nicht erlaubt oder von Schutzsystemen anders beantwortet → mit GET nachfragen
            $retry = array_filter($responses, static fn (array $r): bool => in_array($r['code'], [-1, 0, 400, 403, 404, 405, 406, 429, 500, 501, 503], true));
            foreach (self::requestMany(array_intersect_key($current, $retry), 'GET') as $origin => $get) {
                if ($get['code'] > 0 || $responses[$origin]['code'] <= 0) {
                    $responses[$origin] = $get;
                }
            }

            $next = [];
            foreach ($responses as $origin => $response) {
                if ($response['code'] >= 300 && $response['code'] < 400 && '' !== $response['location']) {
                    $next[$origin] = self::resolve($current[$origin], $response['location']);
                } else {
                    $final[$origin] = $response['code'] > 0 ? $response['code'] : -1;
                }
            }
            $current = $next;
        }

        foreach ($current as $origin => $url) {
            $final[$origin] = -1; // zu viele Weiterleitungen
        }

        return $final;
    }

    /**
     * @return int|true true = in Ordnung, sonst Fehlercode
     */
    private static function validate(string $url): int|bool
    {
        $parts = parse_url($url);
        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        $host = strtolower((string) ($parts['host'] ?? ''));
        if (!in_array($scheme, ['http', 'https'], true) || '' === $host) {
            return -3;
        }
        if (isset(self::$hostCache[$host])) {
            return self::$hostCache[$host];
        }

        $ips = filter_var($host, FILTER_VALIDATE_IP) ? [$host] : array_merge(
            (array) @gethostbynamel($host),
            array_column((array) @dns_get_record($host, DNS_AAAA), 'ipv6'),
        );
        $ips = array_filter($ips);
        $result = true;
        if ([] === $ips) {
            $result = -2;
        }
        foreach ($ips as $ip) {
            if (false === filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $result = -3;
            }
        }

        return self::$hostCache[$host] = $result;
    }

    /**
     * @param array<string, string> $urls Schlüssel => URL
     * @return array<string, array{code: int, location: string}>
     */
    private static function requestMany(array $urls, string $method): array
    {
        if ([] === $urls) {
            return [];
        }
        if (!function_exists('curl_multi_init')) {
            $out = [];
            foreach ($urls as $key => $url) {
                $out[$key] = self::requestSocket($url, $method);
            }

            return $out;
        }

        $out = [];
        $locations = [];
        foreach (array_chunk($urls, self::PARALLEL, true) as $chunk) {
            $multi = curl_multi_init();
            $handles = [];
            foreach ($chunk as $key => $url) {
                $locations[$key] = '';
                $ch = curl_init($url);
                curl_setopt_array($ch, [
                    CURLOPT_NOBODY => 'HEAD' === $method,
                    CURLOPT_CUSTOMREQUEST => $method,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => false, // Weiterleitungen selbst verfolgen und jede Station prüfen
                    CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
                    CURLOPT_CONNECTTIMEOUT => self::TIMEOUT,
                    CURLOPT_TIMEOUT => self::TIMEOUT,
                    CURLOPT_USERAGENT => self::USER_AGENT,
                    CURLOPT_HTTPHEADER => ['Accept: text/html,application/xhtml+xml,application/pdf,*/*;q=0.8'],
                    CURLOPT_HEADERFUNCTION => static function ($ch, string $header) use (&$locations, $key): int {
                        if (0 === stripos($header, 'location:')) {
                            $locations[$key] = trim(substr($header, 9));
                        }

                        return strlen($header);
                    },
                ]);
                if ('GET' === $method) {
                    curl_setopt($ch, CURLOPT_RANGE, '0-1023'); // nur der Anfang, der Inhalt ist egal
                }
                curl_multi_add_handle($multi, $ch);
                $handles[$key] = $ch;
            }

            do {
                $status = curl_multi_exec($multi, $running);
                if ($running) {
                    curl_multi_select($multi, 1.0);
                }
            } while ($running && CURLM_OK === $status);

            foreach ($handles as $key => $ch) {
                $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
                $out[$key] = ['code' => $code > 0 ? $code : -1, 'location' => $locations[$key]];
                curl_multi_remove_handle($multi, $ch);
            }
            curl_multi_close($multi);
        }

        return $out;
    }

    /**
     * @return array{code: int, location: string}
     */
    private static function requestSocket(string $url, string $method): array
    {
        try {
            $socket = rex_socket::factoryUrl($url);
            $socket->setTimeout(self::TIMEOUT);
            $socket->addHeader('User-Agent', self::USER_AGENT);
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

    private static function cacheFile(string $url): string
    {
        return rex_path::addonCache('for_sa11y', 'links/' . sha1($url) . '.json');
    }

    private static function cacheGet(string $url): ?int
    {
        $file = self::cacheFile($url);
        if (!is_file($file)) {
            return null;
        }
        $data = json_decode((string) rex_file::get($file), true);
        if (!is_array($data) || !isset($data['status'], $data['ts']) || time() - (int) $data['ts'] >= self::CACHE_TTL) {
            return null;
        }

        return (int) $data['status'];
    }

    private static function cacheSet(string $url, int $status): void
    {
        rex_file::put(self::cacheFile($url), (string) json_encode(['status' => $status, 'ts' => time()]));
    }
}
