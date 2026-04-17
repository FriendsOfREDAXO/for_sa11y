/* FOR Sa11y – Backend helpers */
(function () {
    'use strict';

    /* ------------------------------------------------------------------ */
    /* Live-Vorschau: Panel-Position                                        */
    /* ------------------------------------------------------------------ */
    function initPanelPositionPreview() {
        /* Find the panel-position select by its unique option values */
        var posSelect = null;
        document.querySelectorAll('select').forEach(function (s) {
            if (s.querySelector('option[value="top-right"]') && s.querySelector('option[value="right"]')) {
                posSelect = s;
            }
        });

        if (!posSelect) {
            return;
        }

        /* Build preview container */
        var preview = document.createElement('div');
        preview.className = 'sa11y-pos-preview';
        preview.style.cssText = [
            'position:relative',
            'width:140px',
            'height:90px',
            'border:2px solid rgba(128,128,128,0.3)',
            'border-radius:6px',
            'margin-top:10px',
            'background:rgba(128,128,128,0.15)',
            'overflow:hidden',
            'display:inline-block',
        ].join(';');

        /* Moving dot represents the Sa11y panel button */
        var dot = document.createElement('div');
        dot.title = 'Sa11y Panel';
        dot.style.cssText = [
            'width:24px',
            'height:24px',
            'background:#2980b9',
            'border-radius:50%',
            'position:absolute',
            'transition:top 0.2s ease,right 0.2s ease,bottom 0.2s ease,left 0.2s ease',
        ].join(';');
        preview.appendChild(dot);

        /* Position map */
        var posMap = {
            'right':     { bottom: '4px', right: '4px', top: 'auto', left: 'auto' },
            'left':      { bottom: '4px', left: '4px',  top: 'auto', right: 'auto' },
            'top-right': { top: '4px',    right: '4px', bottom: 'auto', left: 'auto' },
            'top-left':  { top: '4px',    left: '4px',  bottom: 'auto', right: 'auto' },
        };

        function updatePreview(val) {
            var pos = posMap[val] || posMap['right'];
            dot.style.top    = pos.top;
            dot.style.right  = pos.right;
            dot.style.bottom = pos.bottom;
            dot.style.left   = pos.left;
        }

        /* Insert preview after the select (inside .form-group or directly) */
        var formGroup = posSelect.closest('.form-group') || posSelect.parentNode;
        formGroup.appendChild(preview);

        updatePreview(posSelect.value);
        posSelect.addEventListener('change', function () {
            updatePreview(this.value);
        });
    }

    $(document).on('rex:ready', initPanelPositionPreview);

    /* ------------------------------------------------------------------ */
    /* Nach Formular-Speichern: zurück zur gespeicherten Sektion scrollen   */
    /* ------------------------------------------------------------------ */
    function initSectionScrollRestore() {
        // Vor dem Submit: Sektion-ID merken
        document.querySelectorAll('div[id^="for-sa11y-section-"] form').forEach(function (form) {
            form.addEventListener('submit', function () {
                var section = form.closest('div[id^="for-sa11y-section-"]');
                if (section) {
                    sessionStorage.setItem('for_sa11y_scroll_to', section.id);
                }
            });
        });

        // Nach Reload: zur gemerkten Sektion scrollen
        var target = sessionStorage.getItem('for_sa11y_scroll_to');
        if (target) {
            sessionStorage.removeItem('for_sa11y_scroll_to');
            var el = document.getElementById(target);
            if (el) {
                setTimeout(function () {
                    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 150);
            }
        }
    }

    $(document).on('rex:ready', initSectionScrollRestore);
}());
