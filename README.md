# Yii Email Module

Easily configurable and powerful email system with templates and email queuing.


[![Mr PHP](https://raw.github.com/cornernote/mrphp-assets/master/img/code-banner.png)](http://mrphp.com.au) [![Project Stats](https://www.ohloh.net/p/yii-email-module/widgets/project_thin_badge.gif)](https://www.ohloh.net/p/yii-email-module)

[![Latest Stable Version](https://poser.pugx.org/cornernote/yii-email-module/v/stable.png)](https://packagist.org/packages/cornernote/yii-email-module) [![Build Status](https://travis-ci.org/cornernote/yii-email-module.png?branch=master)](https://travis-ci.org/cornernote/yii-email-module)


### Contents

[Features](#features)  
[Screenshots](#screenshots)  
[Installation](#installation)  
[Configuration](#configuration)  
[Usage](#usage)  
[License](#license)  
[Links](#links)  


## Features

- Emails can be sent directly, or saved into a spool to be sent later.
- Templates can be normal php files, or stored in the database and rendered with Mustache
- Emails are built and send using SwiftMailer


## Screenshots

...coming soon...


## Installation

Please download using ONE of the following methods:


### Composer Installation

```
curl http://getcomposer.org/installer | php
php composer.phar require cornernote/yii-email-module
```


### Manual Installation

Download the [latest version](https://github.com/cornernote/yii-email-module/archive/master.zip) and move the `email` folder into your `protected/modules` folder.



## Configuration

Add yii-email-module to the `modules` in your yii configuration:

```php
return array(
	'modules' => array(
		'email' => array(
			// path to the EmailModule class
			'class' => 'vendor.cornernote.yii-email-module.email.EmailModule',
			// if you downloaded into modules
			//'class' => 'application.modules.email.EmailModule',

			// add a list of users who can access the email module
			'adminUsers' => array('admin'),

			// set this to false in production to improve performance
			'autoCreateTables' => true,
		),
	),
);
```

Add swiftMailer to the `aliases` in your yii configuration:

```php
return array(
	'aliases' => array(
        // path to the SwiftMailer lib folder
        'swiftMailer' => '/path/to/vendor/swiftmailer/swiftmailer/lib',
	),
);
```

Add `EmailManager` to the `components` section in your yii configuration:

```php
return array(
	'components' => array(
		'emailManager' => array(
			// path to the EmailManager class
			'class' => 'email.components.EmailManager',

			// set this to false in production to improve performance
			'fromEmail' => 'webmaster@your.dom.ain',

			// set this to false in production to improve performance
			'fromName' => 'Your Name',

			// can be one of: php, db
			'templateType' => 'php',

			// when templateType=php this is the path to the email views
			// you may copy the default templates from email/views/emails
			'templatePath' => 'application.views.emails',
		),
	),
);
```


## Usage


### Simple Usage

```php
Yii::app()->emailManager->email('user@dom.ain', 'test email', '<b>Hello</b> <i>World<i>!');
```

### Using Templates

To send more complex emails you will need to use Email Templates.

Create a new component in `components/Email.php`:

```php
class Email {
	public static function sendUserWelcome($user) {
		$emailManager = Yii::app()->emailManager;

		// build the templates
		$template = 'user_welcome';
		$message = $emailManager->buildTemplateMessage($template, array(
			'user' => $user,
		));

		// get the message
		$swiftMessage = Swift_Message::newInstance($message['subject']);
		$swiftMessage->setBody($message['message'], 'text/html');
		//$swiftMessage->addPart($message['text'], 'text/plain');
		$swiftMessage->setFrom($emailManager->fromEmail, $emailManager->fromName);
		$swiftMessage->setTo($user->email, $user->name);

		// spool the email
		$emailSpool = $emailManager->getEmailSpool($swiftMessage, $user);
		$emailSpool->priority = 10;
		$emailSpool->template = $template;
		return $emailSpool->save(false);

		// or send the email
		//return Swift_Mailer::newInstance(Swift_MailTransport::newInstance())->send($swiftMessage);
	}
}
```

Create a view for the subject in `views/emails/example/subject.php`:
```php
<?php echo 'hello' . $user->name;
```

Create a view for the heading in `views/emails/example/heading.php`:
```php
<?php echo 'Hi there ' . $user->name . ', Welcome to '. Yii::app()->name;
```

Create a view for the message in `views/emails/example/message.php`:
```php
<?php echo 'Here is an <b>awesome</b> email!';
```

Now you can send an email like this:

```php
$user = User::model()->findByPk(123);
Email::sendUserWelcome($user);
```

### Sending Spooled Emails

Create a new yiic command in `commands/EmailSpoolCommand.php`:

```php
class EmailSpoolCommand extends CConsoleCommand {
	public function actionIndex() {
		Yii::app()->emailManager->processSpool();
	}
}
```

Now you can send the spooled emails like this:

```
yiic emailSpool
```


## License

- Author: Brett O'Donnell <cornernote@gmail.com>
- Author: Zain Ul abidin <zainengineer@gmail.com>
- Source Code: https://github.com/cornernote/yii-email-module
- Copyright © 2013 Mr PHP <info@mrphp.com.au>
- License: BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE


## Links

- [Yii Extension](http://www.yiiframework.com/extension/yii-email-module)
- [Composer Package](https://packagist.org/packages/cornernote/yii-email-module)
- [MrPHP](http://mrphp.com.au)

