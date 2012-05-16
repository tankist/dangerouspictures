<?php

class Sch_Form_Decorator_Twitter_Checkbox extends Zend_Form_Decorator_ViewHelper
{

    public function getElementAttribs()
    {
        if (null === ($element = $this->getElement())) {
            return null;
        }
        $attribs = parent::getElementAttribs();
        $attribs['label'] = $element->getLabel();
        return $attribs;
    }

}
