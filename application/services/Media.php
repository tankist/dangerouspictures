<?php

use Entities\Media;

class Service_Media extends Sch_Service_Abstract
{

    protected $_entityName = '\Entities\Media';

    /**
     * @return Entities\Image
     */
    public function createImage()
    {
        return new \Entities\Image();
    }

    /**
     * @return Entities\Video
     */
    public function createVideo()
    {
        return new \Entities\Video();
    }

    public function getAll()
    {
        return $this->getRepository()->findBy(array(), array('position' => 'ASC'));
    }

    /**
     * @param $vimeo_id
     * @return array
     * @throws Sch_Service_Exception
     */
    public function getVimeoData($vimeo_id)
    {
        $url = sprintf('/api/v2/video/%s.json', $vimeo_id);
        try {
            $client = new Zend_Rest_Client('http://vimeo.com');
            $response = $client->restGet($url);
            $data = Zend_Json::decode($response->getBody());
            if (count($data) > 0) {
                $data = current($data);
            }
        } catch (Zend_Rest_Client_Exception $e) {
            throw new Sch_Service_Exception('Error on request data from Vimeo');
        } catch (Zend_Json_Exception $e) {
            throw new Sch_Service_Exception('Incorrect data retrieved from Vimeo');
        }
        return $data;
    }

    public function getLastPosition()
    {
        $result = $this->getEntityManager()
            ->createQuery('SELECT m.position FROM Entities\Media m ORDER BY m.position DESC')
            ->setMaxResults(1)
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        return (count($result) > 0) ? $result[0]['position'] : 0;
    }

    /**
     * @param Entities\Media $media
     * @return bool
     */
    public function up(Media $media)
    {
        $all = new \Doctrine\Common\Collections\ArrayCollection($this->getAll());
        if ($media == $all->first()) {
            return false;
        }
        /** @var $swap \Entities\Media */
        $swap = $all->filter(function($element) use ($media) {
            return $element->getPosition() < $media->getPosition();
        })->last();
        if (!$swap) {
            return false;
        }
        $position = $swap->getPosition();
        $swap->setPosition($media->getPosition());
        $media->setPosition($position);
        $this->save($swap);
        $this->save($media);
        return true;
    }

    /**
     * @param Entities\Media $media
     * @return bool
     */
    public function down(Media $media)
    {
        $all = new \Doctrine\Common\Collections\ArrayCollection($this->getAll());
        if ($media == $all->last()) {
            return false;
        }
        /** @var $swap \Entities\Media */
        $swap = $all->filter(function($element) use ($media) {
            return $element->getPosition() > $media->getPosition();
        })->first();
        if (!$swap) {
            return false;
        }
        $position = $swap->getPosition();
        $swap->setPosition($media->getPosition());
        $media->setPosition($position);
        $this->save($swap);
        $this->save($media);
        return true;
    }

    /**
     * @param Media $entity
     * @return Sch_Service_Abstract
     */
    public function save($entity)
    {
        if (!$entity->getPosition()) {
            $entity->setPosition($this->getLastPosition() + 1);
        }
        return parent::save($entity);
    }

    public function delete($entity)
    {
        /** @var $pathHelper Helper_AttachmentPath */
        $pathHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('AttachmentPath');
        /** @var $attachmentsHelper Helper_Attachments */
        $attachmentsHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('Attachments');
        if ($entity instanceof \Entities\Video) {
            $subdir = 'videos';
        }
        elseif ($entity instanceof \Entities\Image) {
            $subdir = 'images';
        }
        else {
            $subdir = '';
        }
        if ($subdir) {
            $path = $pathHelper->getRealPath($entity, $subdir);
            if (is_dir($path) && is_writable($path)) {
                $attachmentsHelper->recursiveClearFolder($path);
            }
        }
        return parent::delete($entity);
    }

    /**
     * @param $url
     * @param $path
     * @return bool
     * @throws Sch_Service_Exception
     */
    public function downloadVimeoThumbnail($url, $path)
    {
        $client = new Zend_Http_Client($url);
        $client->setStream($path);
        $response = $client->request(Zend_Http_Client::GET);
        if ($response->getStatus() != 200) {
            throw new Sch_Service_Exception('Error on downloading thumbnail');
        }
        return true;
    }

}
