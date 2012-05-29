<?php

class Default_IndexController extends Zend_Controller_Action
{

    /**
     * @var Service_User
     */
    protected $_service;

    public function init()
    {
        $this->_service = new Service_User($this->_helper->Em());
    }

    public function indexAction()
    {
        // action body
    }

    public function contactAction()
    {
        $form = new Default_Form_Contact(array(
            'action' => $this->_helper->url('send')
        ));
        $this->view->form = $form;
    }

    public function sendAction()
    {
        /** @var $request Zend_Controller_Request_Http */
        $request = $this->getRequest();
        $form = new Default_Form_Contact();
        if ($request->isPost() && $form->isValid($request->getPost())) {
            $me = $this->_service->getFirstRootUser();
            $this->view->assign($form->getValues());
            $this->_helper->mailSender->emailContact(
                $form->getValue('email'),
                $me->getEmail(),
                $form->getValue('subject'));
        }
        $this->_redirect($this->_helper->url(''));
    }

}

