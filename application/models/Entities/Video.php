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

}
