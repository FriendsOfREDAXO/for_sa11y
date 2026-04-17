# Changelog

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

### Verbesserungen
- Mehrere Formulare in der Konfiguration waren nicht voneinander getrennt (gleiches Form-Hash-Problem) – behoben durch eindeutige Fieldset-Keys
- Labels mit Anführungszeichen (`"Gute Bilder"`) wurden doppelt HTML-escaped – behoben durch `rex_i18n::rawMsg()`
- Erweiterte Sprachunterstützung in `detectLanguage()`: `fr_fr`, `nl_nl`, `pl_pl`, `pt_pt`, `uk_ua`, `en_us`
- Neue Accordion-Hilfe-Beispiele: Ignore-Selektoren, `altPlaceholder`, `initialHeadingLevel`, Shadow DOM, `fixedRoots`, `ignoreByTest`
- Duplikate in Lang-Dateien bereinigt
- Rexstan-Typ-Fix: `rex_addon_interface` statt `rex_addon` als Parameter-Typ

---

## 4.4.1

Letzte stabile Version mit Sa11y 4.x. Siehe [Sa11y 4.x Changelog](https://github.com/ryersondmp/sa11y/releases) für Details zur Bibliothek.
