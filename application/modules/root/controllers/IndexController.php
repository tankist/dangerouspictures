<?php

class Root_IndexController extends Zend_Controller_Action
{

    public $ajaxable = array(
        'save' => array('json')
    );

    public function init()
    {
        $this->_helper->getHelper('AjaxContext')->initContext();
    }

    public function indexAction()
    {
        $form = new Root_Form_Root(array(
            'action' => $this->_helper->url('save')
        ));
        $this->view->form = $form;
    }

    public function saveAction()
    {
        /** @var $request Zend_Controller_Request_Http */
        $request = $this->getRequest();
        $form = new Root_Form_Root();
        if ($request->isPost() && $form->isValid($request->getPost())) {

        }
        else {
            $this->view->error = $form->getMessages();
        }
    }

}

