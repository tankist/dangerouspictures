<?php

class Sch_Form_Decorator_Twitter_FormErrors extends Zend_Form_Decorator_FormErrors
{

    /**
     * @var array
     */
    protected $_labels = array();

    protected $_defaults = array(
        'ignoreSubForms' => false,
        'showCustomFormErrors' => true,
        'onlyCustomFormErrors' => false,
        'markupElementLabelEnd' => '</strong>',
        'markupElementLabelStart' => '<strong>',
        'markupListEnd' => '</div>',
        'markupListItemEnd' => '',
        'markupListItemStart' => '',
        'markupListStart' => '<div class="alert block-message alert-error"><a data-dismiss="alert" class="close" href="#">×</a>',
    );

    /**
     * @param Zend_Form_Element $element
     * @param Zend_View_Interface $view
     * @return string
     */
    public function renderLabel(Zend_Form_Element $element, Zend_View_Interface $view)
    {
        $label = $element->getLabel();
        if (empty($label)) {
            $label = $element->getName();
        }

        return $this->getMarkupElementLabelStart()
            . ucfirst($view->escape($label)) . ':&nbsp'
            . $this->getMarkupElementLabelEnd();
    }

}
