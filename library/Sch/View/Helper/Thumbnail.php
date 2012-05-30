<?php

class Sch_View_Helper_Thumbnail extends Zend_View_Helper_Abstract
{

    /**
     * @param Entities\Media $media
     * @param $size
     * @return string
     * @throws Zend_View_Exception
     */
    public function thumbnail(\Entities\Media $media, $size = null)
    {
        if (!\Entities\Media::getSize($size)) {
            $size = '';
        }
        $path = $media->getPath();
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        /** @var $pathHelper Helper_AttachmentPath */
        $pathHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('AttachmentPath');
        if ($media instanceof \Entities\Video) {
            if ($size) {
                $subdir = 'videos';
                $file = $pathHelper->getWebPath($media, $subdir) . $size . '.jpg';
            }
            else {
                $file = $media->getPath();
            }
        }
        elseif ($media instanceof \Entities\Image) {
            $subdir = 'images';
            $file = $pathHelper->getWebPath($media, $subdir) . pathinfo($media->getPath(), PATHINFO_FILENAME) . $size . '.' . $ext;
        }
        else {
            throw new Zend_View_Exception('Wrong thumbnail type');
        }
        return $file;
    }

}
