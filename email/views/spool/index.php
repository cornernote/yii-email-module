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

Yii::app()->user->setState('index.emailSpool', Yii::app()->request->requestUri);
$this->pageTitle = Yii::t('email', 'Spools');

// links
$items = array();
$items[] = array('label' => Yii::t('email', 'Search'), 'url' => '#', 'linkOptions' => array('class' => 'emailSpool-grid-search btn btn-default'));
if (Yii::app()->user->getState('index.emailSpool') != $this->createUrl('index'))
    $items[] = array('label' => Yii::t('email', 'Reset Filters'), 'url' => array('index'), 'linkOptions' => array('class' => 'btn btn-default'));
$this->pageTitle .= $this->widget('zii.widgets.CMenu', array(
    'items' => $items,
    'htmlOptions' => array('class' => 'list-inline pull-right'),
), true);

// search
$this->renderPartial('_search', array(
    'emailSpool' => $emailSpool,
));

// grid
$this->renderPartial('_grid', array(
    'emailSpool' => $emailSpool,
    'dataProvider' => isset($dataProvider) ? $dataProvider : null,
));