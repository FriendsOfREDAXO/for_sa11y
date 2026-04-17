<?php
$package = rex_addon::get('for_sa11y');
$isAdmin = rex::getUser()?->isAdmin();

// ==============================
// AddOn Versionscheck (1x täglich)
// ==============================
$versionCheckTime = (int) $package->getConfig('_version_check_time', 0);
if (time() - $versionCheckTime > 86400) {
    try {
        $socket = rex_socket::factoryUrl('https://api.github.com/repos/FriendsOfREDAXO/for_sa11y/releases/latest');
        $socket->addHeader('User-Agent', 'REDAXO for_sa11y/' . $package->getVersion());
        $socket->setTimeout(5);
        $response = $socket->doGet();
        if ($response->isOk()) {
            $data = json_decode($response->getBody(), true);
            if (is_array($data) && isset($data['tag_name'])) {
                $tag = ltrim((string) $data['tag_name'], 'v');
                if (preg_match('/^\d[\d.]*$/', $tag)) {
                    $package->setConfig('_latest_addon_version', $tag);
                }
            }
        }
    } catch (\Exception $e) {
        // Netzwerkfehler ignorieren
    }
    $package->setConfig('_version_check_time', time());
}

$currentVersion = $package->getVersion();
$latestVersion  = (string) $package->getConfig('_latest_addon_version', '');
if ($latestVersion !== '' && version_compare($latestVersion, $currentVersion, '>')) {
    echo rex_view::warning(
        $package->i18n('for_sa11y_new_version_available', $latestVersion, $currentVersion)
        . ' <a href="https://github.com/FriendsOfREDAXO/for_sa11y/releases/tag/' . rex_escape($latestVersion) . '" target="_blank" rel="noopener noreferrer">'
        . $package->i18n('for_sa11y_new_version_release_notes')
        . '</a>'
    );
} else {
    // Zeigt auch "aktuell" wenn GitHub-Check noch nicht lief (kein Internet/noch kein Release)
    echo rex_view::success($package->i18n('for_sa11y_version_up_to_date', $currentVersion));
}

// ==============================
// Sektion 1: Grundeinstellungen
// ==============================
$form = rex_config_form::factory('for_sa11y', 'basic');

$field = $form->addSelectField('active');
$field->setLabel($package->i18n('for_sa11y_active_label'));
$select = $field->getSelect();
$select->setSize(1);
$select->addOption($package->i18n('for_sa11y_active_true'), 'true');
$select->addOption($package->i18n('for_sa11y_active_false'), 'false');

if ($isAdmin) {
    $field = $form->addInputField('text', 'root', null, ['class' => 'form-control']);
    $field->setLabel($package->i18n('for_sa11y_root'));
    $field->getValidator()->add('notEmpty', $package->i18n('root_empty'));

    $field = $form->addInputField('text', 'ignore', null, ['class' => 'form-control']);
    $field->setLabel($package->i18n('for_sa11y_ignore'));
}

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $package->i18n('for_sa11y_section_basic'), false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');

