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
 * @property string $to_address
 * @property string $from_address
 * @property string $subject
 * @property string $message
 * @property integer $sent
 * @property integer $created
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
 * @property Swift_Message $swiftMessage
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
     * @param string $className
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
            'to_address' => Yii::t('email', 'To Address'),
            'from_address' => Yii::t('email', 'From Address'),
            'subject' => Yii::t('email', 'Subject'),
            'message' => Yii::t('email', 'Message'),
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
            $rules[] = array('id, transport, template, priority, status, model_name, model_id, to_address, from_address, subject, message, sent, created', 'safe');
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
        $criteria->compare('t.to_address', $this->to_address, true);
        $criteria->compare('t.from_address', $this->from_address, true);
        $criteria->compare('t.subject', $this->subject, true);
        $criteria->compare('t.message', $this->message, true);
        $criteria->compare('t.sent', $this->sent);
        $criteria->compare('t.created', $this->created);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

    /**
     * @param $value mixed
     * @return string
     */
    public static function pack($value)
    {
        return gzcompress(serialize($value));
    }

    /**
     * @param $value string
     * @return mixed
     */
    public static function unpack($value)
    {
        return unserialize(gzuncompress($value));
    }

    /**
     * @return Swift_Message
     */
    public function getSwiftMessage()
    {
        Yii::app()->getComponent('emailManager');
        return $this->unpack($this->message);
    }

}