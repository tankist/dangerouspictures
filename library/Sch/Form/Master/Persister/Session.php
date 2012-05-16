<?php

/**
 * @implements Sch_Form_Master_Persister_Interface
 */
class Sch_Form_Master_Persister_Session
    extends Sch_Form_Master_Persister_Abstract
    implements Sch_Form_Master_Persister_Interface
{

    /**
     * @var Zend_Session_Namespace
     */
    protected $_session;

    /**
     * @var string
     */
    protected $_stepKey = '__multi-page-form-step-key';

    /**
     * @var string
     */
    protected $_dataKey = '__multi-page-form-step-data';

    /**
     * @param Zend_Session_Namespace $session
     */
    public function __construct(Zend_Session_Namespace $session)
    {
        $this->setSession($session);
    }

    /**
     * @param int|null $step
     * @return array|null
     * @throws InvalidArgumentException
     */
    public function read($step = null)
    {
        $data = (array)$this->getSession()->__get($this->getDataKey());
        if ($step === null) {
            return $data;
        }
        if (!is_int($step)) {
            throw new \InvalidArgumentException('Step must be int');
        }
        return (array_key_exists($step, $data))?$data[$step]:null;
    }

    /**
     * @param int|null $step
     * @param array $data
     * @return Sch_Form_Master_Persister_Interface|Sch_Form_Master_Persister_Session
     * @throws InvalidArgumentException
     */
    public function write($step = null, array $data)
    {
        if ($step === null) {
            $step = $this->getStep();
        }
        if (empty($step)) {
            throw new \InvalidArgumentException('Step must be non-empty');
        }
        $currentData = (array)$this->getSession()->__get($this->getDataKey());
        $currentData[$step] = $data;
        $this->getSession()->__set($this->getDataKey(), $currentData);
        return $this;
    }

    /**
     * @return Sch_Form_Master_Persister_Interface|Sch_Form_Master_Persister_Session
     */
    public function clean()
    {
        $this->getSession()->__unset($this->getDataKey());
        $this->getSession()->__unset($this->getStepKey());
        return $this;
    }

    /**
     * @param int $step
     * @return Sch_Form_Master_Persister_Interface|Sch_Form_Master_Persister_Session
     */
    public function setStep($step)
    {
        $this->getSession()->__set($this->getStepKey(), $step);
        return $this;
    }

    /**
     * @return int
     */
    public function getStep()
    {
        return $this->getSession()->__get($this->getStepKey());
    }

    /**
     * @param \Zend_Session_Namespace $session
     * @return Sch_Form_Master_Persister_Session
     */
    public function setSession($session)
    {
        $this->_session = $session;
        return $this;
    }

    /**
     * @return \Zend_Session_Namespace
     */
    public function getSession()
    {
        return $this->_session;
    }

    /**
     * @param string $dataKey
     * @return Sch_Form_Master_Persister_Session
     */
    public function setDataKey($dataKey)
    {
        $this->_dataKey = $dataKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataKey()
    {
        return $this->_dataKey;
    }

    /**
     * @param string $stepKey
     * @return Sch_Form_Master_Persister_Session
     */
    public function setStepKey($stepKey)
    {
        $this->_stepKey = $stepKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getStepKey()
    {
        return $this->_stepKey;
    }

}