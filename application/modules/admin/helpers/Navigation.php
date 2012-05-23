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
                    'label' => 'Media',
                    'module' => 'admin',
                    'controller' => 'media',
                    'action' => ''
                )),
                new Zend_Navigation_Page_Mvc(array(
                    'label' => 'Contact Us',
                    'module' => 'admin',
                    'controller' => 'contact',
                    'action' => ''
                )),
                new Zend_Navigation_Page_Mvc(array(
                    'label' => 'Settings',
                    'module' => 'admin',
                    'controller' => 'settings',
                    'action' => ''
                )),
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
