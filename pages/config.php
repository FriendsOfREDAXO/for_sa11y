<?php
$package = rex_addon::get('for_sa11y');
$form = rex_config_form::factory('for_sa11y');
$field = $form->addSelectField('active');
$field->setLabel($package->i18n('for_sa11y_active_label'));
$select = $field->getSelect();
$select->setSize(1);
$select->addOption($package->i18n('for_sa11y_active_true'), 'true');
$select->addOption($package->i18n('for_sa11y_active_false'), 'false');

if(rex::getUser()?->isAdmin())
{  
    $field = $form->addInputField('text', 'root', null, ['class' => 'form-control']);
    $field->setLabel($package->i18n('root'));
    $field->getValidator()->add('notEmpty', $package->i18n('root_empty'));
    $field = $form->addInputField('text', 'ignore', null, ['class' => 'form-control']);
    $field->setLabel($package->i18n('ignore'));
    $field = $form->addTextAreaField('custom_settings', null, ['class' => 'form-control']);
    $field->setLabel($package->i18n('custom_settings'));
}
$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $package->i18n('for_sa11y_config'), false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');

// Accordion mit Custom Settings Beispielen
if(rex::getUser()?->isAdmin()) {
    $accordion = '<div class="panel-group" id="sa11y-examples-accordion">';
    
    // Hinweise zur Verwendung
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
    
    // Beispiel 1: Panel Position
    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-panel-position">
                        <i class="rex-icon fa-code"></i> ' . $package->i18n('custom_settings_example_panel') . '
                    </a>
                </h4>
            </div>
            <div id="example-panel-position" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_panel_desc') . '</p>
                    <pre><code>panelPosition: "top-right",</code></pre>
                    <p><small>' . $package->i18n('custom_settings_options') . ': <code>top-left</code>, <code>top-right</code>, <code>bottom-left</code>, <code>bottom-right</code></small></p>
                </div>
            </div>
        </div>';
    
    // Beispiel 2: Verzögerung
    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-delay">
                        <i class="rex-icon fa-code"></i> ' . $package->i18n('custom_settings_example_delay') . '
                    </a>
                </h4>
            </div>
            <div id="example-delay" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_delay_desc') . '</p>
                    <pre><code>delayCheck: 1000,</code></pre>
                    <p><small>' . $package->i18n('custom_settings_delay_note') . '</small></p>
                </div>
            </div>
        </div>';
    
    // Beispiel 3: Custom Content
    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-about">
                        <i class="rex-icon fa-code"></i> ' . $package->i18n('custom_settings_example_about') . '
                    </a>
                </h4>
            </div>
            <div id="example-about" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_about_desc') . '</p>
                    <pre><code>aboutContent: \'&lt;h2&gt;Hilfe&lt;/h2&gt;&lt;p&gt;Bei Fragen wenden Sie sich an die REDAXO Community&lt;/p&gt;\',</code></pre>
                </div>
            </div>
        </div>';
    
    // Beispiel 4: Buttons ausblenden
    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-buttons">
                        <i class="rex-icon fa-code"></i> ' . $package->i18n('custom_settings_example_buttons') . '
                    </a>
                </h4>
            </div>
            <div id="example-buttons" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_buttons_desc') . '</p>
                    <pre><code>showGoodImageButton: false,
showGoodLinkButton: false,</code></pre>
                </div>
            </div>
        </div>';
    
    // Beispiel 5: Kontrast-Prüfung
    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-contrast">
                        <i class="rex-icon fa-code"></i> ' . $package->i18n('custom_settings_example_contrast') . '
                    </a>
                </h4>
            </div>
            <div id="example-contrast" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_contrast_desc') . '</p>
                    <pre><code>contrastPlugin: true,</code></pre>
                </div>
            </div>
        </div>';
    
    // Beispiel 6: Formular-Labels
    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-forms">
                        <i class="rex-icon fa-code"></i> ' . $package->i18n('custom_settings_example_forms') . '
                    </a>
                </h4>
            </div>
            <div id="example-forms" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_forms_desc') . '</p>
                    <pre><code>formLabelsPlugin: true,</code></pre>
                </div>
            </div>
        </div>';
    
    // Beispiel 7: Custom Checks
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
      // Beispiel: Warnung wenn ein Link "hier klicken" enthält
      const text = el.textContent.toLowerCase();
      return text.includes(\'hier klicken\') || text.includes(\'click here\');
    },
    selector: \'a\',
    severity: \'warning\',
    message: \'Vermeiden Sie unspezifische Link-Texte wie "hier klicken".\',
    title: \'Unspezifischer Link-Text\',
  },
  checkPhoneNumbers: {
    check: (el) => {
      // Prüft ob Telefonnummern klickbar sind
      const hasPhone = /\d{3,}[\s\-]?\d{3,}/.test(el.textContent);
      const hasLink = el.querySelector(\'a[href^="tel:"]\');
      return hasPhone && !hasLink;
    },
    selector: \'p, div\',
    severity: \'error\',
    message: \'Telefonnummern sollten als klickbare Links formatiert sein.\',
    title: \'Telefonnummer nicht klickbar\',
  }
},</code></pre>
                    <p><small>' . $package->i18n('custom_settings_custom_checks_note') . '</small></p>
                </div>
            </div>
        </div>';
    
    // Beispiel 8: Readability
    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-readability">
                        <i class="rex-icon fa-code"></i> ' . $package->i18n('custom_settings_example_readability') . '
                    </a>
                </h4>
            </div>
            <div id="example-readability" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_readability_desc') . '</p>
                    <pre><code>readabilityPlugin: true,
