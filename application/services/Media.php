<?php

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

}
