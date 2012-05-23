<?php

class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{

    protected function _initHelpers()
    {
        $this->bootstrap('frontcontroller');
        /**
         * @var Zend_Controller_Front $frontController
         */
        $frontController = $this->getResource('frontController');
        Zend_Controller_Action_HelperBroker::addPath(
            $frontController->getModuleDirectory(
                strtolower($this->getModuleName())) .
                DIRECTORY_SEPARATOR . 'helpers',
            'Admin_Helper'
        );
    }
    
    protected function _initRoutes()
    {
        $front = Zend_Controller_Front::getInstance();
        /** @var $router Zend_Controller_Router_Rewrite */
        $router = $front->getRouter();

        $logout = new Zend_Controller_Router_Route_Static('admin/logout', array(
            'module' => 'admin',
            'controller' => 'auth',
            'action' => 'logout'
        ));
        $login = new Zend_Controller_Router_Route_Static('admin/login', array(
            'module' => 'admin',
            'controller' => 'auth',
            'action' => 'login'
        ));

        $router->addRoutes(array(
            'login' => $login,
            'logout' => $logout
        ));
    }

}