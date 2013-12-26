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

echo $form->textFieldControlGroup($emailTemplate, 'name');
echo $form->textFieldControlGroup($emailTemplate, 'subject');
echo $form->textFieldControlGroup($emailTemplate, 'heading');
echo $form->textAreaControlGroup($emailTemplate, 'message');

echo $form->getSubmitButtonRow($emailTemplate->isNewRecord ? Yii::t('email', 'Create') : Yii::t('email', 'Save'));
$this->endWidget();