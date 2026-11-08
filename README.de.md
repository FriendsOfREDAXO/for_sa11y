# FOR Sa11y - REDAXO AddOn

FOR Sa11y fügt ["Sa11y"](https://sa11y.netlify.app) zum REDAXO-Frontend hinzu, sobald ein Backend-Benutzer eingeloggt ist.

Sa11y hebt Probleme mit der Barrierefreiheit direkt an der Quelle visuell hervor und zeigt Tooltips mit Lösungsvorschlägen an.

Das AddOn dient als redaktionelle Hilfestellung, um Barrierefreiheitsprobleme auf der Website zu reduzieren.

![Sa11y, the accessibility quality assurance tool.](https://camo.githubusercontent.com/256164e3ce95ee8ed53fcb2cd25b213c7aef03b64dbf1c8677eaa5ed4d8d39a0/68747470733a2f2f72796572736f6e646d702e6769746875622e696f2f73613131792f6769746875622d62616e6e65722e706e67)

> **Version 5.0** – Sa11y-Bibliothek auf Version 5.0 aktualisiert. Siehe [Upgrade-Hinweise](#upgrade-hinweise-50) weiter unten.

---

## Installation

1. AddOn über den REDAXO-Installer installieren oder manuell hochladen.
2. Folgenden Snippet ins Template einfügen, am Ende oder Anfang von `<body>`:

```php
<?= \FriendsOfRedaxo\Sa11y\Sa11y::get() ?>
```

Sa11y erscheint automatisch für jeden eingeloggten Backend-Benutzer.

**Ohne Template-Änderung:** In den Grundeinstellungen „Automatisch einbinden“ auf „ja“ stellen. Dann setzt das AddOn Sa11y im Frontend vor `</body>`. Steht `Sa11y::get()` bereits im Template, wird es nicht doppelt eingebunden.

---

## Konfiguration

Alle Einstellungen werden auf der Konfigurationsseite des AddOns im REDAXO-Backend verwaltet. Sie sind in sechs Bereiche gegliedert:

### 1. Grundeinstellungen

| Einstellung | Beschreibung |
|---|---|
| **Aktiv** | Sa11y global aktivieren oder deaktivieren |
| **Root-Element** | CSS-Selektor für den zu prüfenden Inhaltsbereich (Standard: `body`) |
| **Ignorierte Selektoren** | CSS-Selektoren, die von allen Prüfungen ausgeschlossen werden |
| **Panel-Position** | `right` (Standard), `left`, `top-right`, `top-left` |
| **Prüf-Verzögerung (ms)** | Verzögerung vor dem Start (sinnvoll für SPAs) |
| **SPA-Routing erkennen** | Prüfungen bei clientseitiger Navigation erneut ausführen |

### 2. Anzeige

| Einstellung | Beschreibung |
|---|---|
| **"Gutes Bild"-Button anzeigen** | Bilder mit gutem Alt-Text hervorheben |
| **"Guter Link"-Button anzeigen** | Links mit beschreibendem Text hervorheben |
| **Panel verschiebbar** | Schalter in den Sa11y-Einstellungen, um das Panel auf die andere Seite zu setzen |
| **Hinweise ignorieren erlauben** | Redakteure können einzelne Warnungen als gesehen markieren |
| **„Bearbeiten“ führt in den Medienpool** | In der Bildübersicht von Sa11y öffnet „Bearbeiten“ die Datei im Medienpool, auch bei Media-Manager-Pfaden wie `/media/typ/datei.jpg` |

### 3. Plugins

| Einstellung | Beschreibung |
|---|---|
| **Kontrast-Plugin** | WCAG-Farbkontrastprüfung aktivieren |
| **Kontrast-Algorithmus** | `AA` (Standard), `AAA` oder `APCA` (experimentell) |
| **Formular-Labels-Plugin** | Auf fehlende Formular-Labels prüfen |
| **Lesbarkeits-Plugin** | Flesch-Kincaid-Lesbarkeits-Score anzeigen |
| **Lesbarkeits-Root** | CSS-Selektor für den Lesbarkeits-Bereich |
| **Lesbarkeits-Ignore** | CSS-Selektoren aus der Lesbarkeits-Prüfung ausschließen (z. B. `.sidebar, .footer`) |
| **Ergebnisse exportieren** | Befunde als CSV exportieren (standardmäßig aktiv) |
| **Lang-of-Parts-Plugin** | Experimentelle KI-Spracherkennung pro Element (nur Chrome) |
| **Lang-of-Parts-Cache** | Erkennungsergebnisse zwischenspeichern |

### 4. Ausschlüsse je Prüfungsbereich

| Einstellung | Sa11y-Prop | Beschreibung |
|---|---|---|
| **Ohne Kontrastprüfung** | `contrastIgnore` | ersetzt den Standard `.sr-only`, also bei Bedarf mit angeben |
| **Ohne Linkprüfung** | `linkIgnore` | z. B. `.breadcrumb a` |
| **Ohne Bildprüfung** | `imageIgnore` | z. B. `.logo, .icon` |
| **Ohne Überschriftenprüfung** | `headerIgnore` | Überschriften weder prüfen noch in der Gliederung zeigen |
| **Nicht in der Gliederung** | `outlineIgnore` | prüfen, aber nicht in der Gliederung zeigen |
| **Absätze ohne „Schein-Überschrift“-Prüfung** | `paragraphIgnore` | Standard `table p` |
| **Verdeckte Bereiche** | `ignoreHiddenOverflow` | Container mit `overflow: hidden`, die Markierungen abschneiden |
| **Erste Überschriftenebene** | `initialHeadingLevel` | z. B. H2, wenn die H1 im Template außerhalb des Prüfbereichs steht |

Leere Felder lassen den Sa11y-Standard unverändert.

### 4b. Prüfungen

| Einstellung | Beschreibung |
|---|---|
| **Prüfungen abschalten** | Mehrfachauswahl aller Sa11y-Prüfungen, nach Bereichen gruppiert. Ein `checks: {…}`-Block in den individuellen Einstellungen wird ergänzt und hat Vorrang. |
| **Verbotene Link-Ziele** | CSS-Selektoren für Links, die nicht vorkommen dürfen, z. B. `a[href*="staging."]` (Sa11y-Prüfung `QA_BAD_LINK`) |

### 5. Individuelle Einstellungen

Freie JS-Optionen, die direkt an den Sa11y-Initializer übergeben werden.
Nützlich für Props, die nicht über das Formular abgedeckt sind.

**Beispiele im Accordion auf der Konfigurationsseite:**

```js
// Eigener Hilfetext im About-Panel
aboutContent: '<h2>Hilfe</h2><p>Fragen? Wenden Sie sich an die REDAXO-Community.</p>',

// Bestimmte Elemente pro Prüfung ignorieren
ignoreByTest: { allCaps: '.hero-title', linkLabel: 'nav a' },

// Sa11y nicht starten wenn ein Element vorhanden ist
doNotRun: '.no-sa11y',

// Alt-Text-Platzhalter-Erkennung anpassen
altPlaceholder: ['placeholder', 'bild', 'image'],

// Erwartete erste Überschriften-Ebene festlegen
initialHeadingLevel: 2,

// Shadow DOM / Web Components
shadowComponents: 'my-card, my-hero',

// Fixierte/klebende Elemente
fixedRoots: '.sticky-header, #cookie-bar',
```

### 6. Erweiterte Einstellungen

| Einstellung | Beschreibung |
|---|---|
| **doNotRun** | CSS-Selektor – Sa11y startet nicht, wenn dieses Element auf der Seite vorhanden ist |
| **Developer-Plugin** | Entwickler-Prüfungen anzeigen (CSS/JS-Probleme) |
| **Developer-Prüfungen standardmäßig aktiv** | Developer-Checks beim Start einschalten |
| **Shadow DOM automatisch erkennen** | Web-Component-Shadow-Roots automatisch finden |

---

## Link-Checker (opt-in)

Das AddOn enthält einen optionalen **Live-Link-Checker**. Er prüft die Links der angezeigten Seite und meldet defekte Links direkt im Sa11y-Panel.

### Funktionsweise

Nach der Aktivierung läuft der Link-Checker im Hintergrund und nutzt die `customChecks: "listen"`-API von Sa11y. Sa11y zeigt seine Ergebnisse sofort, defekte Links kommen nach den Prüfungen dazu. Jeder defekte Link erhält einen roten Fehler-Button mit Erklärung:

- **HTTP 404 / 410** – Seite nicht gefunden
- **HTTP 5xx** (außer 503) – Serverfehler
- **Domain nicht gefunden** – die Adresse existiert nicht (DNS)

Nicht gemeldet werden 401, 403, 429, 503 und Zeitüberschreitungen. Das sind meist Bot-Schutz oder kurze Störungen, keine defekten Links.

### Konfigurationsoptionen

| Einstellung | Beschreibung |
|---|---|
| **Link-Checker aktivieren** | Feature ein-/ausschalten |
| **Externe Links prüfen** | Links zu anderen Domains über den Server prüfen (echter HTTP-Status) |
| **Ignorier-Selektoren** | Kommagetrennte CSS-Selektoren, die vom Check ausgeschlossen werden (z. B. `nav a, .footer a`) |

### Technische Details

- **Interne Links** prüft der Browser (HEAD, GET-Fallback bei 405).
- **Externe Links** prüft der Server über `index.php?rex-api-call=for_sa11y_link_check`. Der Browser kann den Status fremder Seiten wegen CORS nicht lesen. Gemischte Inhalte (`http://` auf `https://`-Seiten), Tracking-Schutz und Inhaltsblocker ließen erreichbare Links bisher defekt aussehen.
- **Sicherheit des Endpunkts:**
  - Er ist nur für angemeldete Backend-Nutzer mit Sa11y-Recht nutzbar.
  - Er erlaubt nur `http`/`https`.
  - Ziele in privaten und reservierten Netzen werden abgelehnt, auch nach Weiterleitungen, die einzeln geprüft werden.
- **Cache:** Der Server speichert Ergebnisse 1 Stunde (`var/cache/addons/for_sa11y/links/`), der Browser 5 Minuten im `sessionStorage`.
- **Ein Request pro Ziel:** Links auf dieselbe Seite mit unterschiedlichen Sprungmarken (`#…`) werden einmal geprüft.
- **Parallelität:** interne und externe Links laufen in getrennten Warteschlangen mit je 6 Requests.

---

## Upgrade-Hinweise (5.0)

### Breaking Changes von Sa11y 4.x → 5.0

- **`panelPosition`-Werte geändert** – `bottom-right` → `right`, `bottom-left` → `left`.  
  Gespeicherte Konfigurationswerte werden automatisch durch `update.php` migriert.
- **`linksToFlag` entfernt** – Diese Prop wurde von Sa11y 5.0 entfernt. Das zugehörige Konfigurationsfeld wurde aus dem AddOn entfernt.

### Neu in Version 5.0

- XSS-Härtung und Performance-Verbesserungen in der Sa11y-Kernbibliothek
- ~10 % kleineres Bundle (Migration von Tippy.js auf Floating UI)
- Neue Tamil-Übersetzung (`ta`)
- Neuer `contrastAlgorithm`-Prop (AA / AAA / APCA)
- Neues `langOfPartsPlugin` für KI-gestützte Spracherkennung pro Element
- Neues `autoDetectShadowComponents` für einfachere Web-Component-Unterstützung
- Erweiterte Sprachunterstützung in `detectLanguage()`: Französisch, Niederländisch, Polnisch, Portugiesisch, Ukrainisch, US-Englisch

---

## Für Entwickler: Vendor-Aktualisierung

Eine GitHub-Action überwacht neue Sa11y-Releases automatisch und aktualisiert die Dateien in `assets/vendor/sa11y/dist/`.
Manuelle Vendor-Updates sind nicht erforderlich.

Alle verfügbaren Sa11y-Props sind dokumentiert unter:  
[https://sa11y.netlify.app/developers/props/](https://sa11y.netlify.app/developers/props/)

---

## AddOn-Lead

[Thomas Skerbis](https://github.com/skerbis)

## Credits

- [Adam Chaboryk](https://github.com/adamchaboryk) – Entwickler von Sa11y
- [Digital Media Projects team at TMU](https://github.com/ryersondmp)
- Deutsche Übersetzung: @elricco @ansichtsache

## Lizenz (Vendor)

Sa11y ist unter der GNU General Public License lizenziert.  
[https://github.com/ryersondmp/sa11y/blob/master/LICENSE.md](https://github.com/ryersondmp/sa11y/blob/master/LICENSE.md)