if ($isAdmin) {
    // ==============================
    // Sektion 2: Anzeige & Verhalten
    // ==============================
    $form = rex_config_form::factory('for_sa11y', 'display');

    $field = $form->addSelectField('panel_position');
    $field->setLabel($package->i18n('for_sa11y_panel_position'));
    $select = $field->getSelect();
    $select->setSize(1);
    $select->addOption($package->i18n('for_sa11y_panel_position_right'), 'right');
    $select->addOption($package->i18n('for_sa11y_panel_position_left'), 'left');
    $select->addOption($package->i18n('for_sa11y_panel_position_top_right'), 'top-right');
    $select->addOption($package->i18n('for_sa11y_panel_position_top_left'), 'top-left');

    $field = $form->addInputField('text', 'delay_check', null, ['class' => 'form-control', 'type' => 'number', 'min' => '0', 'step' => '100']);
    $field->setLabel($package->i18n('for_sa11y_delay_check'));
    $field->setNotice($package->i18n('for_sa11y_delay_check_notice'));

    $field = $form->addSelectField('show_good_image_button');
    $field->setLabel(rex_i18n::rawMsg('for_sa11y_show_good_image_button'));
    $select = $field->getSelect(); $select->setSize(1);
    $select->addOption($package->i18n('for_sa11y_active_true'), 1);
    $select->addOption($package->i18n('for_sa11y_active_false'), 0);

    $field = $form->addSelectField('show_good_link_button');
    $field->setLabel(rex_i18n::rawMsg('for_sa11y_show_good_link_button'));
    $select = $field->getSelect(); $select->setSize(1);
    $select->addOption($package->i18n('for_sa11y_active_true'), 1);
    $select->addOption($package->i18n('for_sa11y_active_false'), 0);

    $field = $form->addSelectField('detect_spa_routing');
    $field->setLabel($package->i18n('for_sa11y_detect_spa_routing'));
    $select = $field->getSelect(); $select->setSize(1);
    $select->addOption($package->i18n('for_sa11y_active_true'), 1);
    $select->addOption($package->i18n('for_sa11y_active_false'), 0);
    $field->setNotice($package->i18n('for_sa11y_detect_spa_routing_notice'));

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', $package->i18n('for_sa11y_section_display'), false);
    $fragment->setVar('body', $form->get(), false);
    echo $fragment->parse('core/page/section.php');

    // ==============================
    // Sektion 3: Plugins
    // ==============================
    $form = rex_config_form::factory('for_sa11y', 'plugins');

    $field = $form->addSelectField('contrast_plugin');
    $field->setLabel($package->i18n('for_sa11y_contrast_plugin'));
    $select = $field->getSelect(); $select->setSize(1);
    $select->addOption($package->i18n('for_sa11y_active_true'), 1);
    $select->addOption($package->i18n('for_sa11y_active_false'), 0);
    $field->setNotice($package->i18n('for_sa11y_contrast_plugin_notice'));

    $field = $form->addSelectField('contrast_algorithm');
    $field->setLabel($package->i18n('for_sa11y_contrast_algorithm'));
    $select = $field->getSelect();
    $select->setSize(1);
    $select->addOption($package->i18n('for_sa11y_contrast_algorithm_aa'), 'AA');
    $select->addOption($package->i18n('for_sa11y_contrast_algorithm_aaa'), 'AAA');
    $select->addOption($package->i18n('for_sa11y_contrast_algorithm_apca'), 'APCA');
    $field->setNotice($package->i18n('for_sa11y_contrast_algorithm_notice'));

    $field = $form->addSelectField('form_labels_plugin');
    $field->setLabel($package->i18n('for_sa11y_form_labels_plugin'));
    $select = $field->getSelect(); $select->setSize(1);
    $select->addOption($package->i18n('for_sa11y_active_true'), 1);
    $select->addOption($package->i18n('for_sa11y_active_false'), 0);
    $field->setNotice($package->i18n('for_sa11y_form_labels_plugin_notice'));

    $field = $form->addSelectField('readability_plugin');
    $field->setLabel($package->i18n('for_sa11y_readability_plugin'));
    $select = $field->getSelect(); $select->setSize(1);
    $select->addOption($package->i18n('for_sa11y_active_true'), 1);
    $select->addOption($package->i18n('for_sa11y_active_false'), 0);
    $field->setNotice($package->i18n('for_sa11y_readability_plugin_notice'));

    $field = $form->addInputField('text', 'readability_root', null, ['class' => 'form-control']);
    $field->setLabel($package->i18n('for_sa11y_readability_root'));
    $field->setNotice($package->i18n('for_sa11y_readability_root_notice'));

    $field = $form->addInputField('text', 'readability_ignore', null, ['class' => 'form-control']);
    $field->setLabel($package->i18n('for_sa11y_readability_ignore'));
    $field->setNotice($package->i18n('for_sa11y_readability_ignore_notice'));

    $field = $form->addSelectField('export_results_plugin');
    $field->setLabel($package->i18n('for_sa11y_export_results_plugin'));
    $select = $field->getSelect(); $select->setSize(1);
    $select->addOption($package->i18n('for_sa11y_active_true'), 1);
    $select->addOption($package->i18n('for_sa11y_active_false'), 0);
    $field->setNotice($package->i18n('for_sa11y_export_results_plugin_notice'));

    $field = $form->addSelectField('lang_of_parts_plugin');
    $field->setLabel($package->i18n('for_sa11y_lang_of_parts_plugin'));
    $select = $field->getSelect(); $select->setSize(1);
    $select->addOption($package->i18n('for_sa11y_active_true'), 1);
    $select->addOption($package->i18n('for_sa11y_active_false'), 0);
    $field->setNotice($package->i18n('for_sa11y_lang_of_parts_plugin_notice'));

    $field = $form->addSelectField('lang_of_parts_cache');
    $field->setLabel($package->i18n('for_sa11y_lang_of_parts_cache'));
    $select = $field->getSelect(); $select->setSize(1);
    $select->addOption($package->i18n('for_sa11y_active_true'), 1);
    $select->addOption($package->i18n('for_sa11y_active_false'), 0);
    $field->setNotice($package->i18n('for_sa11y_lang_of_parts_cache_notice'));

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', $package->i18n('for_sa11y_section_plugins'), false);
    $fragment->setVar('body', $form->get(), false);
    echo $fragment->parse('core/page/section.php');

    // ==============================
    // Sektion 4: Links & Inhalte
    // ==============================
    $form = rex_config_form::factory('for_sa11y', 'links');

    $field = $form->addTextAreaField('about_content', null, ['class' => 'form-control', 'rows' => 3]);
    $field->setLabel($package->i18n('for_sa11y_about_content'));
    $field->setNotice($package->i18n('for_sa11y_about_content_notice'));

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', $package->i18n('for_sa11y_section_advanced'), false);
    $fragment->setVar('body', $form->get(), false);
    echo $fragment->parse('core/page/section.php');

    // ==============================
    // Sektion 4b: Erweiterte Einstellungen
    // ==============================
    $form = rex_config_form::factory('for_sa11y', 'advanced');

    $field = $form->addInputField('text', 'do_not_run', null, ['class' => 'form-control']);
    $field->setLabel($package->i18n('for_sa11y_do_not_run'));
    $field->setNotice($package->i18n('for_sa11y_do_not_run_notice'));

    $field = $form->addSelectField('developer_plugin');
    $field->setLabel($package->i18n('for_sa11y_developer_plugin'));
    $select = $field->getSelect(); $select->setSize(1);
    $select->addOption($package->i18n('for_sa11y_active_true'), 1);
    $select->addOption($package->i18n('for_sa11y_active_false'), 0);
    $field->setNotice($package->i18n('for_sa11y_developer_plugin_notice'));

    $field = $form->addSelectField('developer_checks_on_by_default');
    $field->setLabel($package->i18n('for_sa11y_developer_checks_on_by_default'));
    $select = $field->getSelect(); $select->setSize(1);
    $select->addOption($package->i18n('for_sa11y_active_true'), 1);
    $select->addOption($package->i18n('for_sa11y_active_false'), 0);
    $field->setNotice($package->i18n('for_sa11y_developer_checks_on_by_default_notice'));

    $field = $form->addSelectField('auto_detect_shadow_components');
    $field->setLabel($package->i18n('for_sa11y_auto_detect_shadow_components'));
    $select = $field->getSelect(); $select->setSize(1);
    $select->addOption($package->i18n('for_sa11y_active_true'), 1);
    $select->addOption($package->i18n('for_sa11y_active_false'), 0);
    $field->setNotice($package->i18n('for_sa11y_auto_detect_shadow_components_notice'));

    $field = $form->addInputField('text', 'exclude_url_params', null, ['class' => 'form-control']);
    $field->setLabel($package->i18n('for_sa11y_exclude_url_params'));
    $field->setNotice($package->i18n('for_sa11y_exclude_url_params_notice'));

    $field = $form->addSelectField('link_checker');
    $field->setLabel($package->i18n('for_sa11y_link_checker'));
    $select = $field->getSelect(); $select->setSize(1);
    $select->addOption($package->i18n('for_sa11y_active_true'), 1);
    $select->addOption($package->i18n('for_sa11y_active_false'), 0);
    $field->setNotice($package->i18n('for_sa11y_link_checker_notice'));

    $field = $form->addSelectField('link_checker_check_external');
    $field->setLabel($package->i18n('for_sa11y_link_checker_check_external'));
    $select = $field->getSelect(); $select->setSize(1);
    $select->addOption($package->i18n('for_sa11y_active_true'), 1);
    $select->addOption($package->i18n('for_sa11y_active_false'), 0);
    $field->setNotice($package->i18n('for_sa11y_link_checker_check_external_notice'));

    $field = $form->addInputField('text', 'link_checker_ignore', null, ['class' => 'form-control']);
    $field->setLabel($package->i18n('for_sa11y_link_checker_ignore'));
    $field->setNotice($package->i18n('for_sa11y_link_checker_ignore_notice'));

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', $package->i18n('for_sa11y_section_extended'), false);
    $fragment->setVar('body', $form->get(), false);
    echo $fragment->parse('core/page/section.php');

    // ==============================
    // Sektion 5: Erweiterte JS-Einstellungen (Custom Settings)
    // ==============================
    $form = rex_config_form::factory('for_sa11y', 'custom');

    $field = $form->addTextAreaField('custom_settings', null, ['class' => 'form-control rex-code', 'rows' => 6]);
    $field->setLabel($package->i18n('for_sa11y_custom_settings'));
    $field->setNotice($package->i18n('for_sa11y_custom_settings_notice'));

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', $package->i18n('for_sa11y_custom_settings'), false);
    $fragment->setVar('body', $form->get(), false);
    echo $fragment->parse('core/page/section.php');

    // ==============================
    // Beispiel-Accordion (eingeklappt)
    // ==============================
    $accordion = '<div class="panel-group" id="sa11y-examples-accordion">';

    $accordion .= '
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-usage-tips">
                        <i class="rex-icon fa-info-circle"></i> ' . $package->i18n('custom_settings_usage_tips') . '
                    </a>
                </h4>
            </div>
            <div id="example-usage-tips" class="panel-collapse collapse">
                <div class="panel-body">
                    <h5><i class="rex-icon fa-check"></i> ' . $package->i18n('custom_settings_tip_1_title') . '</h5>
                    <p>' . $package->i18n('custom_settings_tip_1_desc') . '</p>
                    <pre><code>panelPosition: "top-right",
delayCheck: 1000,</code></pre>

                    <h5><i class="rex-icon fa-check"></i> ' . $package->i18n('custom_settings_tip_2_title') . '</h5>
                    <p>' . $package->i18n('custom_settings_tip_2_desc') . '</p>
                    <pre><code>// Richtig:
panelPosition: "top-right",

// Falsch - fehlendes Komma:
panelPosition: "top-right"
delayCheck: 1000,</code></pre>

                    <h5><i class="rex-icon fa-check"></i> ' . $package->i18n('custom_settings_tip_3_title') . '</h5>
                    <p>' . $package->i18n('custom_settings_tip_3_desc') . '</p>
                    <pre><code>aboutContent: \'&lt;h2&gt;Titel&lt;/h2&gt;\',</code></pre>

                    <h5><i class="rex-icon fa-check"></i> ' . $package->i18n('custom_settings_tip_4_title') . '</h5>
                    <p>' . $package->i18n('custom_settings_tip_4_desc') . '</p>

                    <h5><i class="rex-icon fa-check"></i> ' . $package->i18n('custom_settings_tip_5_title') . '</h5>
                    <p>' . $package->i18n('custom_settings_tip_5_desc') . '</p>
                </div>
            </div>
        </div>';

    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-ignore-by-test">
                        <i class="rex-icon fa-code"></i> ' . $package->i18n('custom_settings_example_ignore_by_test') . '
                    </a>
                </h4>
            </div>
            <div id="example-ignore-by-test" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_ignore_by_test_desc') . '</p>
                    <pre><code>ignoreByTest: {
  QA_FAKE_HEADING: \'p.ignore strong\',
  IMAGE_ALT_TOO_LONG: \'.hero-image\',
},</code></pre>
                </div>
            </div>
        </div>';

    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-custom-checks">
                        <i class="rex-icon fa-code"></i> ' . $package->i18n('custom_settings_example_custom_checks') . '
                    </a>
                </h4>
            </div>
            <div id="example-custom-checks" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_custom_checks_desc') . '</p>
                    <pre><code>customChecks: {
  myCustomCheck: {
    check: (el) => {
      const text = el.textContent.toLowerCase();
      return text.includes(\'hier klicken\') || text.includes(\'click here\');
    },
    selector: \'a\',
    severity: \'warning\',
    message: \'Vermeiden Sie unspezifische Link-Texte.\',
    title: \'Unspezifischer Link-Text\',
  }
},</code></pre>
                    <p><small>' . $package->i18n('custom_settings_custom_checks_note') . '</small></p>
                </div>
            </div>
        </div>';

    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-checks-config">
                        <i class="rex-icon fa-code"></i> ' . $package->i18n('custom_settings_example_checks_config') . '
                    </a>
                </h4>
            </div>
            <div id="example-checks-config" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_checks_config_desc') . '</p>
                    <pre><code>checks: {
  HEADING_MISSING_ONE: false,
  IMAGE_ALT_TOO_LONG: {
    maxLength: 150,
  },
  TABLES_EMPTY_HEADING: {
    content: \'Bitte vermeiden Sie leere Tabellenüberschriften!\',
    type: \'warning\',
    dismissAll: true,
  }
},</code></pre>
                </div>
            </div>
        </div>';

    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-ignore-selectors">
                        <i class="rex-icon fa-filter"></i> ' . $package->i18n('custom_settings_example_ignore_selectors') . '
                    </a>
                </h4>
            </div>
            <div id="example-ignore-selectors" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_ignore_selectors_desc') . '</p>
                    <pre><code>// Überschriften ausschließen (z.B. Bootstrap-Akkordeons mit aria-expanded)
headerIgnore: ".accordion-header, .widget-title",

// Bilder ausschließen
imageIgnore: ".icon, .logo, [aria-hidden=\'true\']",

// Links ausschließen
linkIgnore: ".sa11y-ignore, .breadcrumb a",

// Kontrast-Ausnahmen (z.B. deaktivierte Buttons, Platzhaltertext)
contrastIgnore: ".btn-disabled, ::placeholder",

// Absätze bei Fake-Überschriften-Check ignorieren
paragraphIgnore: "table p, .meta-info p",</code></pre>
                </div>
            </div>
        </div>';

    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-alt-placeholder">
                        <i class="rex-icon fa-image"></i> ' . $package->i18n('custom_settings_example_alt_placeholder') . '
                    </a>
                </h4>
            </div>
            <div id="example-alt-placeholder" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_alt_placeholder_desc') . '</p>
                    <pre><code>// CMS-typische Platzhalter im Alt-Text erkennen und als Fehler markieren
altPlaceholder: [
  "bild",
  "foto",
  "image",
  "img",
  "photo",
  "picture",
  "DSC",
  "_DSC",
  "thumbnail",
  "screenshot",
],</code></pre>
                    <p><small>' . $package->i18n('custom_settings_example_alt_placeholder_note') . '</small></p>
                </div>
            </div>
        </div>';

    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-heading-level">
                        <i class="rex-icon fa-header"></i> ' . $package->i18n('custom_settings_example_heading_level') . '
                    </a>
                </h4>
            </div>
            <div id="example-heading-level" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_heading_level_desc') . '</p>
                    <pre><code>// Wenn der Seiteninhalt mit H2 beginnt (H1 ist im Template)
initialHeadingLevel: [2],

// Wenn mehrere Startebenen erlaubt sind
initialHeadingLevel: [1, 2],</code></pre>
                    <p><small>' . $package->i18n('custom_settings_example_heading_level_note') . '</small></p>
                </div>
            </div>
        </div>';

    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-shadow-dom">
                        <i class="rex-icon fa-puzzle-piece"></i> ' . $package->i18n('custom_settings_example_shadow_dom') . '
                    </a>
                </h4>
            </div>
            <div id="example-shadow-dom" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_shadow_dom_desc') . '</p>
                    <pre><code>// Automatische Erkennung (empfohlen, leicht langsamer)
autoDetectShadowComponents: true,

// Oder gezielt bestimmte Komponenten angeben (schneller)
shadowComponents: "my-card, rex-media-gallery, custom-nav",</code></pre>
                </div>
            </div>
        </div>';

    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-fixed-nav">
                        <i class="rex-icon fa-arrows-v"></i> ' . $package->i18n('custom_settings_example_fixed_nav') . '
                    </a>
                </h4>
            </div>
            <div id="example-fixed-nav" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_fixed_nav_desc') . '</p>
                    <pre><code>// Sticky Header / fixierte Navigation angeben – Tooltips werden
// unterhalb angezeigt, um nicht verdeckt zu werden
fixedRoots: "header.sticky, #fixed-nav, .topbar",</code></pre>
                </div>
            </div>
        </div>';

    $accordion .= '</div>';

    $docsLink = '<p class="help-block">
        <i class="rex-icon fa-external-link"></i>
        <a href="https://sa11y.netlify.app/developers/props/" target="_blank" rel="noopener noreferrer">
            ' . $package->i18n('custom_settings_docs_link') . '
        </a>
    </p>';

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'info', false);
    $fragment->setVar('title', $package->i18n('custom_settings_examples_title'), false);
    $fragment->setVar('body', $docsLink . $accordion, false);
    echo $fragment->parse('core/page/section.php');
}
