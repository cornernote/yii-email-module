<?php
/**
 * @var $this EmailSpoolController
 * @var $emailSpool EmailSpool
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
$form->searchToggle('emailSpool-grid-search', 'emailSpool-grid');

echo $form->textFieldRow($emailSpool, 'id');
echo $form->textFieldRow($emailSpool, 'transport');
echo $form->textFieldRow($emailSpool, 'template');
echo $form->textFieldRow($emailSpool, 'priority');
echo $form->textFieldRow($emailSpool, 'status');
echo $form->textFieldRow($emailSpool, 'model_name');
echo $form->textFieldRow($emailSpool, 'model_id');
echo $form->textFieldRow($emailSpool, 'to_email');
echo $form->textFieldRow($emailSpool, 'to_name');
echo $form->textFieldRow($emailSpool, 'from_email');
echo $form->textFieldRow($emailSpool, 'from_name');
echo $form->textFieldRow($emailSpool, 'message_subject');
echo $form->textFieldRow($emailSpool, 'message_html');
echo $form->textFieldRow($emailSpool, 'message_text');
echo $form->textFieldRow($emailSpool, 'attachments');
echo $form->textFieldRow($emailSpool, 'sent');
echo $form->textFieldRow($emailSpool, 'created');

echo $form->getSubmitButtonRow(Yii::t('email', 'Search'));

$this->endWidget();