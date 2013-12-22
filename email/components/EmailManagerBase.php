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
class EmailManagerBase extends CComponent
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
        // include SwiftMailer
        $path = Yii::getPathOfAlias('vendor') . '/swiftmailer/swiftmailer/lib';
        require_once($path . '/classes/Swift.php');
        Yii::registerAutoloader(array('Swift', 'autoload'));
        require_once($path . '/swift_init.php');

        // set default from name
        if ($this->fromName === null)
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
     * @return bool
     */
    public function sendEmail($to_email, $subject, $message_text, $attachments = array())
    {
        // build the templates
        $message = array(
            'message_subject' => $subject,
            'message_text' => $message_text,
            'message_html' => Yii::app()->format->formatNtext($message_text),
        );

        // get the message
        $swiftMessage = $this->getSwiftMessage($message);
        $swiftMessage->setFrom($this->fromEmail, $this->fromName);
        $swiftMessage->setTo($to_email);
        foreach ($attachments as $attachment)
            $swiftMessage->attach(Swift_Attachment::fromPath($attachment));

        // spool the email
        $emailSpool = $this->getEmailSpool($swiftMessage);
        return $emailSpool->save(false);

        // or send the email
        //return Swift_Mailer::newInstance(Swift_MailTransport::newInstance())->send($swiftMessage);
    }

    /**
     * @param Swift_Message $swiftMessage
     * @param CActiveRecord|null $model
     * @return EmailSpool
     */
    public function getEmailSpool($swiftMessage, $model = null)
    {
        $emailSpool = new EmailSpool;
        $emailSpool->created = time();
        $emailSpool->status = 'pending';
        $emailSpool->subject = $swiftMessage->getSubject();
        $emailSpool->message = $emailSpool->pack($swiftMessage);
        $emailSpool->to_address = json_encode($swiftMessage->getTo());
        $emailSpool->from_address = json_encode($swiftMessage->getFrom());
        if ($model) {
            $emailSpool->model_name = get_class($model);
            $emailSpool->model_id = is_array($model->getPrimaryKey()) ? implode('-', $model->getPrimaryKey()) : $model->getPrimaryKey();
        }
        return $emailSpool;
    }


    /**
     * @param array $message
     * @return Swift_Message
     */
    public function getSwiftMessage($message)
    {
        $swiftMessage = Swift_Message::newInstance($message['message_subject']);
        $swiftMessage->setBody($message['message_text']);
        $swiftMessage->addPart($message['message_html'], 'text/html');
        return $swiftMessage;
    }

    /**
     * @param $template string
     * @param $viewParams array
     * @return array
     */
    public function buildTemplateMessage($template, $viewParams = array(), $layout = 'layout_default')
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
        foreach ($fields as $field) {
            $viewParams['contents'] = $mustache->render($emailTemplate->$field, $viewParams);
            $viewParams[$field] = $message[$field] = $mustache->render($emailLayout->$field, $viewParams);
            unset($viewParams['contents']);
        }
        return $message;
    }


    /**
     * Find pending emails and attempt to deliver them
     */
    public function processSpool()
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

            // build the message
            $message = $spool->unpack($spool->message);

            // send the email and update status
            $Mailer = Swift_Mailer::newInstance(Swift_MailTransport::newInstance());
            $spool->status = $Mailer->send($message) ? 'emailed' : 'error';
            $spool->sent = time();
            $spool->save(false);

        }
    }

}
