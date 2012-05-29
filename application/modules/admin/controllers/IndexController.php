<?php

class Admin_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->navigation();
    }

    public function indexAction()
    {
        $this->_forward('', 'media');
    }


}

