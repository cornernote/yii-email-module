<?php
/**
 * @var $this EmailTemplateController
 * @var $emailTemplate EmailTemplate
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */

/** @var EmailActiveForm $form */
$form = $this->beginWidget('email.widgets.EmailActiveForm', array(
    'method' => 'get',
    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
    'htmlOptions' => array('style' => 'display:none;'),
));
$form->searchToggle('emailTemplate-grid-search', 'emailTemplate-grid');

echo $form->textFieldControlGroup($emailTemplate, 'id');
echo $form->textFieldControlGroup($emailTemplate, 'name');
echo $form->textFieldControlGroup($emailTemplate, 'subject');
echo $form->textFieldControlGroup($emailTemplate, 'heading');
echo $form->textFieldControlGroup($emailTemplate, 'message');

echo $form->getSubmitButtonRow(Yii::t('email', 'Search'));

$this->endWidget();