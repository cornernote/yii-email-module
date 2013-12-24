<?php
/**
 * EmailManager
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */
class EmailManager extends CComponent
{

    /**
     * @var string
     */
    public $fromEmail = 'webmaster@localhost';

    /**
     * @var string If unset the application name is used.
     * @see setFromName
     */
    private $_fromName;

    /**
     * @var string Template type, can be one of: php, db
     */
    public $templateType = 'php';

    /**
     * @var string when templateType=php this is the path to the email views
     */
    public $templatePath = 'email.views.emails';

    /**
     * @var array
     */
    public $templateFields = array('subject', 'heading', 'message');

    /**
     *
     */
    public function init()
    {
        $this->registerSwiftMailerAutoloader();
    }

    /**
     * Send an email.
     *
     * Eg:
     * Yii::app()->emailManager->email('user@dom.ain', 'test email', '<b>Hello</b> <i>World<i>!');
     *
     * @param $to
     * @param $subject
     * @param $message
     * @param $attachments
     * @param $from
     * @param $spool
     * @return bool
     */
    public function email($to, $subject, $message, $from = null, $attachments = array(), $spool = true)
    {
        // get the message
        $swiftMessage = Swift_Message::newInstance($subject);
        $swiftMessage->setTo($to);
        $swiftMessage->setBody($message, 'text/html');

        // set the from
        if (!$from)
            $swiftMessage->setFrom($this->fromEmail, $this->fromName);

        // attach files
        foreach ($attachments as $attachment)
            $swiftMessage->attach(Swift_Attachment::fromPath($attachment));

        // send the email
        if (!$spool)
            return Swift_Mailer::newInstance(Swift_MailTransport::newInstance())->send($swiftMessage);

        // or spool the email
        $emailSpool = $this->getEmailSpool($swiftMessage);
        return $emailSpool->save(false);
    }

    /**
     * Process pending EmailSpool records.
     */
    public function spool($limit = 10)
    {
        // find all the spooled emails
        $spools = EmailSpool::model()->findAll(array(
            'condition' => 't.status=:status',
            'params' => array(':status' => 'pending'),
            'order' => 't.priority DESC, t.created ASC',
            'limit' => $limit,
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

    /**
     * Creates an EmailSpool based on a Swift_Message.
     * Can optionally relate the EmailSpool to a model_id and model_name by passing an CActiveRecord into $model.
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
     * Builds a template using the selected build method.
     * @param $template string
     * @param $viewParams array
     * @param string $layout
     * @return array
     */
    public function buildTemplateMessage($template, $viewParams = array(), $layout = 'layout_default')
    {
        $method = 'buildTemplateMessage_' . $this->templateType;
        if (!method_exists($this, $method))
            $this->templateType = 'php';
        return call_user_func_array(array($this, $method), array($template, $viewParams, $layout));
    }

    /**
     * @param $template string
     * @param $viewParams array
     * @param string $layout
     * @return array
     */
    private function buildTemplateMessage_php($template, $viewParams = array(), $layout = 'layout_default')
    {
        // setup path to layout and template
        $emailTemplate = $this->templatePath . '.' . $template;
        $emailLayout = $this->templatePath . '.' . $layout;

        // parse template
        $message = array();
        $controller = Yii::app()->controller;
        foreach ($this->templateFields as $field) {
            $viewParams['contents'] = $controller->renderPartial($emailTemplate . '.' . $field, $viewParams, true);
            $viewParams[$field] = $message[$field] = $controller->renderPartial($emailLayout . '.' . $field, $viewParams, true);
            unset($viewParams['contents']);
        }
        return $message;
    }

    /**
     * @param $template string
     * @param $viewParams array
     * @param string $layout
     * @throws CException
     * @return array
     */
    private function buildTemplateMessage_db($template, $viewParams = array(), $layout = 'layout_default')
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
        $message = array();
        $mustache = new EmailMustache();
        foreach ($this->templateFields as $field) {
            $viewParams['contents'] = $mustache->render($emailTemplate->$field, $viewParams);
            $viewParams[$field] = $message[$field] = $mustache->render($emailLayout->$field, $viewParams);
            unset($viewParams['contents']);
        }
        return $message;
    }

    /**
     * Registers the SwiftMailer autoloader
     */
    private function registerSwiftMailerAutoloader()
    {
        $path = Yii::getPathOfAlias('swiftMailer');
        if (!$path)
            throw new CException('The alias swiftMailer does not have a path.');
        require_once($path . '/classes/Swift.php');
        Yii::registerAutoloader(array('Swift', 'autoload'));
        require_once($path . '/swift_init.php');
    }

    /**
     * @param string $fromName
     */
    public function setFromName($fromName)
    {
        $this->_fromName = $fromName;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        if ($this->_fromName === null)
            $this->_fromName = Yii::app()->name;
        return $this->_fromName;
    }

}
