<?php
$form = rex_config_form::factory('for_sa11y');
$field = $form->addSelectField('active');
$field->setLabel($this->i18n('for_sa11y_active_label'));
$select = $field->getSelect();
$select->setSize(1);
$select->addOption($this->i18n('for_sa11y_active_true'), true);
$select->addOption($this->i18n('for_sa11y_active_false'), false);

$field = $form->addInputField('text', 'root', null, ['class' => 'form-control']);
$field->setLabel($this->i18n('root'));
$field->getValidator()->add('notEmpty', $this->i18n('root_empty'));

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $this->i18n('for_sa11y_config'), false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');
