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

$columns = array();
$columns[] = array(
    'name' => 'id',
    'value' => 'CHtml::link($data->id, array("template/view", "id" => $data->id))',
    'type' => 'raw',
);
$columns[] = array(
    'name' => 'name',
);
$columns[] = array(
    'name' => 'subject',
);
$columns[] = array(
    'name' => 'heading',
);

// grid
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'emailTemplate-grid',
    'dataProvider' => $emailTemplate->search(),
    'filter' => $emailTemplate,
    'columns' => $columns,
));