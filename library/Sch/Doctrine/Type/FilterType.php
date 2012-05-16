<?php

namespace Sch\Doctrine\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class FilterType extends AbstractEnum
{

    const FILTER_TYPE = 'filterType';

    const TYPE_CHECKBOX = 'checkbox';

    const TYPE_TEXT = 'text';

    /**
     * @var string
     */
    protected $_name = self::FILTER_TYPE;

    /**
     * @var array
     */
    protected $_values = array(self::TYPE_CHECKBOX, self::TYPE_TEXT);

}
