<?php

class Default_IndexController extends Zend_Controller_Action
{

    /**
     * @var Service_User
     */
    protected $_service;

    /**
     * @var Service_Media
     */
    protected $_mediaService;

    public function init()
    {
        $em = $this->_helper->Em();
        $this->_service = new Service_User($em);
        $this->_mediaService = new Service_Media($em);
    }

    public function indexAction()
    {
        $root = $this->_service->getFirstRootUser();
        $this->view->images = $this->_mediaService->getAll();
        $this->view->assign(array(
            'twitter' => $root->getTwitter() ? sprintf('http://twitter.com/%s', $root->getTwitter()) : '',
            'facebook' => $root->getFacebook() ? sprintf('http://facebook.com/%s', $root->getFacebook()) : '',
            'vimeo' => $root->getVimeo() ? sprintf('http://vimeo.com/%s', $root->getVimeo()) : ''
        ));
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

