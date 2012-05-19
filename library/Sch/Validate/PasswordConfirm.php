<?php

class Sch_Validate_PasswordConfirm extends Zend_Validate_Abstract
{

    const INVALID           = 'passwordConfirmInvalid';
    const MISMATCH_PASSWD   = 'passwordConfirmMismatch';
    const MISMATCH_EMAIL    = 'emailConfirmMismatch';
    const MISMATCH_ACCOUNT  = 'accountConfirmMismatch';

    /**
     * @var string
     */
    protected $_contextKey = '';
    /**
     * @var string
     */
    protected $_messageKey = '';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID          => "Invalid type given. String expected",
        self::MISMATCH_PASSWD  => "The passwords you entered do not match. Please try again",
        self::MISMATCH_EMAIL   => "The emails you entered do not match. Please try again",
        self::MISMATCH_ACCOUNT => "The account numbers you entered do not match. Please try again",
    );

    public  function __construct($contextKey, $sMsgKey = 'passwordConfirmMismatch')
    {
        $this->setContextKey($contextKey);
        $this->setMessageKey($sMsgKey);
    }

    /**
     * @throws Zend_Validate_Exception
     * @param $value
     * @param null $context
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        if (!array_key_exists($this->_contextKey, $context)) {
            throw new Zend_Validate_Exception('Confirm field name not found');
        }
        if (!is_string($value)) {
            $this->_error(self::INVALID);
        }
        if ($value != $context[$this->_contextKey]) {
            $this->_error($this->_messageKey);
        }

        return (count($this->_messages) == 0);
    }

    /**
     * @param string $contextKey
     * @return Sch_Validate_PasswordConfirm
     */
    public function setContextKey($contextKey)
    {
        $this->_contextKey = $contextKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getContextKey()
    {
        return $this->_contextKey;
    }

    /**
     * @param string $sMsgKey
     * @return Sch_Validate_PasswordConfirm
     */
    public function setMessageKey($sMsgKey)
    {
        $this->_messageKey = isset($this->_messageTemplates[$sMsgKey]) ? $sMsgKey : self::MISMATCH_PASSWD;
        return $this;
    }

}
