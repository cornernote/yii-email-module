<?php
/**
 * EmailAccessFilter
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */
class EmailAccessFilter extends CFilter
{

    /**
     * @param CFilterChain $filterChain
     * @return bool
     * @throws CHttpException
     */
    protected function preFilter($filterChain)
    {
        $app = Yii::app();
        /** @var emailModule $email */
        $email = $app->getModule('email');
        if (!in_array($app->getUser()->getName(), $email->adminUsers))
            throw new CHttpException(403, 'You are not allowed to access this page.');
        return parent::preFilter($filterChain);
    }

}
