<?php

class Sch_Form extends Zend_Form
{

    protected $_prepared = false;

    /**
     * @param mixed|null $options
     */
    public function __construct($options = null)
    {
        $this
            ->addPrefixPath('Sch_Form_Decorator_Twitter', 'Sch/Form/Decorator/Twitter', 'decorator')
            ->addPrefixPath('Sch_Form_Element_Twitter', 'Sch/Form/Element/Twitter', 'element')
            ->addElementPrefixPath('Sch_Form_Decorator_Twitter', 'Sch/Form/Decorator/Twitter', 'decorator')
            ->addDisplayGroupPrefixPath('Sch_Form_Decorator_Twitter', 'Sch/Form/Decorator/Twitter');
        parent::__construct($options);
    }

    /**
     * @return Sch_Form
     */
    public function prepareDecorators()
    {
        if ($this->_prepared) {
            return $this;
        }
        $this->_prepared = true;
        return $this
            ->_prepareElementsDecorators()
            ->_prepareSubformsDecorators();
    }

    /**
     * @param array $values
     * @return Zend_Form
     */
    public function populate(array $values)
    {
        foreach ($values as $elementName => $elementValue) {
            $populateMethodName = '_populate' . ucfirst(Zend_Filter::filterStatic($elementName, 'Word_UnderscoreToCamelCase'));
            if (method_exists($this, $populateMethodName)) {
                $values = call_user_func(array($this, $populateMethodName), $values);
            }
        }

        return parent::populate($values);
    }

    /**
     * @param Entities\AbstractEntity $entity
     * @return Sch_Form
     */
    public function populateEntity(\Entities\AbstractEntity $entity)
    {
        $values = array();
        foreach ($this->getElements() as $elementName => /** @var Zend_Form_Element $element */ $element) {
            $populateMethodName = '_populate' . ucfirst(Zend_Filter::filterStatic($elementName, 'Word_UnderscoreToCamelCase')) . 'Entity';
            if (method_exists($this, $populateMethodName)) {
                $values[$elementName] = call_user_func(array($this, $populateMethodName), $entity);
                continue;
            }
            if (isset($entity->{$elementName}) && $value = $entity->{$elementName}) {
                if ($value instanceof \Entities\AbstractEntity && method_exists($value, 'getId')) {
                    $value = $value->getId();
                }
                $values[$elementName] = $value;
            }
        }
        foreach ($this->getSubForms() as $subFormName => /** @var Zend_Form_SubForm $subForm */ $subForm) {
            $populateMethodName = '_populate' . ucfirst(Zend_Filter::filterStatic($subFormName, 'Word_UnderscoreToCamelCase')) . 'Entity';
            if (method_exists($this, $populateMethodName)) {
                $values[$subFormName] = call_user_func(array($this, $populateMethodName), $entity);
                continue;
            }
            if (isset($entity->{$subFormName})) {
                $data = $entity->{$subFormName};
                if ($subForm instanceof Sch_Form && $data instanceof \Entities\AbstractEntity) {
                    $subForm->populateEntity($entity->{$subFormName});
                }
                else {
                    $values[$subFormName] = $entity->{$subFormName};
                }
            }
        }
        return $this->populate($values);
    }

    /**
     * @param null|\Zend_View_Interface $view
     * @return string
     */
    public function render(Zend_View_Interface $view = null)
    {
        if (method_exists($this, 'prepareDecorators') && !$this->_prepared) {
            $this->prepareDecorators();
        }
        return parent::render($view);
    }

    /**
     * @return Sch_Form
     */
    protected function _prepareElementsDecorators()
    {
        foreach ($this->getElements() as $elementName => $element) {
            $prepareMethodName = '_prepare' . ucfirst(trim(Zend_Filter::filterStatic($elementName, 'Word_UnderscoreToCamelCase'), '_')) . 'Decorators';
            if (method_exists($this, $prepareMethodName)) {
                call_user_func(array($this, $prepareMethodName), $element);
            }
        }
        return $this;
    }

    /**
     * @return Sch_Form
     */
    protected function _prepareSubformsDecorators()
    {
        foreach ($this->getSubForms() as $subFormName => /** @var Zend_Form $subForm */
                 $subForm) {
            if (method_exists($subForm, 'prepareDecorators')) {
                call_user_func(array($subForm, 'prepareDecorators'));
            }
            $prepareMethodName = '_prepare' . ucfirst(Zend_Filter::filterStatic($subFormName, 'Word_UnderscoreToCamelCase')) . 'SubformDecorators';
            if (method_exists($this, $prepareMethodName)) {
                call_user_func(array($this, $prepareMethodName), $subForm);
            }
            if ($subForm->getDecorator('Zend_Form_Decorator_Form')) {
                $subForm->removeDecorator('Zend_Form_Decorator_Form');
            }
            if ($subForm->getDecorator('Form')) {
                $subForm->removeDecorator('Form');
            }
        }
        return $this;
    }

}
