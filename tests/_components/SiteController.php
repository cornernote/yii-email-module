<?php

/**
 * SiteController for Tests
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class SiteController extends CController
{

    public function actionIndex()
    {
        echo 'Hello World';
    }

    public function actionSendEmail()
    {
        $emailManager = Yii::app()->emailManager;
        $emailManager->email($emailManager->fromEmail, 'EmailManager subject', 'EmailManager message');
    }

    public function actionSendTemplateEmail()
    {
        /** @var EmailManager $emailManager */
        $emailManager = Yii::app()->emailManager;

        // build the templates
        $template = 'test';
        $viewParams = array(
            'foo' => 'bar',
        );
        $message = $emailManager->buildTemplateMessage($template, $viewParams, 'layout_fancy');

        // get the message
        $swiftMessage = Swift_Message::newInstance($message['subject']);
        $swiftMessage->setBody($message['message'], 'text/html');
        //$swiftMessage->addPart($message['text'], 'text/plain');
        $swiftMessage->setFrom($emailManager->fromEmail, $emailManager->fromName);
        $swiftMessage->setTo($emailManager->fromEmail);

        // spool the email
        $emailSpool = $emailManager->getEmailSpool($swiftMessage);
        $emailSpool->priority = 10;
        $emailSpool->template = $template;
        return $emailSpool->save(false);

    }

}