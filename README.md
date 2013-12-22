# Yii Email Module

Easily configurable and powerful email system with templates and email queuing.


### Contents

[Features](#features)  
[Screenshots](#screenshots)  
[Installation](#installation)  
[Configuration](#configuration)  
[Usage](#usage)  
[License](#license)  
[Links](#links)  


## Features

- email spool
- email templates, php/db
- built ontop of swiftmailer


## Screenshots


## Installation

Please download using ONE of the following methods:


### Composer Installation

```
curl http://getcomposer.org/installer | php
php composer.phar require mrphp/yii-email-module
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
			'class' => 'vendor.mrphp.yii-email-module.email.EmailModule',
			// if you downloaded into modules
			//'class' => 'application.modules.email.EmailModule',

			// add a list of users who can access the audit module
			'adminUsers' => array('admin'),

			// set this to false in production to improve performance
			'autoCreateTables' => true,
		),
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
		),
	),
);
```


## Usage

Send an email:

```php
Yii::app()->emailManager->email('user@dom.ain', 'test email', '<b>Hello</b> <i>World<i>!');
```

Build and send an email deom a template:

```php
class Email
{
	public static function sendUserWelcome($user)
	{
		$emailManager = Yii::app()->emailManager;

		// build the templates
		$template = 'user_welcome';
		$message = $emailManager->buildTemplateMessage($template, array(
			'user' => $user,
			'url' => $url,
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

Then call it like this:

```php
$user = User::model()->findByPk(123);
Email::sendUserWelcome($user);
```


## License

- Author: Brett O'Donnell <cornernote@gmail.com>
- Author: Zain Ul abidin <zainengineer@gmail.com>
- Source Code: https://github.com/cornernote/yii-email-module
- Copyright © 2013 Mr PHP <info@mrphp.com.au>
- License: BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE


## Links

- [Yii Extension](http://www.yiiframework.com/extension/yii-email-module)
- [Composer Package](https://packagist.org/packages/mrphp/yii-email-module)
- [MrPHP](http://mrphp.com.au)

