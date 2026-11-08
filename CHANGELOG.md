# Changelog

## 5.2.1 – 2026-11-08

### Link-Checker korrigiert
- **Externe Links prüft jetzt der Server** (`rex-api-call=for_sa11y_link_check`) mit dem echten HTTP-Status. Bisher fragte der Browser im `no-cors`-Modus an. Jeder blockierte Abruf galt dann als „Server nicht erreichbar“, z. B. `http://`-Links auf `https`-Seiten, Abrufe, die Safaris Tracking-Schutz oder ein Inhaltsblocker stoppt, oder Server, die HEAD abweisen. Das führte zu Fehltreffern bei erreichbaren Links.
- **Sicherer Endpunkt:**
  - Er ist nur für angemeldete Backend-Nutzer mit Sa11y-Recht nutzbar.
  - Er erlaubt nur `http`/`https`.
  - Private und reservierte Netze werden abgelehnt, auch nach Weiterleitungen.
  - Ergebnisse werden 1 Stunde zwischengespeichert.
  - Die Anfragen laufen über cURL, sodass keine Log-Warnungen bei `http`-Links entstehen.
- **Nur eindeutige Fälle melden:** 404, 410, 5xx außer 503 und unbekannte Domains. 401, 403, 429, 503 und Zeitüberschreitungen sind meist Bot-Schutz und werden nicht mehr gemeldet.
- **Alle Links in einer Anfrage**: Der Browser schickt alle Links der Seite gesammelt an den Server. Der prüft sie parallel per `curl_multi`, 143 Links in etwa 11 Sekunden statt 30 bis 40. Bisher erschienen die Markierungen beim ersten Aufruf zu spät und waren oft erst nach dem Neuladen zu sehen.
- **Interne Links** prüft jetzt ebenfalls der Server, so wie ein Besucher ohne Anmeldung sie sieht. Ein Link auf eine Offline-Seite gilt also als defekt.
- Links auf dieselbe Seite mit unterschiedlichen Sprungmarken werden nur einmal geprüft.
- **Neu: „Link-Checker nur in diesen Bereichen“**: Die Prüfung lässt sich per CSS-Selektor auf Bereiche eingrenzen, z. B. nur den Inhalt. Leer bedeutet der Prüfbereich von Sa11y.

## 5.2.0 – 2026-11-08

### Neu
- **Automatisch einbinden** (Grundeinstellungen, Standard aus): Sa11y wird im Frontend vor `</body>` gesetzt, ohne Eintrag im Template. Steht `Sa11y::get()` schon im Template, wird es nicht doppelt eingebunden.
- **Ausschlüsse je Prüfungsbereich** als eigene Felder: `contrastIgnore`, `linkIgnore`, `imageIgnore`, `headerIgnore`, `outlineIgnore`, `paragraphIgnore`, `ignoreHiddenOverflow` sowie die erste Überschriftenebene (`initialHeadingLevel`).
- **Prüfungen abschalten**: gruppierte Mehrfachauswahl aller Sa11y-Prüfungen. Ein `checks`-Block in den individuellen Einstellungen wird ergänzt und hat Vorrang.
- **Verbotene Link-Ziele** (`QA_BAD_LINK`), z. B. Links auf den Testserver.
- **„Bearbeiten“ in der Bildübersicht führt in den Medienpool**, auch bei Media-Manager-Pfaden (`rex-api-call=for_sa11y_media_edit`).
- **Anzeige**: Panel verschiebbar (`showMovePanelToggle`) und Hinweise ignorieren (`dismissAnnotations`) einstellbar.

### Bugfixes
- Fehlende Übersetzung `root_empty` (Meldung bei leerem „Prüfen ab Selektor“).
- README: Abschnitt „4. Links“ beschrieb noch `linksToFlag` aus Sa11y 4.

### Hinweis Link-Checker
- Der Link-Checker bleibt erhalten: Sa11y 5.1 prüft nur Sprungmarken (`QA_IN_PAGE_LINK`) und vorgegebene Selektoren (`QA_BAD_LINK`), ruft Links aber nicht auf. 404 und Serverfehler findet nur der Link-Checker des AddOns.

## 5.1.1 – 2026-11-08

### Bugfixes
- **Nonce für alle Scripts**: Sa11y, Sprachdatei und Link-Checker bekommen jetzt die CSP-Nonce, nicht nur das Inline-Script. Ohne sie blockiert eine strenge Content-Security-Policy den Link-Checker. Sa11y wartet dann im Modus „listen“ bis zu 20 Sekunden, bevor es Markierungen setzt.
- **Eigene Einstellungen aus Sa11y 4**: `panelPosition: "bottom-right"` bzw. `"bottom-left"` in den eigenen Einstellungen wird auf `right` bzw. `left` umgeschrieben, bei der Ausgabe und einmalig beim Update. Bisher überschrieb der alte Wert die Position aus dem Formular, und Sa11y fiel still auf `right` zurück.

### Sa11y Bibliothek
- Geprüft: 5.1.0 ist weiterhin die aktuelle Version (npm und GitHub).

## 5.0.0 – 2026-04-17

