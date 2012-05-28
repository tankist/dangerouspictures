<?php

class Admin_MediaController extends Zend_Controller_Action
{

    public $ajaxable = array(
        'saveVimeo' => array('json'),
        'saveImage' => array('json')
    );

    /**
     * @var Service_Media
     */
    protected $_service;

    public function init()
    {
        $this->_service = new Service_Media($this->_helper->Em());
        $this->_helper->getHelper('AjaxContext')->initContext();
        $this->_helper->navigation();
    }

    public function indexAction()
    {
        $this->view->media = $this->_service->getAll();
    }

    public function saveVimeoAction()
    {
        $vimeo_id = $this->_getParam('vimeo_id');
        if (!$vimeo_id) {
            throw new Zend_Controller_Action_Exception('Vimeo video could not be retrieved. Please provide ID', 403);
        }
        $vimeo = $this->_service->getVimeoData($vimeo_id);
        /** @var $media \Entities\Video */
        $media = $this->_service->createVideo();
        $media->setPath($vimeo['url']);
        $media->setWidth($vimeo['width']);
        $media->setHeight($vimeo['height']);
        $this->_service->save($media);
    }

    public function saveImageAction()
    {
        /** @var $request Zend_Controller_Request_Http */
        $request = $this->getRequest();
        $form = new Admin_Form_Image(array(
            'imagesPath' => $this->_helper->attachmentPath()
        ));
        if ($request->isPost() && $form->isValid($request->getPost())) {
            $path = $form->getValue('image');
            if (!($id = $form->getValue('id')) || !($image = $this->_service->getById($id))) {
                $image = $this->_service->createImage();
            }
            $image->setPath($path);
            foreach ($image->getThumbnails() as /** @var $thumbnail \Entities\Thumbnail */ $thumbnail) {
                $path = $thumbnail->getPath();
                if ($path && is_file($path) && is_writable($path)) {
                    set_error_handler(function($code, $message) {
                        throw new Zend_Controller_Action_Exception($message);
                    });
                    unlink($path);
                    restore_error_handler();
                }
            }
            $thumbnails = $this->_helper->attachments->thumbnail($image, dirname($image->getPath()));
        }
    }

    public function upAction()
    {
        $id = $this->_getParam('id');
        /** @var $media \Entities\Media */
        if (!($media = $this->_service->getById($id))) {
            throw new Zend_Controller_Action_Exception('Media file not found', 404);
        }
        $this->_service->up($media);
    }

    public function downAction()
    {
        $id = $this->_getParam('id');
        /** @var $media \Entities\Media */
        if (!($media = $this->_service->getById($id))) {
            throw new Zend_Controller_Action_Exception('Media file not found', 404);
        }
        $this->_service->down($media);
    }

}

