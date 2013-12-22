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
     */
    public function sendAccountRecover($user)
    {
        // get recovery temp login link
        $token = EmailToken::model()->add(strtotime('+1day'), 1, get_class($user), $user->user_id);
        $url = Yii::app()->createAbsoluteUrl('/account/passwordReset', array('id' => $user->user_id, 'token' => $token));

        // save EmailSpool
        $emailSpool = $this->getEmailSpool($this->renderEmailTemplate('account_recover', array(
            'user' => $user,
            'url' => $url,
        )));
        $emailSpool->priority = 10;
        $emailSpool->to_email = $user->email;
        $emailSpool->to_name = $user->name;
        $emailSpool->from_email = $this->fromEmail;
        $emailSpool->from_name = $this->fromName;
        $emailSpool->model_name = get_class($user);
        $emailSpool->model_id = $user->user_id;
        $emailSpool->save(false);
    }

    /**
     * @param $user User
     */
    public function sendAccountWelcome($user)
    {
        // get activation token
        $token = EmailToken::model()->add('+30days', 1, get_class($user), $user->user_id);
        $url = Yii::app()->createAbsoluteUrl('/account/activate', array('id' => $user->user_id, 'token' => $token));

        // save EmailSpool
        $emailSpool = $this->getEmailSpool($this->renderEmailTemplate('account_welcome', array(
            'user' => $user,
            'url' => $url,
        )));
        $emailSpool->priority = 5;
        $emailSpool->to_email = $user->email;
        $emailSpool->to_name = $user->name;
        $emailSpool->from_email = $this->fromEmail;
        $emailSpool->from_name = $this->fromName;
        $emailSpool->model_name = get_class($user);
        $emailSpool->model_id = $user->user_id;
        $emailSpool->save(false);
    }

}
