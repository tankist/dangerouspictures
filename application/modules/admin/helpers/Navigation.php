<?php

class Admin_Helper_Navigation extends Helper_AbstractNavigation
{

    /**
     * @return Zend_Navigation_Container
     */
    public function getNavigation()
    {
        if (!$this->_navigation) {
            $this->_navigation = new Zend_Navigation(array(
                new Zend_Navigation_Page_Mvc(array(
                    'label' => 'Account Management',
                    'module' => 'admin',
                    'controller' => 'account',
                    'action' => '',
                    'class' => 'account-link'
                )),
                new Zend_Navigation_Page_Mvc(array(
                    'label' => 'Gallery',
                    'module' => 'admin',
                    'controller' => 'media',
                    'action' => '',
                    'class' => 'gallery-link'
                ))
            ));
        }
        return $this->_navigation;
    }

    public function direct()
    {
        $navigation = parent::direct();
        Zend_Layout::getMvcInstance()->assign('navigation', $navigation);
        return $navigation;
    }

}
