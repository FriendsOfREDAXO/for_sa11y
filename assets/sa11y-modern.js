/* Sa11y – Erscheinungsbild „Modern“: ergänzt die Stile in den Shadow-DOM-Bereichen von Sa11y (dort kommt die CSS-Datei nicht hin).
 * Entfernt alle Farbverläufe und macht die Listen im Panel höher (bis zu 80 % der Fensterhöhe). */
(function () {
    var CSS = [
        '.scrollable:before{background:none!important}',
        '#toggle,#toggle.on,#toggle:hover,#toggle:focus{background-image:none!important;animation:none!important}',
        '#contrast-preview{background-image:none!important}',
        '#panel-colour-filters[data-colour]{border-image:none!important}',
        '#panel-colour-filters .panel-icon{background-image:none!important}',
        '#images-content,#outline-content,#page-issues-content,#settings-content,#panel-alert-content{max-height:calc(80vh - 150px)!important}'
    ].join('');
    var SELECTOR = 'sa11y-control-panel,sa11y-annotation,sa11y-tooltips,sa11y-panel-tooltips,sa11y-heading-label,sa11y-heading-anchor,sa11y-console-error';
    function patch(el) {
        var root = el.shadowRoot;
        if (!root || root.querySelector('style[data-sa11y-modern]')) { return; }
        var s = document.createElement('style');
        s.setAttribute('data-sa11y-modern', '');
        s.textContent = CSS;
        root.appendChild(s);
    }
    function scan() { document.querySelectorAll(SELECTOR).forEach(patch); }
    new MutationObserver(scan).observe(document.documentElement, { childList: true, subtree: true });
    document.addEventListener('sa11y-check-complete', scan);
    scan();
})();
