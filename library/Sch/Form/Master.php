<?php

class Sch_Form_Master extends Sch_Form
{

    /**
     * @var string
     */
    protected $_step;

    /**
     * @var Sch_Form_Master_Persister_Interface
     */
    protected $_persister;

    protected $_stepName = '__master_step';

    /**
     * @var bool
     */
    protected $_isPartial = false;

    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->addElement(new Zend_Form_Element_Hidden($this->_stepName));
    }

    /**
     * @param array $data
     * @return bool
     */
    public function isValid($data)
    {
        $subForms = $this->getSubForms();
        if (empty($data[$this->_stepName])) {
            return false;
        }
        $step = $data[$this->_stepName];
        $this->setStep($step);
        if (!array_key_exists($step, $subForms) || !array_key_exists($step, $data)) {
            return false;
        }
        $this->getPersister()->write($step, $data[$step]);
        if (array_search($step, array_keys($subForms)) == (count($subForms) - 1)) {
            return parent::isValid($this->getPersister()->read() + array_diff_key($data, $subForms));
        }
        if ($this->isValidStep($data)) {
            $this->setStep($this->getNextStep());
        }
        return false;
    }

    /**
     * @param $data
     * @return bool
     * @throws InvalidArgumentException
     */
    public function isValidStep($data)
    {
        $step = $this->getStep();
        $form = $this->getSubForm($step);
        if (!($form instanceof Zend_Form)) {
            throw new \InvalidArgumentException('Step form not found');
        }
        $this->getPersister()->write($step, $data[$step]);
        $eBelongTo = $form->getElementsBelongTo();
        return $form->isValidPartial($data);
    }

    /**
     * @param string $step
     * @return Sch_Form_Master
     */
    public function setStep($step)
    {
        $this->_step = $step;
        $this->getPersister()->setStep($step);
        return $this;
    }

    /**
     * @return string
     */
    public function getStep()
    {
        if (!$this->_step) {
            $this->_step = $this->getPersister()->getStep();
        }
        return $this->_step;
    }

    /**
     * @return string
     */
    public function getNextStep()
    {
        $step = $this->getStep();
        $steps = array_keys($this->getSubForms());
        $index = array_search($step, $steps);
        if ($index === false || $index == (count($steps) - 1)) {
            return false;
        }
        return $steps[$index + 1];
    }

    public function getFirstStep()
    {
        return current(array_keys($this->getSubForms()));
    }

    /**
     * @param \Sch_Form_Master_Persister_Interface $persister
     * @return Sch_Form_Master
     */
    public function setPersister($persister)
    {
        $this->_persister = $persister;
        return $this;
    }

    /**
     * @return \Sch_Form_Master_Persister_Interface
     */
    public function getPersister()
    {
        return $this->_persister;
    }

    /**
     * @param null|\Zend_View_Interface $view
     * @return string
     */
    public function render(Zend_View_Interface $view = null)
    {
        if ($this->isPartial()) {
            return parent::render($view);
        }

        if (($step = $this->getStep()) === null) {
            $step = $this->getFirstStep();
            $this->setStep($step);
        }
        $form = clone $this;
        foreach ($form->getSubForms() as /** @var Zend_Form $subform */$subform) {
            if ($subform->getName() != $step) {
                $form->removeSubForm($subform->getName());
            }
        }

        $form->getElement($this->_stepName)->setValue($step);

        $form->isPartial(true);

        return $form->render($view);
    }

    /**
     * @param boolean $isPartial
     * @return Sch_Form_Master
     */
    public function isPartial($isPartial = null)
    {
        if ($isPartial !== null) {
            $this->_isPartial = (bool)$isPartial;
        }
        return $this->_isPartial;
    }

    /**
     * @param Zend_Form_Element_Hidden $step
     * @return void
     */
    protected function _prepareMasterStepDecorators(Zend_Form_Element_Hidden $step)
    {
        $step->setDecorators(array('ViewHelper'));
    }

}
