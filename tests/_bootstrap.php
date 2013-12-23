<?php
/**
 * Global Test Bootstrap
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */

// define paths
define('BASE_PATH', realpath(__DIR__));
define('VENDOR_PATH', realpath(__DIR__ . '/../../vendor'));
define('YII_PATH', realpath(VENDOR_PATH . '/yiisoft/yii/framework'));

echo BASE_PATH . "\n";
echo VENDOR_PATH . "\n";
echo YII_PATH . "\n";

// disable Yii error handling logic
defined('YII_ENABLE_EXCEPTION_HANDLER') or define('YII_ENABLE_EXCEPTION_HANDLER', false);
defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', false);

// create application
require_once(YII_PATH . '/Yii.php');
Yii::createApplication('CWebApplication', array(
    'basePath' => BASE_PATH,
    'runtimePath' => realpath(BASE_PATH . '/_runtime'),
    'components' => array(
        'assetManager' => array(
            'basePath' => realpath(BASE_PATH . '/_assets'),
        ),
    )
));