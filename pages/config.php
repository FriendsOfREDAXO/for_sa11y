<?php
$package = rex_addon::get('for_sa11y');
$isAdmin = rex::getUser()?->isAdmin();

// ==============================
// Sektion 1: Grundeinstellungen
// ==============================
$form = rex_config_form::factory('for_sa11y');

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
    $form = rex_config_form::factory('for_sa11y');

    $field = $form->addSelectField('panel_position');
    $field->setLabel($package->i18n('for_sa11y_panel_position'));
    $select = $field->getSelect();
    $select->setSize(1);
    $select->addOption($package->i18n('for_sa11y_panel_position_bottom_right'), 'bottom-right');
    $select->addOption($package->i18n('for_sa11y_panel_position_bottom_left'), 'bottom-left');
    $select->addOption($package->i18n('for_sa11y_panel_position_top_right'), 'top-right');
    $select->addOption($package->i18n('for_sa11y_panel_position_top_left'), 'top-left');

    $field = $form->addInputField('text', 'delay_check', null, ['class' => 'form-control', 'type' => 'number', 'min' => '0', 'step' => '100']);
    $field->setLabel($package->i18n('for_sa11y_delay_check'));
    $field->setNotice($package->i18n('for_sa11y_delay_check_notice'));

    $field = $form->addCheckboxField('show_good_image_button');
    $field->addOption($package->i18n('for_sa11y_show_good_image_button'), 1);

    $field = $form->addCheckboxField('show_good_link_button');
    $field->addOption($package->i18n('for_sa11y_show_good_link_button'), 1);

    $field = $form->addCheckboxField('detect_spa_routing');
    $field->addOption($package->i18n('for_sa11y_detect_spa_routing'), 1);
    $field->setNotice($package->i18n('for_sa11y_detect_spa_routing_notice'));

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', $package->i18n('for_sa11y_section_display'), false);
    $fragment->setVar('body', $form->get(), false);
    echo $fragment->parse('core/page/section.php');

    // ==============================
    // Sektion 3: Plugins
    // ==============================
    $form = rex_config_form::factory('for_sa11y');

    $field = $form->addCheckboxField('contrast_plugin');
    $field->addOption($package->i18n('for_sa11y_contrast_plugin'), 1);
    $field->setNotice($package->i18n('for_sa11y_contrast_plugin_notice'));

    $field = $form->addCheckboxField('form_labels_plugin');
    $field->addOption($package->i18n('for_sa11y_form_labels_plugin'), 1);
    $field->setNotice($package->i18n('for_sa11y_form_labels_plugin_notice'));

    $field = $form->addCheckboxField('readability_plugin');
    $field->addOption($package->i18n('for_sa11y_readability_plugin'), 1);
    $field->setNotice($package->i18n('for_sa11y_readability_plugin_notice'));

    $field = $form->addInputField('text', 'readability_root', null, ['class' => 'form-control']);
    $field->setLabel($package->i18n('for_sa11y_readability_root'));
    $field->setNotice($package->i18n('for_sa11y_readability_root_notice'));

    $field = $form->addCheckboxField('export_results_plugin');
    $field->addOption($package->i18n('for_sa11y_export_results_plugin'), 1);
    $field->setNotice($package->i18n('for_sa11y_export_results_plugin_notice'));

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', $package->i18n('for_sa11y_section_plugins'), false);
    $fragment->setVar('body', $form->get(), false);
    echo $fragment->parse('core/page/section.php');

    // ==============================
    // Sektion 4: Links & Inhalte
    // ==============================
    $form = rex_config_form::factory('for_sa11y');

    $field = $form->addInputField('text', 'links_to_flag', null, ['class' => 'form-control']);
    $field->setLabel($package->i18n('for_sa11y_links_to_flag'));
    $field->setNotice($package->i18n('for_sa11y_links_to_flag_notice'));

    $field = $form->addTextAreaField('about_content', null, ['class' => 'form-control', 'rows' => 3]);
    $field->setLabel($package->i18n('for_sa11y_about_content'));
    $field->setNotice($package->i18n('for_sa11y_about_content_notice'));

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', $package->i18n('for_sa11y_section_advanced'), false);
    $fragment->setVar('body', $form->get(), false);
    echo $fragment->parse('core/page/section.php');

    // ==============================
    // Sektion 5: Erweiterte JS-Einstellungen (Custom Settings)
    // ==============================
    $form = rex_config_form::factory('for_sa11y');

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
