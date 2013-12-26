<?php

/**
 * EmailSpoolController
 *
 * @method EmailSpool loadModel() loadModel($id, $model = null)
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */
class EmailSpoolController extends EmailWebController
{

    /**
     * @param string $view the view to be rendered
     * @return bool
     */
    public function beforeRender($view)
    {
        if (!parent::beforeRender($view))
            return false;
        if ($view != 'index')
            $this->addBreadcrumb(Yii::t('email', 'Spools'), Yii::app()->user->getState('index.emailSpool', array('spool/index')));

        return true;
    }

    /**
     * Index
     */
    public function actionIndex()
    {
        $emailSpool = new EmailSpool('search');
        if (!empty($_GET['EmailSpool']))
            $emailSpool->attributes = $_GET['EmailSpool'];

        $this->render('index', array(
            'emailSpool' => $emailSpool,
        ));
    }

    /**
     * View
     * @param $id
     */
    public function actionView($id)
    {
        $emailSpool = $this->loadModel($id);

        $this->render('view', array(
            'emailSpool' => $emailSpool,
        ));
    }

    /**
     * Preview
     * @param $id
     */
    public function actionPreview($id)
    {
        $emailSpool = $this->loadModel($id);

        echo $emailSpool->swiftMessage->getBody();
    }

}
