<?php

use Entities\Thumbnail, Entities\Media;

class Service_Thumbnail extends Sch_Service_Abstract
{

    protected $_entityName = '\Entities\Thumbnail';

    /**
     * @param Entities\Media $media
     * @return array
     */
    public function getAllForMedia(Media $media)
    {
        $all = $this->getRepository()->findBy(array('media' => $media->getId()));
        return array_combine(
            array_map(function ($it) { return $it->getType(); }, $all),
            $all
        );
    }

    /**
     * @param Entities\Media $media
     */
    public function clear(Media $media)
    {
        $all = $this->getAllForMedia($media);
        foreach ($all as /** @var $thumbnail \Entities\Thumbnail */$thumbnail) {
            $path = $thumbnail->getPath();
            if (file_exists($path) && is_writable($path)) {
                unlink($path);
            }
            $this->getEntityManager()->remove($thumbnail);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @param Entities\Media $media
     * @param array $urls
     * @param $thumbnailsDir
     */
    public function attachVimeoThumbnails(Media $media, array $urls, $thumbnailsDir)
    {
        $thumbnailSizes = Thumbnail::getSizes();
        foreach ($urls as $type => $url) {
            if (!empty($url) && array_key_exists($type, $thumbnailSizes)) {
                $ext = pathinfo($url, PATHINFO_EXTENSION);
                $path = $thumbnailsDir . DIRECTORY_SEPARATOR . $type . '.' . $ext;
                if (!file_exists($path)) {
                    touch($path);
                }
                if ($this->downloadVimeoThumbnail($url, $path)) {
                    /** @var $thumbnail \Entities\Thumbnail */
                    $thumbnail = $this->create();
                    $thumbnail->setPath($path);
                    $thumbnail->setType($type);
                    $media->addThumbnail($thumbnail);
                }
            }
        }
    }

}
