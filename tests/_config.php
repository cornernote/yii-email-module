<?php
/**
 * Global Test Config
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */

return array(
    'basePath' => BASE_PATH,
    'runtimePath' => realpath(BASE_PATH . '/_runtime'),
    'aliases' => array(
        'email' => realpath(BASE_PATH . '/../email'),
    ),
    'components' => array(
        'assetManager' => array(
            'basePath' => realpath(BASE_PATH . '/_public/assets'),
        ),
        'emailManager' => array(
            'class' => 'email.components.EmailManager',
            'controllerFilters' => array(),
        ),
    ),
    'modules' => array(
        'email' => array(
            'class' => 'email.EmailModule',
            'adminUsers' => array('admin'),
        ),
    ),
);