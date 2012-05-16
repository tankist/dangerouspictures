<?php

namespace Sch\Doctrine\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EnumTitleType extends AbstractEnum
{

    const TYPE_NAME = 'enumtitle';

    const TYPE_MR   = 'Mr.';
    const TYPE_MRS  = 'Mrs.';
    const TYPE_MS   = 'Ms.';
    const TYPE_MISS = 'Miss.';
    const TYPE_DR   = 'Dr.';

    /**
     * @var string
     */
    protected $_name = self::TYPE_NAME;

    /**
     * @var array
     */
    protected $_values = array(
        self::TYPE_MR,
        self::TYPE_MRS,
        self::TYPE_MS,
        self::TYPE_MISS,
        self::TYPE_DR,
    );

}
