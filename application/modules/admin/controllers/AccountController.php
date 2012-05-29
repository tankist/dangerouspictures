<?php

class Admin_AccountController extends Zend_Controller_Action
{

    const SESSION_SAVER_HANDLER = 'AdminAccountData';

    /**
     * @var Service_User
     */
    protected $_service;

    public function init()
    {
        $this->_service = new Service_User($this->_helper->Em());
        $this->_helper->navigation();
    }

    public function indexAction()
    {
        $form = new Admin_Form_Account(array(
            'action' => $this->_helper->url('save')
        ));
        if (($data = $this->_helper->sessionSaver(self::SESSION_SAVER_HANDLER))) {
            $form->populate($data);
            $this->_helper->sessionSaver->delete(self::SESSION_SAVER_HANDLER);
        }
        else {
            $form->populateEntity($this->_service->getFirstRootUser());
        }
        $this->view->form = $form;
    }

    public function saveAction()
    {
        /** @var $request Zend_Controller_Request_Http */
        $request = $this->getRequest();
        $form = new Admin_Form_Account();
        if ($request->isPost() && $form->isValid($request->getPost())) {
            $user = $this->_service->getFirstRootUser();
            $user->populate($form->getValues());
            $user->setPassword(md5($user->getPassword()));
            $this->_service->save($user);
            $this->_helper->sessionSaver->delete(self::SESSION_SAVER_HANDLER);
        }
        else {
            $this->_helper->sessionSaver(self::SESSION_SAVER_HANDLER, $form->getValues());
            $this->_helper->flashMessenger->addErrorsFromForm($form);
        }
        $this->_redirect($this->_helper->url(''));
    }


}



