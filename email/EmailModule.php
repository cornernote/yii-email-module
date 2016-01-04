<?php

/**
 * EmailModule
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */
class EmailModule extends CWebModule
{
    /**
     * @var string The ID of the CDbConnection application component. If not set, a SQLite3
     * database will be automatically created in <code>protected/runtime/email-EmailVersion.db</code>.
     */
    public $connectionID;

    /**
     * @var boolean Whether the DB tables should be created automatically if they do not exist. Defaults to true.
     * If you already have the table created, it is recommended you set this property to be false to improve performance.
     */
    public $autoCreateTables = true;

    /**
     * @var string The layout used for module controllers.
     */
    public $layout = 'email.views.layouts.column1';

    /**
     * @var string The widget used to render grid views.
     */
    public $gridViewWidget = 'bootstrap.widgets.TbGridView';

    /**
     * @var string The widget used to render detail views.
     */
    public $detailViewWidget = 'zii.widgets.CDetailView';

    /**
     * @var array mapping from controller ID to controller configurations.
     */
    public $controllerMap = array(
        'spool' => 'email.controllers.EmailSpoolController',
        'template' => 'email.controllers.EmailTemplateController',
    );

    /**
     * @var array Map of model info including relations and behaviors.
     */
    public $modelMap = array();

    /**
     * @var array Defines the access filters for the module.
     * The default is EmailAccessFilter which will allow any user listed in EmailModule::adminUsers to have access.
     */
    public $controllerFilters = array(
        'emailAccess' => array('email.components.EmailAccessFilter'),
    );

    /**
     * @var array A list of users who can access this module.
     */
    public $adminUsers = array();

    /**
     * @var array A list of IPs who can access this module.
     */
    public $ipFilters = array();

    /**
     * @var array|string The home url, eg "/admin".
     */
    public $homeUrl;

    /**
     * @var string The path to YiiStrap.
     * Only required if you do not want YiiStrap in your app config, for example, if you are running YiiBooster.
     * Only required if you did not install using composer.
     * Please note:
     * - You must download YiiStrap even if you are using YiiBooster in your app.
     * - When using this setting YiiStrap will only loaded in the menu interface (eg: index.php?r=menu).
     */
    public $yiiStrapPath;

    /**
     * @var CDbConnection the DB connection instance
     */
    private $_db;

    /**
     * @var string Url to the assets
     */
    private $_assetsUrl;

    /**
     * @return string
     */
    public static function powered()
    {
        return Yii::t('email', 'Powered by {yii-email-module}.', array('{yii-email-module}' => '<a href="http://cornernote.github.io/yii-email-module/" rel="external">Yii Email Module</a>'));
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return trim(file_get_contents(dirname(__FILE__) . '/version.txt'));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Email';
    }

    /**
     * Initializes the email module.
     */
    public function init()
    {
        parent::init();

        // setup paths
        $this->setImport(array(
            'email.models.*',
            'email.components.*',
        ));

        // map models
        foreach ($this->getDefaultModelMap() as $method => $data)
            foreach ($data as $name => $options)
                if (empty($this->modelMap[$method][$name]))
                    $this->modelMap[$method][$name] = $options;

        // init yiiStrap
        $this->initYiiStrap();
    }

    /**
     * @return array
     */
    public function getDefaultModelMap()
    {
        return array();
    }

    /**
     * @return CDbConnection the DB connection instance
     * @throws CException if {@link connectionID} does not point to a valid application component.
     */
    public function getDbConnection()
    {
        if ($this->_db !== null)
            return $this->_db;
        elseif (($id = $this->connectionID) !== null) {
            if (($this->_db = Yii::app()->getComponent($id)) instanceof CDbConnection)
                return $this->_db;
            else
                throw new CException(Yii::t('email', 'EmailModule.connectionID "{id}" is invalid. Please make sure it refers to the ID of a CDbConnection application component.',
                    array('{id}' => $id)));
        }
        else {
            $dbFile = Yii::app()->getRuntimePath() . DIRECTORY_SEPARATOR . 'email-' . $this->getVersion() . '.db';
            return $this->_db = new CDbConnection('sqlite:' . $dbFile);
        }
    }

    /**
     * Sets the DB connection used by the cache component.
     * @param CDbConnection $value the DB connection instance
     * @since 1.1.5
     */
    public function setDbConnection($value)
    {
        $this->_db = $value;
    }

    /**
     * @return string the base URL that contains all published asset files of email.
     */
    public function getAssetsUrl()
    {
        if ($this->_assetsUrl === null)
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('email.assets'));
        return $this->_assetsUrl;
    }

    /**
     * @param string $value the base URL that contains all published asset files of email.
     */
    public function setAssetsUrl($value)
    {
        $this->_assetsUrl = $value;
    }

    /**
     * Setup yiiStrap, works even if YiiBooster is used in main app.
     */
    public function initYiiStrap()
    {
        // check that we are in a web application
        if (!(Yii::app() instanceof CWebApplication))
            return;
        // and in this module
        $route = explode('/', Yii::app()->urlManager->parseUrl(Yii::app()->request));
        if ($route[0] != $this->id)
            return;
        // and yiiStrap is not configured
        if (Yii::getPathOfAlias('bootstrap') && file_exists(Yii::getPathOfAlias('bootstrap.helpers') . '/TbHtml.php'))
            return;
        // try to guess yiiStrapPath
        if ($this->yiiStrapPath === null)
            $this->yiiStrapPath = Yii::getPathOfAlias('vendor.crisu83.yiistrap');
        // check for valid path
        if (!realpath($this->yiiStrapPath))
            return;
        // setup yiiStrap components
        Yii::setPathOfAlias('bootstrap', realpath($this->yiiStrapPath));
        Yii::import('bootstrap.helpers.*');
        Yii::import('bootstrap.widgets.*');
        Yii::import('bootstrap.behaviors.*');
        Yii::import('bootstrap.form.*');
        Yii::app()->setComponents(array(
            'bootstrap' => array(
                'class' => 'bootstrap.components.TbApi',
            ),
        ), false);
    }

}
