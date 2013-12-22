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
    'id' => 'emailTemplate-form',
));
echo $form->errorSummary($emailTemplate);

echo $form->textFieldRow($emailTemplate, 'name');
echo $form->textFieldRow($emailTemplate, 'subject');
echo $form->textFieldRow($emailTemplate, 'heading');
echo $form->textAreaRow($emailTemplate, 'message');

echo $form->getSubmitButtonRow($emailTemplate->isNewRecord ? Yii::t('email', 'Create') : Yii::t('email', 'Save'));
$this->endWidget();