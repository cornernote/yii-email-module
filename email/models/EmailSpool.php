<?php

/**
 * EmailSpool
 *
 * --- BEGIN ModelDoc ---
 *
 * Table email_spool
 * @property integer $id
 * @property string $transport
 * @property string $template
 * @property integer $priority
 * @property string $status
 * @property string $model_name
 * @property integer $model_id
 * @property string $to_email
 * @property string $to_name
 * @property string $from_email
 * @property string $from_name
 * @property string $message_subject
 * @property string $message_html
 * @property string $message_text
 * @property string $attachments
 * @property integer $sent
 * @property integer $created
 *
 * Relations
 * @property EmailAttachment[] $emailAttachment
 *
 * @see CActiveRecord
 * @method EmailSpool find() find($condition, array $params = array())
 * @method EmailSpool findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method EmailSpool findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method EmailSpool findBySql() findBySql($sql, array $params = array())
 * @method EmailSpool[] findAll() findAll($condition = '', array $params = array())
 * @method EmailSpool[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method EmailSpool[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method EmailSpool[] findAllBySql() findAllBySql($sql, array $params = array())
 * @method EmailSpool with() with()
 *
 * --- END ModelDoc ---
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */
class EmailSpool extends EmailActiveRecord
{

    /**
     * @return EmailSpool
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('email', 'ID'),
            'transport' => Yii::t('email', 'Transport'),
            'template' => Yii::t('email', 'Template'),
            'priority' => Yii::t('email', 'Priority'),
            'status' => Yii::t('email', 'Status'),
            'model_name' => Yii::t('email', 'Model Name'),
            'model_id' => Yii::t('email', 'Model ID'),
            'to_email' => Yii::t('email', 'To Email'),
            'to_name' => Yii::t('email', 'To Name'),
            'from_email' => Yii::t('email', 'From Email'),
            'from_name' => Yii::t('email', 'From Name'),
            'message_subject' => Yii::t('email', 'Message Subject'),
            'message_html' => Yii::t('email', 'Message Html'),
            'message_text' => Yii::t('email', 'Message Text'),
            'attachments' => Yii::t('email', 'Attachments'),
            'sent' => Yii::t('email', 'Sent'),
            'created' => Yii::t('email', 'Created'),
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        $rules = array();
        if ($this->scenario == 'search') {
            $rules[] = array('id, transport, template, priority, status, model_name, model_id, to_email, to_name, from_email, from_name, message_subject, message_html, message_text, attachments, sent, created', 'safe');
        }
        return $rules;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.transport', $this->transport, true);
        $criteria->compare('t.template', $this->template, true);
        $criteria->compare('t.priority', $this->priority);
        $criteria->compare('t.status', $this->status, true);
        $criteria->compare('t.model_name', $this->model_name, true);
        $criteria->compare('t.model_id', $this->model_id);
        $criteria->compare('t.to_email', $this->to_email, true);
        $criteria->compare('t.to_name', $this->to_name, true);
        $criteria->compare('t.from_email', $this->from_email, true);
        $criteria->compare('t.from_name', $this->from_name, true);
        $criteria->compare('t.message_subject', $this->message_subject, true);
        $criteria->compare('t.message_html', $this->message_html, true);
        $criteria->compare('t.message_text', $this->message_text, true);
        $criteria->compare('t.attachments', $this->attachments, true);
        $criteria->compare('t.sent', $this->sent);
        $criteria->compare('t.created', $this->created);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

}