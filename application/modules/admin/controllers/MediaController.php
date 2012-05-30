<?php

class Admin_MediaController extends Zend_Controller_Action
{

    public $ajaxable = array(
        'save-vimeo' => array('json'),
        'save-image' => array('json'),
        'up' => array('json'),
        'down' => array('json')
    );

    /**
     * @var Service_Media
     */
    protected $_service;

    public function init()
    {
        $em = $this->_helper->Em();
        $this->_service = new Service_Media($em);
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

        $thumbnailsDir = $this->_helper->attachmentPath($media, 'videos');
        foreach (array(
                     's' => $vimeo['thumbnail_small'],
                     'm' => $vimeo['thumbnail_medium'],
                     'b' => $vimeo['thumbnail_large']) as $type => $url) {
            $ext = pathinfo($url, PATHINFO_EXTENSION);
            $this->_service->downloadVimeoThumbnail($url, $thumbnailsDir . DIRECTORY_SEPARATOR . $type . '.' . $ext);
        }
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
            $path = $form->getImagesPath() . DIRECTORY_SEPARATOR . $form->getValue('image');
            if (!($id = $form->getValue('id')) || !($image = $this->_service->getById($id))) {
                $image = $this->_service->createImage();
            }
            $image->setPath($path);
            if (($data = getimagesize($path))) {
                $image->setWidth($data[0]);
                $image->setHeight($data[1]);
            }
            $this->_service->save($image);
            $imagePath = $this->_helper->attachmentPath($image, 'images');
            $this->_helper->attachments->upload($image, $imagePath, true, true);
            $this->_service->save($image);
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

    public function deleteAction()
    {
        $id = $this->_getParam('id');
        /** @var $media \Entities\Media */
        if (!($media = $this->_service->getById($id))) {
            throw new Zend_Controller_Action_Exception('Media file not found', 404);
        }
        try {
            $this->_service->delete($media);
        } catch (Exception $e) {

        }
        $this->_redirect($this->_helper->url->url(array(
            'module' => 'admin',
            'controller' => 'media',
            'action' => ''
        ), null, true));
    }

}

