<?php

/**
 * @stringerface
 */
interface Sch_Form_Master_Persister_Interface
{

    /**
     * @abstract
     * @param string $step
     * @return array|null
     */
    public function read($step = null);

    /**
     * @abstract
     * @param string $step
     * @param array $data
     * @return self
     */
    public function write($step = null, array $data);

    /**
     * @abstract
     * @return self
     */
    public function clean();

    /**
     * @abstract
     * @param string $step
     * @return self
     */
    public function setStep($step);

    /**
     * @abstract
     * @return string
     */
    public function getStep();

}
