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
    public $swiftMailerPath;

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
     * @var string
     */
    public $defaultTransport = 'mail';

    /**
     * @var array
     */
    public $transports = array(
        'mail' => array(
            'class' => 'Swift_MailTransport',
        ),
    );

    /**
     *
     */
    public function init()
    {
        $this->registerSwiftMailerAutoloader();
    }

    /**
     * Send an email.
     * Email addresses can be formatted as a string 'user@dom.ain' or as an array('user@dom.ain'=>'User name').
     *
     * Eg:
     * Yii::app()->emailManager->email('user@dom.ain', 'test email', '<b>Hello</b> <i>World<i>!');
     *
     * @param string|array $to
     * @param string $subject
     * @param string $message
     * @param string|array $from
     * @param array $attachments
     * @param string $transport
     * @param bool $spool
     * @return bool
     */
    public function email($to, $subject, $message, $from = null, $attachments = array(), $transport = null, $spool = true)
    {
        // get the message
        $swiftMessage = Swift_Message::newInstance($subject);
        $swiftMessage->setTo($to);
        $swiftMessage->setBody($message, 'text/html');

        // set the from
        if (!$from)
            $swiftMessage->setFrom($this->fromEmail, $this->fromName);
        else
            $swiftMessage->setFrom($from);

        // attach files
        foreach ($attachments as $attachment)
            $swiftMessage->attach(Swift_Attachment::fromPath($attachment));

        // send the email
        if (!$spool)
            return $this->deliver($swiftMessage, $transport);

        // or spool the email
        $emailSpool = $this->getEmailSpool($swiftMessage);
        $emailSpool->transport = $transport;
        return $emailSpool->save(false);
    }

    /**
     * Deliver a message using a Swift_Transport class.
     *
     * Eg:
     * Yii::app()->emailManager->deliver($swiftMessage);
     *
     * @param $swiftMessage
     * @param string $transport
     * @throws CException
     * @return bool
     */
    public function deliver($swiftMessage, $transport = null)
    {
        // get the transport
        if (!$transport)
            $transport = $this->defaultTransport;
        if (!isset($this->transports[$transport]))
            throw new CException(Yii::t('email', 'Transport :transport is not configured.', array(':transport' => $transport)));

        // get transport options
        $options = $this->transports[$transport];

        // get transport class
        if (isset($options['class'])) {
            $class = $options['class'];
            unset($options['class']);
        }
        else {
            throw new CException(Yii::t('email', 'Transport :transport does not have a class.', array(':transport' => $transport)));
        }

        // get transport setters
        if (isset($options['setters'])) {
            $setters = $options['setters'];
            unset($options['setters']);
        }
        else {
            $setters = array();
        }

        // create a new transport using class, options and setters
        $swiftTransport = call_user_func_array($class . '::newInstance', $options);
        foreach ($setters as $k => $v) {
            call_user_func_array(array($swiftTransport, 'set' . ucfirst($k)), array($v));
        }

        // send the message using the transport
        return Swift_Mailer::newInstance($swiftTransport)->send($swiftMessage);
    }

    /**
     * Deliver pending EmailSpool records.
     */
    public function spool($limit = 10)
    {
        // find all the spooled emails
        $emailSpools = EmailSpool::model()->findAll(array(
            'condition' => 't.status=:status',
            'params' => array(':status' => 'pending'),
            'order' => 't.priority DESC, t.created ASC',
            'limit' => $limit,
        ));
        foreach ($emailSpools as $emailSpool) {

            // update status to emailing
            $emailSpool->status = 'processing';
            $emailSpool->save(false);

            // build the message
            $swiftMessage = $emailSpool->unpack($emailSpool->message);

            // send the email
            $sent = $this->deliver($swiftMessage, $emailSpool->transport);

            // update status and save
            $emailSpool->status = $sent ? 'emailed' : 'error';
            $emailSpool->sent = time();
            $emailSpool->save(false);

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
        $path = realpath($this->swiftMailerPath);
        if (!$path)
            throw new CException('The EmailManager.swiftMailerPath is invalid.');
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
