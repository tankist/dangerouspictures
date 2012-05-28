<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Video extends Media
{

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $duration;

    /**
     * @param int $duration
     * @return Video
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }
}
