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
    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
    'htmlOptions' => array('style' => 'display:none;'),
));
$form->searchToggle('emailSpool-grid-search', 'emailSpool-grid');

echo $form->textFieldControlGroup($emailSpool, 'id');
echo $form->textFieldControlGroup($emailSpool, 'transport');
echo $form->textFieldControlGroup($emailSpool, 'template');
echo $form->textFieldControlGroup($emailSpool, 'priority');
echo $form->textFieldControlGroup($emailSpool, 'status');
echo $form->textFieldControlGroup($emailSpool, 'model_name');
echo $form->textFieldControlGroup($emailSpool, 'model_id');
echo $form->textFieldControlGroup($emailSpool, 'to_address');
echo $form->textFieldControlGroup($emailSpool, 'from_address');
echo $form->textFieldControlGroup($emailSpool, 'subject');
echo $form->textFieldControlGroup($emailSpool, 'message');
echo $form->textFieldControlGroup($emailSpool, 'sent');
echo $form->textFieldControlGroup($emailSpool, 'created');

echo $form->getSubmitButtonRow(Yii::t('email', 'Search'));

$this->endWidget();