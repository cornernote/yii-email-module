<?php
/**
 * EmailTemplateController Test
 *
 * @var $scenario \Codeception\Scenario
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */

$I = new WebGuy($scenario);
$I->wantTo('ensure TemplateController works');

$I->amOnPage('email/template/index');
$I->see('Templates');
$I->see('Search');