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
$this->pageTitle = Yii::t('email', 'Spool ID-:id', array(':id' => $emailSpool->id));

// details
$attributes = array();
$attributes[] = array(
    'name' => 'id',
    'value' => ' emailSpool-' . $emailSpool->id,
);
$attributes[] = 'subject';
$attributes[] = 'to_address';
$attributes[] = 'from_address';
$attributes[] = 'status';
$attributes[] = 'model';
$attributes[] = 'model_id';
$attributes[] = array(
    'name' => 'model_id',
    //'value' => $emailSpool->getModelLink(),
    'type' => 'raw',
);
$attributes[] = 'sent';
$this->widget('zii.widgets.CDetailView', array(
    'data' => $emailSpool,
    'attributes' => $attributes,
));

echo CHtml::tag('h2', array(), Yii::t('email', 'Message HTML'));
//echo $emailSpool->message;

echo CHtml::tag('h2', array(), Yii::t('email', 'Message Text'));
//echo nl2br($emailSpool->message_text);
