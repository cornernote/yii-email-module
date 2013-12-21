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
$this->pageTitle = $emailSpool->getName();

$this->renderPartial('/emailSpool/_menu', array(
    'emailSpool' => $emailSpool,
));

// details
ob_start();
$attributes = array();
$attributes[] = array(
    'name' => 'id',
    'value' => ' emailSpool-' . $emailSpool->id,
);
$attributes[] = 'message_subject';
$attributes[] = 'to_email';
$attributes[] = 'to_name';
$attributes[] = 'status';
$attributes[] = 'model';
$attributes[] = 'model_id';
$attributes[] = 'from_email';
$attributes[] = 'from_name';
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
$details = ob_get_clean();

// tabs
// TODO render email
//$this->widget('bootstrap.widgets.TbTabs', array(
//    'type' => 'pills', // 'tabs' or 'pills'
//    'tabs' => array(
//        array('label' => Yii::t('email', 'Details'), 'content' => $details, 'active' => true),
//        array('label' => Yii::t('email', 'HTML Message'), 'content' => $emailSpool->message_html),
//        array('label' => Yii::t('email', 'Text Message'), 'content' => nl2br($emailSpool->message_text)),
//    ),
//));