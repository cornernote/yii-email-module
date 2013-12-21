<?php
/**
 * EmailManagerBase
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */
class EmailManagerBase extends CApplicationComponent
{

    /**
     * @var string defaults to the application name
     */
    public $fromName;

    /**
     * @var string
     */
    public $fromEmail = 'webmaster@localhost';

    /**
     * @var string Render method, can be one of: php, database
     */
    public $renderMethod = 'php';

    /**
     * @var string when renderMethod=php this is the path to the email views
     */
    public $templatePath = 'email.views.emails';

    /**
     *
     */
    public function init()
    {
        if (!$this->fromName)
            $this->fromName = Yii::app()->name;
    }

    /**
     * Allows sending a quick email.
     *
     * Eg:
     * Yii::app()->email->sendEmail('webmaster@localhost', 'subject', 'message');
     *
     * @param $to_email
     * @param $subject
     * @param $message_text
     * @param $filename
     */
    public function sendEmail($to_email, $subject, $message_text, $filename = null)
    {
        $emailSpool = $this->getEmailSpool(array(
            'message_subject' => $subject,
            'message_text' => $message_text,
            'message_html' => Yii::app()->format->formatNtext($message_text),
        ));
        $emailSpool->status = $filename ? 'attaching' : 'pending';
        $emailSpool->from_email = $this->fromEmail;
        $emailSpool->from_name = $this->fromName;
        $emailSpool->to_email = $to_email;
        $emailSpool->save(false);

        if ($filename) {
            // TODO handle attachments
            //$attachment = new Attachment();
            //$attachment->model = 'EmailSpool';
            //$attachment->model_id = $emailSpool->id;
            //$attachment->filename = $filename;
            //$attachment->handleFileUpload = false;

            $emailSpool->status = 'pending';
            $emailSpool->save(false);
        }
    }

    /**
     * @param array $message
     * @return EmailSpool
     */
    public function getEmailSpool($message)
    {
        $emailSpool = new EmailSpool;
        $emailSpool->status = 'pending';
        $emailSpool->template = vd($message['template']);
        $emailSpool->message_subject = $message['message_subject'];
        $emailSpool->message_text = $message['message_text'];
        $emailSpool->message_html = $message['message_html'];
        return $emailSpool;
    }

    /**
     * @param $template string
     * @param $viewParams array
     * @return array
     */
    public function renderEmailTemplate($template, $viewParams = array(), $layout = 'layout_default')
    {
        if (!method_exists($this, 'renderEmailTemplate_' . $this->renderMethod))
            $this->renderMethod = 'php';
        return call_user_func_array(array($this, 'renderEmailTemplate_' . $this->renderMethod), array($template, $viewParams, $layout));
    }

    /**
     * @param $template string
     * @param $viewParams array
     * @throws CException
     * @return array
     */
    private function renderEmailTemplate_php($template, $viewParams = array(), $layout = 'layout_default')
    {
        // setup path to layout and template
        $emailTemplate = $this->templatePath . '.' . $template;
        $emailLayout = $this->templatePath . '.' . $layout;

        // parse template
        $fields = array('message_title', 'message_subject', 'message_html', 'message_text');
        $message = array('template' => $template);
        $controller = Yii::app()->controller;
        foreach ($fields as $field) {
            $viewParams['contents'] = $controller->renderPartial($emailTemplate . '.' . str_replace('message_', '', $field), $viewParams, true);
            $viewParams[$field] = $message[$field] = $controller->renderPartial($emailLayout . '.' . str_replace('message_', '', $field), $viewParams, true);
            unset($viewParams['contents']);
        }
        return $message;
    }

    /**
     * @param $template string
     * @param $viewParams array
     * @throws CException
     * @return array
     */
    private function renderEmailTemplate_database($template, $viewParams = array(), $layout = 'layout_default')
    {
        // load template
        $emailTemplate = EmailTemplate::model()->findByAttributes(array('name' => $template));
        if (!$emailTemplate)
            throw new CException('missing EmailTemplate - ' . $template);

        // load layout
        $emailLayout = EmailTemplate::model()->findByAttributes(array('name' => $layout));
        if (!$emailLayout)
            throw new CException('missing EmailTemplate - ' . $layout);

        // parse template
        $mustache = new EmailMustache();
        $fields = array('message_title', 'message_subject', 'message_html', 'message_text');
        $message = array('template' => $template);
        foreach ($fields as $field) {
            $viewParams['contents'] = $mustache->render($emailTemplate->$field, $viewParams);
            $viewParams[$field] = $message[$field] = $mustache->render($emailLayout->$field, $viewParams);
            unset($viewParams['contents']);
        }
        return $message;
    }


    /**
     * Find pending emails and attempt to deliver them
     * @param bool $mailinator
     */
    public function processSpool($mailinator = false)
    {
        // find all the spooled emails
        $spools = EmailSpool::model()->findAll(array(
            'condition' => 't.status=:status',
            'params' => array(':status' => 'pending'),
            'order' => 't.priority DESC, t.id ASC',
            'limit' => '10',
        ));
        foreach ($spools as $spool) {

            // update status to emailing
            $spool->status = 'processing';
            $spool->save(false);

            // get the to_email
            $to_email = $mailinator ? str_replace('@', '.', $spool->to_email) . '@mailinator.com' : $spool->to_email;

            // build the message
            $SM = Yii::app()->swiftMailer;
            $message = $SM->newMessage($spool->message_subject);
            $message->setFrom($spool->from_name ? array($spool->from_email => $spool->from_name) : array($spool->from_email));
            $message->setTo($spool->to_name ? array($to_email => $spool->to_name) : array($to_email));
            $message->setBody($spool->message_text);
            $message->addPart($spool->message_html, 'text/html');
            foreach ($spool->attachment as $attachment) {
                $message->attach(Swift_Attachment::fromPath($attachment->filename));
            }

            // send the email and update status
            $Transport = $SM->mailTransport();
            $Mailer = $SM->mailer($Transport);
            if ($Mailer->send($message)) {
                $spool->status = 'emailed';
                $spool->sent = date('Y-m-d H:i:s');
            }
            else {
                $spool->status = 'error';
            }
            $spool->save(false);

        }
    }

}