readabilityRoot: "main",
readabilityIgnore: ".sidebar, .footer",</code></pre>
                    <p><small>' . $package->i18n('custom_settings_readability_note') . '</small></p>
                </div>
            </div>
        </div>';
    
    // Beispiel 9: SPA Routing
    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-spa">
                        <i class="rex-icon fa-code"></i> ' . $package->i18n('custom_settings_example_spa') . '
                    </a>
                </h4>
            </div>
            <div id="example-spa" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_spa_desc') . '</p>
                    <pre><code>detectSPArouting: true,</code></pre>
                </div>
            </div>
        </div>';
    
    // Beispiel 10: Links kennzeichnen
    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-links">
                        <i class="rex-icon fa-code"></i> ' . $package->i18n('custom_settings_example_links') . '
                    </a>
                </h4>
            </div>
            <div id="example-links" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_links_desc') . '</p>
                    <pre><code>linksToFlag: "a[href*=\'dev.\'], a[href*=\'staging.\'], a[href*=\'localhost\']",</code></pre>
                    <p><small>' . $package->i18n('custom_settings_links_note') . '</small></p>
                </div>
            </div>
        </div>';
    
    // Beispiel 11: APCA Kontrast
    $accordion .= '
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#sa11y-examples-accordion" href="#example-apca">
                        <i class="rex-icon fa-code"></i> ' . $package->i18n('custom_settings_example_apca') . '
                    </a>
                </h4>
            </div>
            <div id="example-apca" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>' . $package->i18n('custom_settings_example_apca_desc') . '</p>
                    <pre><code>contrastAPCA: true,</code></pre>
                    <p><small>' . $package->i18n('custom_settings_apca_note') . '</small></p>
                </div>
            </div>
        </div>';
    
    // Beispiel 12: Bestimmte Checks anpassen
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
  // "Missing H1" Check deaktivieren
  HEADING_MISSING_ONE: false,
  
  // Alt-Text Länge anpassen (Standard: 250 Zeichen)
  IMAGE_ALT_TOO_LONG: {
    maxLength: 150,
  },
  
  // Check von Error zu Warning ändern
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
    
    // Dokumentations-Link
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
