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

$attributes = array();
$attributes[] = array(
    'name' => 'id',
);
$attributes[] = array(
    'name' => 'name',
);
$attributes[] = array(
    'name' => 'message_subject',
);
$attributes[] = array(
    'name' => 'message_title',
);
$attributes[] = array(
    'name' => 'message_html',
    'type' => 'raw',
);
$attributes[] = array(
    'name' => 'message_text',
);

$this->widget('zii.widgets.CDetailView', array(
    'data' => $emailTemplate,
    'attributes' => $attributes,
));