<?php

class Sch_Twitter_View_Helper_FormCheckbox extends Zend_View_Helper_FormCheckbox
{

    public function formCheckbox($name, $value = null, $attribs = null, array $checkedOptions = null)
    {
        return sprintf(
            '<label class="checkbox">%s%s</label>',
            parent::formCheckbox($name, $value, $attribs, $checkedOptions),
            array_key_exists('label', $attribs)? $attribs['label'] : '');
    }

}