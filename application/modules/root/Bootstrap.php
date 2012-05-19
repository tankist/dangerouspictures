<?php

class Root_Bootstrap extends Zend_Application_Module_Bootstrap
{

    protected function _initRoutes()
    {
        $front = Zend_Controller_Front::getInstance();
        /** @var $router Zend_Controller_Router_Rewrite */
        $router = $front->getRouter();

        $rootSaveRoute = new Zend_Controller_Router_Route_Static('/root/save', array(
            'module' => 'root',
            'controller' => 'index',
            'action' => 'save'
        ));

        $router->addRoute('saveRoot', $rootSaveRoute);
    }

    protected function _initAcl()
    {
        /** @var $bootstrap Bootstrap */
        $bootstrap = $this->getApplication();
        $bootstrap->bootstrap('acl');
        /** @var $acl Zend_Acl */
        $acl = $bootstrap->getResource('acl');
        if ($acl instanceof Zend_Acl) {
            $root = new Zend_Acl_Resource('root');
            $acl->addResource($root);
            $acl->allow(null, $root);
        }
    }

    protected function _initAuth()
    {
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Root_Plugin_Auth(
            strtolower($this->_moduleName),
            APPLICATION_PATH . '/../.htpasswd')
        );
    }

}
