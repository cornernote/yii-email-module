<?php

/**
 * EmailToken
 *
 * --- BEGIN ModelDoc ---
 *
 * Table email_token
 * @property integer $id
 * @property string $token
 * @property string $model_name
 * @property integer $model_id
 * @property integer $uses_allowed
 * @property integer $uses_remaining
 * @property integer $expires
 * @property integer $created
 *
 * @see CActiveRecord
 * @method EmailToken find() find($condition, array $params = array())
 * @method EmailToken findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method EmailToken findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method EmailToken findBySql() findBySql($sql, array $params = array())
 * @method EmailToken[] findAll() findAll($condition = '', array $params = array())
 * @method EmailToken[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method EmailToken[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method EmailToken[] findAllBySql() findAllBySql($sql, array $params = array())
 * @method EmailToken with() with()
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
class EmailToken extends EmailActiveRecord
{

    /**
     * @param string $className
     * @return EmailToken
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
            'token' => Yii::t('email', 'Token'),
            'model_name' => Yii::t('email', 'Model Name'),
            'model_id' => Yii::t('email', 'Model ID'),
            'uses_allowed' => Yii::t('email', 'Uses Allowed'),
            'uses_remaining' => Yii::t('email', 'Uses Remaining'),
            'expires' => Yii::t('email', 'Expires'),
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
            $rules[] = array('id, token, model_name, model_id, uses_allowed, uses_remaining, expires, created', 'safe');
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
        $criteria->compare('t.token', $this->token, true);
        $criteria->compare('t.model_name', $this->model_name, true);
        $criteria->compare('t.model_id', $this->model_id);
        $criteria->compare('t.uses_allowed', $this->uses_allowed);
        $criteria->compare('t.uses_remaining', $this->uses_remaining);
        $criteria->compare('t.expires', $this->expires);
        $criteria->compare('t.created', $this->created);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

    /**
     * @param $expires
     * @param $uses_allowed
     * @param $model_name
     * @param $model_id
     * @return string
     */
    public function add($expires, $uses_allowed, $model_name, $model_id)
    {
        $token = md5($this->hashToken(uniqid(true)));
        $emailToken = new EmailToken();
        $emailToken->token = $this->hashToken($token);
        $emailToken->uses_allowed = $uses_allowed;
        $emailToken->uses_remaining = $uses_allowed;
        $emailToken->expires = $expires;
        $emailToken->model_name = $model_name;
        $emailToken->model_id = $model_id;
        $emailToken->created = date('Y-m-d H:i:s');
        return $token;
    }

    /**
     * @param $model_name
     * @param $model_id
     * @param $plain
     * @return EmailToken
     */
    public function checkToken($model_name, $model_id, $plain)
    {
        // check for valid token
        $token = self::model()->find("model_name=:model_name AND model_id=:model_id ORDER BY t.created DESC, t.id DESC", array(
            ':model_name' => $model_name,
            ':model_id' => $model_id,
        ));
        if (!$token) {
            $this->addError('token', Yii::t('email', 'missing token'));
            return false;
        }
        // check uses remaining
        if ($token->uses_remaining <= 0) {
            $this->addError('token', Yii::t('email', 'no uses remaining'));
            return false;
        }
        // check expires
        if (strtotime($token->expires) <= time()) {
            $this->addError('token', Yii::t('email', 'token has expired'));
            return false;
        }
        // check token plain
        if (!$token->validateToken($plain)) {
            $this->addError('token', Yii::t('email', 'token is invalid'));
            return false;
        }
        return $token;
    }

    /**
     * @param $model_name
     * @param $model_id
     * @param $plain
     * @return bool
     */
    public function useToken($model_name, $model_id, $plain)
    {
        $token = $this->checkToken($model_name, $model_id, $plain);
        if (!$token) {
            return false;
        }
        // deduct from uses remaining
        $token->uses_remaining--;
        $token->save(false);
        return true;
    }

    /**
     * @param $plain
     * @param null $encrypted
     * @return boolean validates a token
     */
    public function validateToken($plain, $encrypted = null)
    {
        $encrypted = $encrypted ? $encrypted : $this->token;
        if (!$plain || !$encrypted) {
            return false;
        }
        return CPasswordHelper::verifyPassword($plain, $encrypted);
    }

    /**
     * @param $plain
     * @return string creates a token hash
     */
    public function hashToken($plain)
    {
        return CPasswordHelper::hashPassword($plain);
    }

}