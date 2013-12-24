<?php
/**
 * EmailManagerTest
 *
 * @var $scenario \Codeception\Scenario
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */

use Codeception\Util\Stub;

class EmailManagerTest extends \Codeception\TestCase\Test
{
    /**
     * @var \CodeGuy
     */
    protected $codeGuy;

    protected $emailSpool;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testMe()
    {
        $this->pack();
        $this->send();
    }

    protected function pack()
    {
        $string = 'hello world!';
        $this->assertEquals(EmailSpool::unpack(EmailSpool::pack($string)), $string);
    }

    protected function send()
    {
        /** @var EmailManager $emailManager */
        $emailManager = Yii::app()->emailManager;
        $emailManager->email($emailManager->fromEmail, 'EmailManager subject', 'EmailManager message');
        $this->codeGuy->seeInDatabase('email_spool', array(
            'subject' => 'EmailManager subject',
            //'message' => EmailSpool::pack('EmailManager message'),
        ));
        $this->assertEquals(EmailSpool::unpack(EmailSpool::pack('EmailManager message')), 'EmailManager message');
    }

}