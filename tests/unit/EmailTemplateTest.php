<?php
/**
 * EmailTemplateTest
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

class EmailTemplateTest extends \Codeception\TestCase\Test
{
    /**
     * @var \CodeGuy
     */
    protected $codeGuy;

    protected $emailTemplate;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testMe()
    {
        $this->create();
        $this->find();
        $this->update();
        $this->search();
    }

    protected function create()
    {
        $emailTemplate = new EmailTemplate('create');
        $emailTemplate->name = 'the name';
        $emailTemplate->subject = 'the subject';
        $emailTemplate->heading = 'the heading';
        $emailTemplate->message = 'the message';
        $save = $emailTemplate->save();
        $this->emailTemplate = $emailTemplate;
        $this->assertTrue($save);
        $this->codeGuy->seeInDatabase('email_template', array(
            'name' => 'the name',
            'subject' => 'the subject',
            'heading' => 'the heading',
            'message' => 'the message',
        ));
    }

    public function find()
    {
        $emailTemplate = EmailTemplate::model()->findByPk($this->emailTemplate->id);
        $this->assertEquals($emailTemplate->name, 'the name');
        $this->assertEquals($emailTemplate->subject, 'the subject');
        $this->assertEquals($emailTemplate->heading, 'the heading');
        $this->assertEquals($emailTemplate->message, 'the message');
    }

    public function update()
    {
        $emailTemplate = EmailTemplate::model()->findByPk($this->emailTemplate->id);
        $emailTemplate->name = 'the name changed';
        $emailTemplate->subject = 'the subject changed';
        $emailTemplate->heading = 'the heading changed';
        $emailTemplate->message = 'the message changed';
        $save = $emailTemplate->save();
        $this->assertTrue($save);
        $this->codeGuy->seeInDatabase('email_template', array(
            'name' => 'the name changed',
            'subject' => 'the subject changed',
            'heading' => 'the heading changed',
            'message' => 'the message changed',
        ));
    }

    public function search()
    {
        $emailTemplate = new EmailTemplate('search');
        $emailTemplate->name = 'the name changed';
        $dataProvider = $emailTemplate->search();
        $data = $dataProvider->getData();
        $this->assertEquals($data[0]->name, 'the name changed');
        $this->assertEquals($data[0]->subject, 'the subject changed');
        $this->assertEquals($data[0]->heading, 'the heading changed');
        $this->assertEquals($data[0]->message, 'the message changed');
    }

}