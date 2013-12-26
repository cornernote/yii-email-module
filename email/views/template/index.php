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

Yii::app()->user->setState('index.emailTemplate', Yii::app()->request->requestUri);
$this->pageTitle = Yii::t('email', 'Templates');

// links
$this->menu[] = array('label' => Yii::t('email', 'Create'), 'url' => array('create'), 'linkOptions' => array('class' => 'btn btn-default'));
$this->menu[] = array('label' => Yii::t('email', 'Search'), 'url' => '#', 'linkOptions' => array('class' => 'emailTemplate-grid-search btn btn-default'));
if (Yii::app()->user->getState('index.emailTemplate') != $this->createUrl('index'))
    $this->menu[] = array('label' => Yii::t('email', 'Reset Filters'), 'url' => array('index'), 'linkOptions' => array('class' => 'btn btn-default'));

// message if wrong templateType
if (Yii::app()->emailManager->templateType != 'db') {
    echo CHtml::tag('div', array('class' => 'alert alert-danger'), Yii::t('email', 'EmailManager.templateType is not set to db, these templates will not be used.'));
}

// search
$this->renderPartial('_search', array(
    'emailTemplate' => $emailTemplate,
));

// grid
$this->renderPartial('_grid', array(
    'emailTemplate' => $emailTemplate,
));