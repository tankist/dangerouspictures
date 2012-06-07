<?php

class Default_Bootstrap extends Zend_Application_Module_Bootstrap
{

    public function _initRoutes()
    {
        $front = Zend_Controller_Front::getInstance();
        /** @var $router Zend_Controller_Router_Rewrite */
        $router = $front->getRouter();
        $router->addRoutes(array(
            'contact' => new Zend_Controller_Router_Route_Static('contact', array(
                'module' => strtolower($this->getModuleName()),
                'controller' => 'index',
                'action' => 'contact'
            ))
        ));
    }

}