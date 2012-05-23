<?php

class Admin_AuthController extends Zend_Controller_Action
{

    /**
     * @var Service_User
     */
    protected $_service = null;

    public function init()
    {
        $this->_service = new Service_User($this->_helper->Em());
    }

    public function indexAction()
    {
        // action body
    }

    public function loginAction()
    {
        /** @var $request Zend_Controller_Request_Http */
        $request = $this->getRequest();
        $form = new Admin_Form_Login(array(
            'action' => $this->_helper->url('login')
        ));
        if ($request->isPost() && $form->isValid($request->getPost())) {
            $data = $form->getValues();
            if ($this->_authenticate($data)) {
                /** @var $me \Entities\User */
                if ($me = $this->_helper->currentUser()) {
                    $me->setOnline(true)->setOnlineLast(new DateTime());
                    $this->_service->save($me);
                }
                $this->_redirect(urldecode($this->_getParam('return', '/admin')));
            }
        }
        $form->populate(array('return' => $this->_getParam('return')));
        $this->view->form = $form;

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

    private function _authenticate($data)
    {
        if (empty($data['email']) || empty($data['password'])) {
            return false;
        }

        if (!empty($data['remember']) && $data['remember']) {
            Zend_Session::rememberMe(86400 * 15);
        } else {
            Zend_Session::forgetMe();
        }

        $authAdapter = new Sch_Auth_Adapter_Doctrine2(
            $this->_helper->Em(),
            'Entities\User',
            'email',
            'password'
        );
        $authAdapter->setIdentity($data['email']);
        $authAdapter->setCredential(md5($data['password']));

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);
        return $result->isValid();
    }

}









