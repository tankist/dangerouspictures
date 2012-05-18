<?php

class Admin_AuthController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function loginAction()
    {
        $this->_helper->layout()->setLayout('login');
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect($this->_helper->url('login'));
    }

    public function forgetAction()
    {
        // action body
    }

    public function recoverAction()
    {
        // action body
    }


}









