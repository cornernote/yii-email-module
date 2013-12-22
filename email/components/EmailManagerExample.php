<?php
/**
 * EmailManagerExample
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */
class EmailManagerExample extends EmailManagerBase
{

    /**
     * @param $user User
     * @return bool
     */
    public function sendAccountRecover($user)
    {
        // get recovery temp login link
        $token = EmailToken::model()->add(strtotime('+1day'), 1, get_class($user), $user->user_id);
        $url = Yii::app()->createAbsoluteUrl('/account/passwordReset', array('id' => $user->user_id, 'token' => $token));

        // build the templates
        $template = 'account_recover';
        $message = $this->buildTemplateMessage($template, array(
            'user' => $user,
            'url' => $url,
        ));

        // get the message
        $swiftMessage = Swift_Message::newInstance($message['subject']);
        $swiftMessage->setBody($message['message'], 'text/html');
        //$swiftMessage->addPart($message['text'], 'text/plain');
        $swiftMessage->setFrom($this->fromEmail, $this->fromName);
        $swiftMessage->setTo($user->email, $user->name);

        // spool the email
        $emailSpool = $this->getEmailSpool($swiftMessage, $user);
        $emailSpool->priority = 10;
        $emailSpool->template = $template;
        return $emailSpool->save(false);

        // or send the email
        //return Swift_Mailer::newInstance(Swift_MailTransport::newInstance())->send($swiftMessage);
    }

    /**
     * @param $user User
     * @return bool
     */
    public function sendAccountWelcome($user)
    {
        // get activation token
        $token = EmailToken::model()->add('+30days', 1, get_class($user), $user->user_id);
        $url = Yii::app()->createAbsoluteUrl('/account/activate', array('id' => $user->user_id, 'token' => $token));

        // build the templates
        $template = 'account_welcome';
        $message = $this->buildTemplateMessage($template, array(
            'user' => $user,
            'url' => $url,
        ));

        // get the message
        $swiftMessage = Swift_Message::newInstance($message['subject']);
        $swiftMessage->setBody($message['message'], 'text/html');
        //$swiftMessage->addPart($message['text'], 'text/plain');
        $swiftMessage->setFrom($this->fromEmail, $this->fromName);
        $swiftMessage->setTo($user->email, $user->name);

        // spool the email
        $emailSpool = $this->getEmailSpool($swiftMessage, $user);
        $emailSpool->priority = 5;
        $emailSpool->template = $template;
        return $emailSpool->save(false);

        // or send the email
        //return Swift_Mailer::newInstance(Swift_MailTransport::newInstance())->send($swiftMessage);
    }

}
