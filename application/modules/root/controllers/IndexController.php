<?php

class Root_IndexController extends Zend_Controller_Action
{

    public $ajaxable = array(
        'save' => array('json')
    );

    /**
     * @var Service_User
     */
    protected $_service;

    public function init()
    {
        $this->_service = new Service_User($this->_helper->Em());
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
            /** @var $user \Entities\User */
            $user = $this->_service->create();
            $user->populate($form->getValues());
            $user->setRole(Sch_Acl_Roles::ADMIN);
            $user->setPassword(md5($user->getPassword()));
            $this->_service->save($user);
        }
        else {
            $this->view->error = $form->getMessages();
        }
    }

}

