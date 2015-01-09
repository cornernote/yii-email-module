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

$columns = array();
$columns[] = array(
    'name' => 'id',
    'value' => 'CHtml::link($data->id, array("/email/spool/view", "id" => $data->id))',
    'type' => 'raw',
);
$columns[] = array(
    'name' => 'transport',
);
$columns[] = array(
    'name' => 'template',
);
$columns[] = array(
    'name' => 'priority',
);
$columns[] = array(
    'name' => 'model_name',
    'filter' => is_scalar($emailSpool->model_name) ? null : false,
);
$columns[] = array(
    'name' => 'model_id',
);
$columns[] = array(
    'name' => 'to_address',
);
$columns[] = array(
    'name' => 'from_address',
);
$columns[] = array(
    'name' => 'subject',
);
$columns[] = array(
    'name' => 'status',
    'filter' => array('pending' => Yii::t('email', 'Pending'), 'processing' => Yii::t('email', 'Processing'), 'emailed' => Yii::t('email', 'Emailed'), 'error' => Yii::t('email', 'Error')),
);
$columns[] = array(
    'name' => 'sent',
    'value' => '$data->sent ? Yii::app()->format->formatDatetime($data->sent) : null',
);
$columns[] = array(
    'name' => 'created',
    'value' => 'Yii::app()->format->formatDatetime($data->created)',
);

// grid
$this->widget(Yii::app()->getModule('email')->gridViewWidget, array(
    'id' => 'emailSpool-grid',
    'dataProvider' => $emailSpool->search(),
    'filter' => $emailSpool,
    'columns' => $columns,
));