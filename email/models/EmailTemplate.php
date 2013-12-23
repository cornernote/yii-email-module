<?php

/**
 * EmailTemplate
 *
 * --- BEGIN ModelDoc ---
 *
 * Table email_template
 * @property integer $id
 * @property string $name
 * @property string $subject
 * @property string $heading
 * @property string $message
 *
 * @see CActiveRecord
 * @method EmailTemplate find() find($condition, array $params = array())
 * @method EmailTemplate findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method EmailTemplate findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method EmailTemplate findBySql() findBySql($sql, array $params = array())
 * @method EmailTemplate[] findAll() findAll($condition = '', array $params = array())
 * @method EmailTemplate[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method EmailTemplate[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method EmailTemplate[] findAllBySql() findAllBySql($sql, array $params = array())
 * @method EmailTemplate with() with()
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
class EmailTemplate extends EmailActiveRecord
{

    /**
     * @param string $className
     * @return EmailTemplate
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
            'name' => Yii::t('email', 'Name'),
            'subject' => Yii::t('email', 'Subject'),
            'heading' => Yii::t('email', 'Heading'),
            'message' => Yii::t('email', 'Message'),
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        $rules = array();
        if ($this->scenario == 'search') {
            $rules[] = array('id, name, subject, heading, message', 'safe');
        }
        if (in_array($this->scenario, array('create', 'update'))) {
            $rules[] = array('name, subject, heading, message', 'required');
            $rules[] = array('name, subject', 'length', 'max' => 255);
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
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('t.subject', $this->subject, true);
        $criteria->compare('t.heading', $this->heading, true);
        $criteria->compare('t.message', $this->message, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

}