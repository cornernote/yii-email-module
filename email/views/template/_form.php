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
    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
));
echo $form->errorSummary($emailTemplate);

echo $form->textFieldControlGroup($emailTemplate, 'name', array('class' => 'input-block-level'));
echo $form->textFieldControlGroup($emailTemplate, 'subject', array('class' => 'input-block-level'));
echo $form->textFieldControlGroup($emailTemplate, 'heading', array('class' => 'input-block-level'));
echo $form->textAreaControlGroup($emailTemplate, 'message', array('class' => 'input-block-level', 'rows' => 10));

echo $form->getSubmitButtonRow($emailTemplate->isNewRecord ? Yii::t('email', 'Create') : Yii::t('email', 'Save'));
$this->endWidget();