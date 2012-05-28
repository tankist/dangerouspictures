<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="thumbnails",
 *             uniqueConstraints={
 *                  @ORM\UniqueConstraint(name="typeMedia", columns={"media_id", "type"}),
 *            })
 */
class Thumbnail
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
     * @var Media
     * @ORM\ManyToOne(targetEntity="Media", inversedBy="thumbnails", cascade={"persist"})
     */
    protected $media;

    /**
     * @var string
     * @ORM\Column(type="string", length=1)
     */
    protected $type;

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
     * @param \Entities\Media $media
     * @return Thumbnail
     */
    public function setMedia($media)
    {
        $this->media = $media;
        return $this;
    }

    /**
     * @return \Entities\Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param string $path
     * @return Thumbnail
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
     * @param string $type
     * @return Thumbnail
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param array $sizes
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


}
