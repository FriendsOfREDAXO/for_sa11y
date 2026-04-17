# FOR Sa11y - REDAXO AddOn

FOR Sa11y fügt ["Sa11y"](https://sa11y.netlify.app) zum REDAXO-Frontend hinzu, sobald ein Backend-Benutzer eingeloggt ist.

Sa11y hebt Probleme mit der Barrierefreiheit direkt an der Quelle visuell hervor und zeigt Tooltips mit Lösungsvorschlägen an.

Das AddOn dient als redaktionelle Hilfestellung, um Barrierefreiheitsprobleme auf der Website zu reduzieren.

![Sa11y, the accessibility quality assurance tool.](https://ryersondmp.github.io/sa11y/assets/github-banner.png)

> **Version 5.0** – Sa11y-Bibliothek auf Version 5.0 aktualisiert. Siehe [Upgrade-Hinweise](#upgrade-hinweise-50) weiter unten.

---

## Installation

1. AddOn über den REDAXO-Installer installieren oder manuell hochladen.
2. Folgenden Snippet ins Template einfügen, am Ende oder Anfang von `<body>`:

```php
<?= \FriendsOfRedaxo\Sa11y\Sa11y::get() ?>
```

Sa11y erscheint automatisch für jeden eingeloggten Backend-Benutzer.

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

### 4. Links

Benutzerdefinierte Link-Prüfungen als JSON-Array konfigurieren. Jedes Objekt kann
`pattern` (Regex-String), `exclusive` (bool), `weight` (int) und `css` (Klassenname) enthalten.

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
