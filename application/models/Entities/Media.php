<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="media")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="media_type", type="string")
 * @ORM\DiscriminatorMap({"video" = "Video", "image" = "Image"})
 */
class Media extends AbstractEntity
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $path;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $position;

    /**
     * @var int
     * @ORM\Column(type="integer", length=5)
     */
    protected $width;

    /**
     * @var int
     * @ORM\Column(type="integer", length=5)
     */
    protected $height;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $link;

    /**
     * @var array
     */
    protected static $_sizes = array();

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $height
     * @return Media
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param string $path
     * @return Media
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param int $position
     * @return Media
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $width
     * @return Media
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @static
     * @param $sizes
     */
    public static function setSizes($sizes)
    {
        self::$_sizes = $sizes;
    }

    /**
     * @return array
     */
    public static function getSizes()
    {
        return self::$_sizes;
    }

    /**
     * @static
     * @param $type
     * @param $size
     */
    public static function setSize($type, $size)
    {
        self::$_sizes[$type] = $size;
    }

    /**
     * @static
     * @param $type
     * @return null
     */
    public static function getSize($type)
    {
        return (array_key_exists($type, self::$_sizes)) ? self::$_sizes[$type] : null;
    }

    /**
     * @param string $description
     * @return Media
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $link
     * @return Media
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $title
     * @return Media
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

}
