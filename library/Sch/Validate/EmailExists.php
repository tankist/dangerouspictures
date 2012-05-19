<?php

/**
 * App_Validate_EqualInputs
 *
 * Validator compare two fields.
 *
 */
class Sch_Validate_EmailExists extends Zend_Validate_Abstract
{
    /**
     * Error mark
     */
    const IS_EMAIL_EXISTS = 'emailAlreadyExists';

    /**
     * Error text
     * @var array
     */
    protected $_messageTemplates = array(
        self::IS_EMAIL_EXISTS => 'Such email already exists'
    );

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->_em = $em;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $value = (string) $value;
        if($this->_em->getRepository('\Entities\User')->findOneBy(array('email' => $value)) === TRUE) {
            $this->_error(self::IS_EMAIL_EXISTS);
            return false;
        }
        return true;
    }

}