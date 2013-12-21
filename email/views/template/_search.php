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
    'htmlOptions' => array('style' => 'display:none;'),
));
$form->searchToggle('emailTemplate-grid-search', 'emailTemplate-grid');

echo $form->textFieldRow($emailTemplate, 'id');
echo $form->textFieldRow($emailTemplate, 'name');
echo $form->textFieldRow($emailTemplate, 'message_subject');
echo $form->textFieldRow($emailTemplate, 'message_title');
echo $form->textFieldRow($emailTemplate, 'message_html');
echo $form->textFieldRow($emailTemplate, 'message_text');

echo $form->getSubmitButtonRow(Yii::t('email', 'Search'));

$this->endWidget();