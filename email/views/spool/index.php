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
$this->menu[] = array('label' => Yii::t('email', 'Search'), 'url' => '#', 'linkOptions' => array('class' => 'emailSpool-grid-search btn btn-default'));
if (Yii::app()->user->getState('index.emailSpool') != $this->createUrl('index'))
    $this->menu[] = array('label' => Yii::t('email', 'Reset Filters'), 'url' => array('index'), 'linkOptions' => array('class' => 'btn btn-default'));

// search
$this->renderPartial('_search', array(
    'emailSpool' => $emailSpool,
));

// grid
$this->renderPartial('_grid', array(
    'emailSpool' => $emailSpool,
));