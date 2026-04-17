# FOR Sa11y - REDAXO AddOn

FOR Sa11y adds ["Sa11y"](https://sa11y.netlify.app) to the REDAXO frontend whenever a backend user is logged in.

Sa11y visually highlights accessibility content issues at the source with a simple tooltip on how to fix them.

The AddOn serves as an editorial assistant to reduce accessibility issues on your website.

![Sa11y, the accessibility quality assurance tool.](https://ryersondmp.github.io/sa11y/assets/github-banner.png)

> **Version 5.0** – Sa11y library updated to 5.0. See [Upgrade Notes](#upgrade-notes-50) below.

---

## Installation

1. Install the AddOn via the REDAXO installer or upload it manually.
2. Add the following snippet to your template, near the end or beginning of `<body>`:

```php
<?= \FriendsOfRedaxo\Sa11y\Sa11y::get() ?>
```

That's it. Sa11y appears automatically for every logged-in backend user.

---

## Configuration

All settings are managed on the AddOn's config page in the REDAXO backend. They are grouped into six sections:

### 1. Basic Settings

| Setting | Description |
|---|---|
| **Active** | Enable or disable Sa11y globally |
| **Root element** | CSS selector for the content area to check (default: `body`) |
| **Ignore selectors** | CSS selectors to exclude from all checks |
| **Panel position** | `right` (default), `left`, `top-right`, `top-left` |
| **Check delay (ms)** | Delay before Sa11y runs (useful for SPAs) |
| **Detect SPA routing** | Re-run checks on client-side navigation |

### 2. Display

| Setting | Description |
|---|---|
| **Show "Good image" button** | Highlights images that have good alt text |
| **Show "Good link" button** | Highlights links with descriptive text |

### 3. Plugins

| Setting | Description |
|---|---|
| **Contrast plugin** | Enable WCAG colour contrast checking |
| **Contrast algorithm** | `AA` (default), `AAA`, or `APCA` (experimental) |
| **Form labels plugin** | Check for missing form labels |
| **Readability plugin** | Flesch-Kincaid readability score |
| **Readability root** | CSS selector for the readability target area |
| **Readability ignore** | CSS selectors to exclude from readability checks (e.g. `.sidebar, .footer`) |
| **Export results plugin** | Export findings as CSV (enabled by default) |
| **Lang of parts plugin** | Experimental AI-powered per-element language detection (Chrome only) |
| **Lang of parts cache** | Cache language detection results |

### 4. Links

Configure custom link checks via a JSON array of objects. Each object can contain
`pattern` (regex string), `exclusive` (bool), `weight` (int) and `css` (class name).

### 5. Custom Settings

Freeform JS options passed directly to the Sa11y initialiser.
Useful for any prop not covered by the form above.

**Examples available in the accordion on the config page:**

```js
// Custom about panel content
aboutContent: '<h2>Help</h2><p>Questions? Ask the REDAXO community.</p>',

// Ignore specific elements by test
ignoreByTest: { allCaps: '.hero-title', linkLabel: 'nav a' },

// Skip Sa11y entirely when a specific element is present
doNotRun: '.no-sa11y',

// Adjust alt text placeholder detection
altPlaceholder: ['placeholder', 'bild', 'image'],

// Set the expected first heading level
initialHeadingLevel: 2,

// Shadow DOM / Web Components
shadowComponents: 'my-card, my-hero',

// Sticky/fixed elements
fixedRoots: '.sticky-header, #cookie-bar',
```

### 6. Advanced Settings

| Setting | Description |
|---|---|
| **doNotRun** | CSS selector – Sa11y does not start if this element is found on the page |
| **Developer plugin** | Show developer-mode checks (CSS/JS issues) |
| **Developer checks on by default** | Start with developer checks enabled |
| **Auto-detect Shadow DOM** | Automatically discover Web Component shadow roots |

---

## Link Checker (opt-in)

The AddOn includes an optional **live link checker** that runs entirely in the browser—no server-side crawling required.

### How it works

Once enabled, the link checker runs in the background via `requestIdleCallback` shortly after page load.
Broken links are reported directly **inside the Sa11y error panel** using the same annotation system as all other checks.
Each broken link shows a red error annotation button; clicking it opens a tooltip that displays a human-readable explanation including the HTTP status code:

- **HTTP 404** – Link not found
- **HTTP 5xx** – Server error
- **Timeout** – No response within 8 seconds
- **Network error** – Server unreachable (DNS failure, connection refused)

### Configuration options

| Setting | Description |
|---|---|
| **Enable Link Checker** | Turns the feature on/off |
| **Check external links** | Also check cross-origin URLs (detects unreachable servers; cannot read HTTP status due to CORS) |
| **Ignore selectors** | Comma-separated CSS selectors to exclude from checking (e.g. `nav a, .footer a`) |

### Technical details

- **Non-blocking** – uses `requestIdleCallback` (fallback: `setTimeout` 6 s) so page interactivity is never blocked
- **HEAD request first**, GET fallback for servers that reject HEAD (HTTP 405)
- **Session cache** – results are stored in `sessionStorage` for 5 minutes; repeated navigation on a SPA skips already-checked links
- **Concurrency** – 3 parallel requests at most
- **External links** – checked via `no-cors` mode: an opaque response means the server is up; a `TypeError` means it’s unreachable (status code is not readable due to CORS)
- **Sa11y integration** – uses the native `customChecks: "listen"` API (two-pass: Sa11y renders immediately, broken links are injected after network checks complete)

---

## Upgrade Notes (5.0)

### Breaking changes from Sa11y 4.x → 5.0

- **`panelPosition` values changed** – `bottom-right` → `right`, `bottom-left` → `left`.  
  Stored config values are migrated automatically by `update.php`.
- **`linksToFlag` removed** – This prop was dropped by Sa11y 5.0. The config field has been removed from the AddOn.

### What's new in 5.0

- XSS hardening and performance improvements in the Sa11y core library
- ~10 % smaller bundle (migrated from Tippy.js to Floating UI)
- New Tamil (`ta`) translation
- New `contrastAlgorithm` prop (AA / AAA / APCA)
- New `langOfPartsPlugin` for AI-powered per-element language detection
- New `autoDetectShadowComponents` for easier Web Component support
- Expanded language support in `detectLanguage()`: French, Dutch, Polish, Portuguese, Ukrainian, US English

---

## For Developers: Vendor update

A GitHub Action automatically tracks new Sa11y releases and updates the `assets/vendor/sa11y/dist/` files.
Manual vendor updates are not required.

All available Sa11y props are documented at  
[https://sa11y.netlify.app/developers/props/](https://sa11y.netlify.app/developers/props/)

---

## AddOn Lead

[Thomas Skerbis](https://github.com/skerbis)

## Credits

- [Adam Chaboryk](https://github.com/adamchaboryk) – creator of Sa11y
- [Digital Media Projects team at TMU](https://github.com/ryersondmp)
- German translation: @elricco @ansichtsache

## License (Vendor)

Sa11y is licensed under the GNU General Public License.  
[https://github.com/ryersondmp/sa11y/blob/master/LICENSE.md](https://github.com/ryersondmp/sa11y/blob/master/LICENSE.md)
