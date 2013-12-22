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
$items = array();
$items[] = array('label' => Yii::t('email', 'Update'), 'url' => array('update', 'id' => $emailTemplate->id), 'linkOptions' => array('class' => 'btn btn-default'));
$this->pageHeading = $this->pageTitle . $this->widget('zii.widgets.CMenu', array(
        'items' => $items,
        'htmlOptions' => array('class' => 'list-inline pull-right'),
    ), true);

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
));

// message
echo CHtml::tag('h2', array(), Yii::t('email', 'Message'));
echo CHtml::tag('pre', array(), $emailTemplate->message);