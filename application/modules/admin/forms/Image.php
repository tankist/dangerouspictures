<?php

class Admin_Form_Image extends Sch_Form
{

    /**
     * @var string
     */
    protected $_imagesPath = '';

    public function init()
    {
        $image = new Zend_Form_Element_File('image');
        $image->setRequired(true);
        if (is_dir(($path = $this->getImagesPath()))) {
            $image->setDestination($path);
        }
        $this->addElement($image);
    }

    /**
     * @param string $imagesPath
     */
    public function setImagesPath($imagesPath)
    {
        $this->_imagesPath = $imagesPath;
    }

    /**
     * @return string
     */
    public function getImagesPath()
    {
        return $this->_imagesPath;
    }

}
