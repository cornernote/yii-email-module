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

class EmailSpoolTest extends \Codeception\TestCase\Test
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
        $this->create();
        $this->find();
        $this->update();
        $this->search();
    }

    protected function create()
    {
        $date = time();
        $emailSpool = new EmailSpool('create');
        $emailSpool->transport = 'the transport';
        $emailSpool->template = 'the template';
        $emailSpool->priority = 99;
        $emailSpool->status = 'the status';
        $emailSpool->model_name = 'the model_name';
        $emailSpool->model_id = 'the model_id';
        $emailSpool->to_address = 'the to_address';
        $emailSpool->from_address = 'the from_address';
        $emailSpool->subject = 'the subject';
        $emailSpool->message = 'the message';
        $emailSpool->sent = $date;
        $emailSpool->created = $date;
        $save = $emailSpool->save();
        $this->emailSpool = $emailSpool;
        $this->assertTrue($save);
        $this->codeGuy->seeInDatabase('email_spool', array(
            'transport' => 'the transport',
            'template' => 'the template',
            'priority' => 99,
            'status' => 'the status',
            'model_name' => 'the model_name',
            'model_id' => 'the model_id',
            'to_address' => 'the to_address',
            'from_address' => 'the from_address',
            'subject' => 'the subject',
            'message' => 'the message',
            'sent' => $date,
            'created' => $date,
        ));
    }

    protected function find()
    {
        $emailSpool = EmailSpool::model()->findByPk($this->emailSpool->id);
        $this->assertEquals($emailSpool->transport, 'the transport');
        $this->assertEquals($emailSpool->template, 'the template');
        $this->assertEquals($emailSpool->priority, 99);
        $this->assertEquals($emailSpool->status, 'the status');
        $this->assertEquals($emailSpool->model_name, 'the model_name');
        $this->assertEquals($emailSpool->model_id, 'the model_id');
        $this->assertEquals($emailSpool->to_address, 'the to_address');
        $this->assertEquals($emailSpool->from_address, 'the from_address');
        $this->assertEquals($emailSpool->subject, 'the subject');
        $this->assertEquals($emailSpool->message, 'the message');
    }

    protected function update()
    {
        $date = time();
        $emailSpool = EmailSpool::model()->findByPk($this->emailSpool->id);
        $emailSpool->transport = 'the transport changed';
        $emailSpool->template = 'the template changed';
        $emailSpool->priority = 100;
        $emailSpool->status = 'the status changed';
        $emailSpool->model_name = 'the model_name changed';
        $emailSpool->model_id = 'the model_id changed';
        $emailSpool->to_address = 'the to_address changed';
        $emailSpool->from_address = 'the from_address changed';
        $emailSpool->subject = 'the subject changed';
        $emailSpool->message = 'the message changed';
        $emailSpool->sent = $date;
        $emailSpool->created = $date;
        $save = $emailSpool->save();
        $this->assertTrue($save);
        $this->codeGuy->seeInDatabase('email_spool', array(
            'transport' => 'the transport changed',
            'template' => 'the template changed',
            'priority' => 100,
            'status' => 'the status changed',
            'model_name' => 'the model_name changed',
            'model_id' => 'the model_id changed',
            'to_address' => 'the to_address changed',
            'from_address' => 'the from_address changed',
            'subject' => 'the subject changed',
            'message' => 'the message changed',
            'sent' => $date,
            'created' => $date,
        ));
    }

    protected function search()
    {
        $emailSpool = new EmailSpool('search');
        $emailSpool->transport = 'the transport changed';
        $dataProvider = $emailSpool->search();
        $data = $dataProvider->getData();
        $this->assertEquals($data[0]->transport, 'the transport changed');
        $this->assertEquals($data[0]->template, 'the template changed');
        $this->assertEquals($data[0]->priority, 100);
        $this->assertEquals($data[0]->status, 'the status changed');
        $this->assertEquals($data[0]->model_name, 'the model_name changed');
        $this->assertEquals($data[0]->model_id, 'the model_id changed');
        $this->assertEquals($data[0]->to_address, 'the to_address changed');
        $this->assertEquals($data[0]->from_address, 'the from_address changed');
        $this->assertEquals($data[0]->subject, 'the subject changed');
        $this->assertEquals($data[0]->message, 'the message changed');
    }

}