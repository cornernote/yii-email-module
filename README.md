# Yii Email Module

Easily configurable and powerful email system with templates and email queuing.


### Contents

- [Features](#features)
- [Screenshots](#screenshots)
- [Installation](#installation)
	- [Composer Installation](#composer-installation)
	- [Manual Installation](#manual-installation)
- [Configuration](#configuration)
- [Usage](#usage)
	- [Simple Usage](#simple-usage)
	- [Using Templates](#using-templates)
		- [Component](#component)
		- [PHP Templates](#php-templates)
		- [DB Templates](#db-templates)
		- [Sending the Email](#sending-the-email)
	- [Sending Spooled Emails](#sending-spooled-emails)
	- [Automatically Sending](#automatically-sending)
	- [Extending EEmailManager](#extending-eemailmanager)
- [FAQ](#faq)
	- [What is the difference between a template and a layout?](#what-is-the-difference-between-a-template-and-a-layout)
	- [Do layout variables subject, heading and message need to be defined when calling buildTemplateMessage() function?](#do-layout-variables-subject-heading-and-message-need-to-be-defined-when-calling-buildtemplatemessage-function)
	- [Why does HTML code get replaced htmlencoded output when using db templates rendered with Mustache?](#why-does-html-code-get-replaced-htmlencoded-output-when-using-db-templates-rendered-with-mustache)
- [Resources](#resources)
- [License](#license)


## Features

- Emails can be sent directly, or saved into a spool to be sent later.
- Templates can be normal php files, or stored in the database and rendered with Mustache
- Emails are built and send using SwiftMailer


## Screenshots

Yii Email Module Homepage:
![home](https://raw.github.com/cornernote/yii-email-module/master/screenshot/home.png)

Spool List
![Requests](https://raw.github.com/cornernote/yii-email-module/master/screenshot/spools.png)

Spool View
![Request](https://raw.github.com/cornernote/yii-email-module/master/screenshot/spool.png)

Spool Preview
![Request](https://raw.github.com/cornernote/yii-email-module/master/screenshot/spool-preview.png)

Template List
![Requests](https://raw.github.com/cornernote/yii-email-module/master/screenshot/templates.png)

Template View
![Request](https://raw.github.com/cornernote/yii-email-module/master/screenshot/template.png)

Template Preview
![Request](https://raw.github.com/cornernote/yii-email-module/master/screenshot/template-preview.png)


## Installation

Please download using ONE of the following methods:


### Composer Installation

All requirements are automatically downloaded into the correct location when using composer.  There is no need to download additional files or set paths to third party files.

Get composer:

```
curl http://getcomposer.org/installer | php
```

Install latest release OR development version:

```
php composer.phar require cornernote/yii-email-module:*           // latest release
php composer.phar require cornernote/yii-email-module:dev-master  // development version
```

Add the `vendor` folder to the `aliases` in your yii configuration:

```php
return array(
	'aliases' => array(
		'vendor' => '/path/to/vendor',
	),
);
```


### Manual Installation

Download the [latest version](https://github.com/cornernote/yii-email-module/archive/master.zip) and move the `email` folder into your `protected/modules` folder.

In addition the following are required:
* [YiiStrap](http://www.getyiistrap.com) for the interface elements.  Please follow their Getting Started guide to setup the aliases and components for your application.
* [SwiftMailer](http://swiftmailer.org/) to send emails.  Please download and setup the swiftMailerPath as per the Configuration below.
* [MustachePHP](https://github.com/bobthecow/mustache.php) to render database templates.  Please download and setup the mustachePath as per the Configuration below.


## Configuration

Add yii-email-module to the `modules` in your yii configuration:

```php
return array(
	'modules' => array(
		'email' => array(
			// path to the EmailModule class
			'class' => '/path/to/vendor/cornernote/yii-email-module/email/EmailModule',

			// add a list of users who can access the email module
			'adminUsers' => array('admin'),

			// ID of the CDbConnection application component. If not set,
			// a SQLite3 database will be automatically created and used.
			'connectionID' => 'db',

			// set this to false in production to improve performance
			'autoCreateTables' => true,

			// this is only required if you do not want YiiStrap in your app config
			// for example, if you are running YiiBooster
			'yiiStrapPath' => '/path/to/yiistrap',
		),
	),
);
```

Add `EEmailManager` to the `components` section in your yii configuration:

```php
return array(
	'components' => array(
		'emailManager' => array(
			// path to the EEmailManager class
			'class' => 'email.components.EEmailManager',

			// path to the SwiftMailer lib folder
			'swiftMailerPath' => '/path/to/vendor/swiftmailer/swiftmailer/lib',

			// path to the Mustache src folder
			'mustachePath' => '/path/to/vendor/mustache/mustache/src',

			// set this to false in production to improve performance
			'fromEmail' => 'webmaster@your.dom.ain',

			// set this to false in production to improve performance
			'fromName' => 'Your Name',

			// can be one of: php, db
			'templateType' => 'php',

			// when templateType=php this is the path to the email views
			// you may copy the default templates from email/views/emails
			'templatePath' => 'application.views.emails',

			// the default transport to use, for this example you can use "mail" or "smtp"
			// see below for defining new transports
			'defaultTransport' => 'mail',

			// email transport methods
			'transports' => array(

				// mail transport
				'mail' => array(
					// can be Swift_MailTransport or Swift_SmtpTransport
					'class' => 'Swift_MailTransport',
				),

				// smtp transport
				'smtp' => array(
					// if you use smtp you may need to define the host, port, security and setters
					'class' => 'Swift_SmtpTransport',
					'host' => 'localhost',
					'port' => 25,
					'security' => null,
					'setters' => array(
						'username' => 'your_username',
						'password' => 'your_password',
					),
				),
			),

		),
	),
);
```

To use the commands that are included with yii-email-manager, add the following to the `commandMap` section of your yiic configuration:

```php
return array(
	'commandMap' => array(
		'emailSpool' => 'email.commands.EmailSpoolCommand',
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


#### Component

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
		$emailSpool->transport = 'smtp'; // send using smtp
		return $emailSpool->save(false);

		// or send the email
		//return echo Yii::app()->emailManager->deliver($swiftMessage, 'mySmtpTransport');
	}
}
```


#### PHP Templates

Subject `views/emails/example/subject.php`:
```php
<?php echo 'hello' . $user->name;
```

Heading `views/emails/example/heading.php`:
```php
<?php echo 'Hi there ' . $user->name . ', Welcome to '. Yii::app()->name;
```

Message `views/emails/example/message.php`:
```php
<?php echo 'Here is an <b>awesome</b> email!';
```


#### DB Templates

Subject
```
hello {{user.name}}
```

Heading
```
Hi there {{user.name}}, Welcome to {{appName}}
```

Message
```
Here is an <b>awesome</b> email!
```


#### Sending the Email

Now you can send an email like this:

```php
$user = User::model()->findByPk(123);
Email::sendUserWelcome($user);
```


### Sending Spooled Emails

You can send the spooled emails using the yiic command:

```
yiic emailSpool
```


### Automatically Sending

Setup [lockrun](https://github.com/pushcx/lockrun) for overlap protection.  This allows us to setup a cron job that will run every minute, with no risk of a new process starting if an existing process is running.

Add the following to your crontab:
```
* * * * * /usr/local/bin/lockrun --idempotent --lockfile=/path/to/app/runtime/emailSpool.lock -- /path/to/yiic emailSpool loop > /dev/null 2>&1
```


### Extending EEmailManager

Before extending you should check the available configuration options.  In many occasions you can configure the `EEmailManager` class to behave as you require.

If you wish to extend for more complex functionality, you can simply update the class path in your yii config to point to your own `EmailManager` class, all the other configuration options will still be available:

```php
return array(
	'components' => array(
		'emailManager' => array(
			// path to the EmailManager class
			'class' => 'application.components.EmailManager',
		),
	),
),
```

For example, to make the app name available to all templates and layouts, you can override `buildTemplateMessage()`:

```php
class EmailManager extends EEmailManager {
	public function buildTemplateMessage($template, $viewParams = array(), $layout = 'layout_default') {
		$viewParams['appName'] = Yii::app()->name;
		return parent::buildTemplateMessage($template, $viewParams, $layout);
	}
}
```


## FAQ


### What is the difference between a template and a layout?

Each of the **template** parts is rendered (`subject`, `heading` and `message`), and then those parts become variables in the **layout**.  

This allows features such as:
- Consistent pretty message layouts
- Your templates remain light with no need for any layout code
- Easily change email layouts, for example, send the same email template in December with an xmas layout
- Ability to prepend/append to the subject or heading at a more global level (for example if you want your site name in every email subject, you can set your layout subject to "{{subject}} - My Awesome Site"

For an example, let's take a look at a layout and template for a `subject` part:

**TEMPLATE SUBJECT**
```
Welcome {{user.username}}
```

**LAYOUT SUBJECT**
```
{{subject}} - My Awesome Site
```

**GENERATED SUBJECT**
```
Welcome cornernote - My Awesome Site
```

As you can see, the `subject` in the **layout** gets replaced by the parsed `subject` from the **template**.


### Do layout variables `subject`, `heading` and `message` need to be defined when calling `buildTemplateMessage()` function?

No, these variables will be created internally based on your the 3 parts of the **template** (`subject`, `heading` and `message`), which will be passed into the **layout**.


### Why does HTML code get replaced htmlencoded output when using db templates rendered with Mustache?

Mustache replaces `{{variables}}` with the htmlencoded value of the variable, unless you use `{{{tripple_culry_braces}}}`.  
- `{{double_curly_braces}}` will be htmlencoded
- `{{{tripple_culry_braces}}}` will allow html


## Resources

[![Mr PHP](https://raw.github.com/cornernote/mrphp-assets/master/img/code-banner.png)](http://mrphp.com.au) [![Github Project](https://raw.github.com/cornernote/mrphp-assets/master/vendor/github/github-latest-sourcecode-16.png)](https://github.com/cornernote/yii-email-module#yii-email-module) [![Yii Extension](https://raw.github.com/cornernote/mrphp-assets/master/vendor/yii/yii-extension-16.png)](http://www.yiiframework.com/extension/yii-email-module) [![Project Stats](https://www.ohloh.net/p/yii-email-module/widgets/project_thin_badge.gif)](https://www.ohloh.net/p/yii-email-module)

[![Latest Stable Version](https://poser.pugx.org/cornernote/yii-email-module/v/stable.png)](https://packagist.org/packages/cornernote/yii-email-module) [![Build Status](https://travis-ci.org/cornernote/yii-email-module.png?branch=master)](https://travis-ci.org/cornernote/yii-email-module) [![Dependencies Check](https://depending.in/cornernote/yii-email-module.png)](https://depending.in/cornernote/yii-email-module)

- [YiiStrap](http://getyiistrap.com/)
- [SwiftMailer](http://swiftmailer.org/)
- [MustachePHP](https://github.com/bobthecow/mustache.php)
- [Lockrun](https://github.com/pushcx/lockrun)


## License

[BSD-3-Clause](https://raw.github.com/cornernote/yii-email-module/master/LICENSE), Copyright © 2013-2014 [Mr PHP](mailto:info@mrphp.com.au)