### Sa11y Bibliothek
- **Sa11y auf Version 5.0 aktualisiert** (vorher 4.4.1) – [Sa11y 5.0 Release Notes](https://github.com/ryersondmp/sa11y/releases/tag/5)
- Sa11y 5.0 bringt umfangreiche Überarbeitungen: XSS-Härtung, Performance-Verbesserungen, kleinere Bundle-Größe (~10 % weniger durch Migration von Tippy.js auf Floating UI)
- Neue Tamil-Übersetzung (`ta`), erweiterte Sprachunterstützung im AddOn

### Breaking Changes (Sa11y 5.0)
- **`panelPosition`** – Positionswerte geändert: `bottom-right` → `right`, `bottom-left` → `left`. Bestehende Konfigurationen werden automatisch migriert.
- **`linksToFlag`** – Prop wurde von Sa11y 5.0 entfernt. Das zugehörige Konfigurationsfeld wurde aus dem AddOn entfernt.

### Neue Konfigurationsfelder

**Plugins**
- `contrast_algorithm` – Kontrast-Algorithmus wählbar: WCAG 2 AA (Standard), AAA oder APCA (experimentell)
- `readability_ignore` – CSS-Selektoren von der Lesbarkeits-Prüfung ausschließen (z. B. `.sidebar, .footer`)
- `lang_of_parts_plugin` – Experimentelle KI-Spracherkennung (Chrome Only, `langOfPartsPlugin`)
- `lang_of_parts_cache` – Caching der Spracherkennungsergebnisse (`langOfPartsCache`)

**Erweiterte Einstellungen (neue Sektion)**
- `do_not_run` – CSS-Selektor: Sa11y startet nicht wenn Element gefunden (`doNotRun`)
- `developer_plugin` – Developer-Plugin aktivieren/deaktivieren
- `developer_checks_on_by_default` – Developer-Prüfungen automatisch einschalten
- `auto_detect_shadow_components` – Web Components / Shadow DOM automatisch erkennen

### Bugfixes
- **Contrast-, FormLabels- und Readability-Plugin** wurden nicht deaktiviert wenn in der Konfiguration auf „Nein" gestellt: Sa11y 5 hat diese Plugins standardmäßig aktiviert, das AddOn setzte sie bisher nur auf `true`, aber nie explizit auf `false` → behoben

### Verbesserungen
- Mehrere Formulare in der Konfiguration waren nicht voneinander getrennt (gleiches Form-Hash-Problem) – behoben durch eindeutige Fieldset-Keys
- Labels mit Anführungszeichen (`"Gute Bilder"`) wurden doppelt HTML-escaped – behoben durch `rex_i18n::rawMsg()`
- Erweiterte Sprachunterstützung in `detectLanguage()`: `fr_fr`, `nl_nl`, `pl_pl`, `pt_pt`, `uk_ua`, `en_us`
- Neue Accordion-Hilfe-Beispiele: Ignore-Selektoren, `altPlaceholder`, `initialHeadingLevel`, Shadow DOM, `fixedRoots`, `ignoreByTest`
- Duplikate in Lang-Dateien bereinigt
- Rexstan-Typ-Fix: `rex_addon_interface` statt `rex_addon` als Parameter-Typ

### Weitere AddOn-Features (5.0.x)

**Link-Checker** (`sa11y-linkchecker.js`, opt-in)
- Prüft alle Links der Seite live per HTTP HEAD-Request im Browser-Hintergrund
- Defekte Links erscheinen **nativ im Sa11y-Fehlerpanel** (kein separates Badge-UI)
- Integration via Sa11y 5 `customChecks: "listen"`-API: Zwei-Pass-Ansatz – Sa11y-Panel erscheint sofort, defekte Links folgen nach den Netzwerk-Checks
- Nicht-blockierend: nutzt die native `customChecks: "listen"`-API von Sa11y; Sa11y rendert sofort, defekte Links werden nach den Netzwerk-Checks nachgereicht
- Ergebnisse werden 5 Minuten im `sessionStorage` gecacht (kein Repeat bei SPA-Navigation)
- Konfigurierbar: externe Links ein/aus, Ignore-Selektoren
- HEAD 405-Fallback auf GET für Server, die HEAD nicht unterstützen
- Timeout (8 s) und Netzwerkfehler (DNS-Fehler, Server nicht erreichbar) werden als Fehler gemeldet

**URL-Parameter-Ausschluss** (`exclude_url_params`)
- Sa11y startet nicht bei konfigurierten URL-Parametern (z. B. `pdf, print, export=1`)
- Unterstützt einfache Parameternamen (Prüfung auf Vorhandensein) und `key=wert`-Paare

**Live-Vorschau Panel-Position**
- Die Konfigurations-Seite zeigt eine animierte Miniaturvorschau der gewählten Panel-Position

**Versionsbenachrichtigung**
- Einmal täglich prüft das AddOn die Sa11y GitHub Releases API
- Bei verfügbarer neuerer Version erscheint ein Hinweis auf der Konfigurationsseite mit Link zu den Release Notes

---

## 4.4.1

Letzte stabile Version mit Sa11y 4.x. Siehe [Sa11y 4.x Changelog](https://github.com/ryersondmp/sa11y/releases) für Details zur Bibliothek.
