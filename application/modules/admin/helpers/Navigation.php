<?php

class Admin_Helper_Navigation extends Helper_AbstractNavigation
{

    /**
     * @var Zend_Navigation
     */
    protected $_subNavigation;

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

    public function getSubnavigation()
    {
        if (!$this->_subNavigation) {
            $this->_subNavigation = new Zend_Navigation(array(
                new Zend_Navigation_Page_Mvc(array(
                    'label' => 'Images',
                    'module' => 'admin',
                    'controller' => 'media',
                    'action' => 'images',
                    'class' => 'image'
                )),
                new Zend_Navigation_Page_Mvc(array(
                    'label' => 'Video',
                    'module' => 'admin',
                    'controller' => 'media',
                    'action' => 'video',
                    'class' => 'vimeo'
                ))
            ));
        }
        return $this->_subNavigation;
    }

    public function direct()
    {
        $navigation = parent::direct();
        Zend_Layout::getMvcInstance()->assign('navigation', $navigation);
        Zend_Layout::getMvcInstance()->assign('subNavigation', $this->getSubnavigation());
        return $navigation;
    }

}
