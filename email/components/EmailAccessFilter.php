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
        /** @var EmailModule $email */
        $email = $app->getModule('email');
        $user = $app->getUser();
        //$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : Yii::app()->request->userHostAddress;
        $ip = Yii::app()->request->userHostAddress;
        if (!$this->allowUser($email, $user) || !$this->allowIp($email, $ip)) {
            throw new CHttpException(403, 'You are not allowed to access this page.');
        }
        return parent::preFilter($filterChain);
    }

    /**
     * Checks to see if the user IP is allowed by {@link ipFilters}.
     * @param $email EmailModule
     * @param $user
     * @return bool whether the user IP is allowed by <a href='psi_element://ipFilters'>ipFilters</a>.
     * @throws CHttpException
     * @internal param string $ip the user IP
     */
    protected function allowUser($email, $user)
    {
        if (empty($email->adminUsers)) {
            return true;
        }
        if (in_array($user->getName(), $email->adminUsers)) {
            return true;
        }
        return false;
    }

    /**
     * Checks to see if the user IP is allowed by {@link ipFilters}.
     * @param $email EmailModule
     * @param string $ip the user IP
     * @return bool whether the user IP is allowed by <a href='psi_element://ipFilters'>ipFilters</a>.
     */
    protected function allowIp($email, $ip)
    {
        if (empty($email->ipFilters))
            return true;
        foreach ($email->ipFilters as $filter) {
            if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos)))
                return true;
        }
        return false;
    }

}
