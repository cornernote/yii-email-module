<?php

/**
 * EmailTemplateController
 *
 * @method EmailTemplate loadModel() loadModel($id, $model = null)
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */
class EmailTemplateController extends EmailWebController
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
            $this->addBreadcrumb(Yii::t('email', 'Templates'), Yii::app()->user->getState('index.emailTemplate', array('template/index')));

        return true;
    }

    /**
     * Index
     */
    public function actionIndex()
    {
        $emailTemplate = new EmailTemplate('search');
        if (!empty($_GET['EmailTemplate']))
            $emailTemplate->attributes = $_GET['EmailTemplate'];

        $this->render('index', array(
            'emailTemplate' => $emailTemplate,
        ));
    }

    /**
     * View
     * @param $id
     */
    public function actionView($id)
    {
        $emailTemplate = $this->loadModel($id);

        $this->render('view', array(
            'emailTemplate' => $emailTemplate,
        ));
    }

    /**
     * Preview
     * @param $id
     */
    public function actionPreview($id)
    {
        $emailTemplate = $this->loadModel($id);

        echo $emailTemplate->message;
    }

    /**
     * Create
     */
    public function actionCreate()
    {
        $emailTemplate = new EmailTemplate('create');

        if (isset($_POST['EmailTemplate'])) {
            $emailTemplate->attributes = $_POST['EmailTemplate'];
            if ($emailTemplate->save()) {
                $this->redirect(array('template/view', 'id' => $emailTemplate->id));
            }
        }

        $this->render('create', array(
            'emailTemplate' => $emailTemplate,
        ));
    }

    /**
     * Update
     * @param $id
     */
    public function actionUpdate($id)
    {
        $emailTemplate = $this->loadModel($id);

        if (isset($_POST['EmailTemplate'])) {
            $emailTemplate->attributes = $_POST['EmailTemplate'];
            if ($emailTemplate->save()) {
                $this->redirect(array('template/view', 'id' => $emailTemplate->id));
            }
        }

        $this->render('update', array(
            'emailTemplate' => $emailTemplate,
        ));
    }

    /**
     * Delete
     * @param $id
     */
    public function actionDelete($id)
    {
        $emailTemplate = $this->loadModel($id);
        $emailTemplate->delete();
        $this->redirect(Yii::app()->user->getState('index.emailTemplate', array('template/index')));
    }

}
