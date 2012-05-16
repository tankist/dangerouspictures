<?php

namespace Sch\Doctrine\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class AbstractEnum extends Type
{

    /**
     * @var string
     */
    protected $_name;

    /**
     * @var array
     */
    protected $_values = array();

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $values = array_map(function($val) { return "'" . $val . "'"; }, $this->_values);
        return "ENUM(" . implode(", ", $values) . ") COMMENT '(DC2Type:" . $this->getName() . ")'";
    }

    /**
     * @param $value
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    /**
     * @param $value
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, $this->_values)) {
            throw new \InvalidArgumentException("Invalid type");
        }
        return $value;
    }

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

}
