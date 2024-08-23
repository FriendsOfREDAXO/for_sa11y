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
    $field = $form->addTextAreaField('text', null, ['class' => 'form-control']);
    $field->setLabel($package->i18n('custom_settings'));
}
$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $package->i18n('for_sa11y_config'), false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');
