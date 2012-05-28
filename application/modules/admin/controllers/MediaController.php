<?php

class Admin_MediaController extends Zend_Controller_Action
{

    public $ajaxable = array(
        'save-vimeo' => array('json'),
        'save-image' => array('json')
    );

    /**
     * @var Service_Media
     */
    protected $_service;

    /**
     * @var Service_Thumbnail
     */
    protected $_thumbnailsService;

    public function init()
    {
        $em = $this->_helper->Em();
        $this->_service = new Service_Media($em);
        $this->_thumbnailsService = new Service_Thumbnail($em);
        $this->_helper->getHelper('AjaxContext')->initContext();
        $this->_helper->navigation();
    }

    public function indexAction()
    {
        $this->view->media = $this->_service->getAll();
    }

    public function saveVimeoAction()
    {
        $url = $this->_getParam('url');
        if (preg_match('/https?\:\/\/vimeo.com\/(\d+)/i', $url, $matches)) {
            $vimeo_id = $matches[1];
        }
        if (!isset($vimeo_id)) {
            throw new Zend_Controller_Action_Exception('Vimeo video could not be retrieved. Please provide ID', 403);
        }
        $vimeo = $this->_service->getVimeoData($vimeo_id);
        /** @var $media \Entities\Video */
        $media = $this->_service->createVideo();
        $media->setPath($vimeo['url']);
        $media->setWidth($vimeo['width']);
        $media->setHeight($vimeo['height']);
        $media->setDuration($vimeo['duration']);
        $this->_service->save($media);

        $this->_thumbnailsService->clear($media);

        $thumbnailsDir = $this->_helper->attachmentPath($media, 'videos');
        $this->_thumbnailsService->attachVimeoThumbnails($media, array(
            's' => $vimeo['thumbnail_small'],
            'm' => $vimeo['thumbnail_medium'],
            'b' => $vimeo['thumbnail_large']), $thumbnailsDir);
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

