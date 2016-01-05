<?php
/**
 * @var $this EmailStatsController
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */

Yii::app()->user->setState('index.emailStats', Yii::app()->request->requestUri);
$this->pageTitle = Yii::t('email', 'Stats');


$tabs = array();

$transports = Yii::app()->emailManager->transports;
foreach ($transports as $transportName => $transport) {

    $attributes = array();

    for ($day = 0; $day < 90; $day++) {
        $date = date('Y-m-d', strtotime('-' . $day . 'days'));
        $criteria = new CDbCriteria();
        $criteria->compare('transport', $transportName);
        $criteria->addBetweenCondition('created', strtotime(date('Y-m-d 00:00:00', strtotime($date))), strtotime(date('Y-m-d 23:59:59', strtotime($date))));
        $count = EmailSpool::model()->count($criteria);
        $attributes[] = array(
            'label' => $date,
            'value' => $count,
        );
    }

    $tabs[] = array(
        'label' => $transportName,
        'content' => $this->widget('DetailView', array(
            'data' => false,
            'attributes' => $attributes,
        ), true),
    );

}

$tabs[0]['active'] = true;
$this->widget('bootstrap.widgets.TbTabs', array(
    'tabs' => $tabs,
));