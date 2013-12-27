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

$this->pageTitle = Yii::t('email', 'Template ID-:id', array(':id' => $emailTemplate->id));

// links
$this->menu[] = array('label' => Yii::t('email', 'Update'), 'url' => array('update', 'id' => $emailTemplate->id), 'linkOptions' => array('class' => 'btn btn-default'));
$this->menu[] = array('label' => Yii::t('email', 'Preview'), 'url' => array('preview', 'id' => $emailTemplate->id), 'linkOptions' => array('class' => 'btn btn-default fancybox', 'data-fancybox-type' => 'iframe'));

// details
$attributes = array();
$attributes[] = array(
    'name' => 'id',
);
$attributes[] = array(
    'name' => 'name',
);
$attributes[] = array(
    'name' => 'subject',
);
$attributes[] = array(
    'name' => 'heading',
);

$this->widget('zii.widgets.CDetailView', array(
    'data' => $emailTemplate,
    'attributes' => $attributes,
    'htmlOptions' => array(
        'class' => 'table table-condensed table-striped',
    ),
));

// message
echo CHtml::tag('h2', array(), Yii::t('email', 'Message'));
echo CHtml::tag('pre', array(), CHtml::encode($emailTemplate->message));