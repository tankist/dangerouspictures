<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Victor
 * Date: 15.02.12
 * Time: 16:33
 * To change this template use File | Settings | File Templates.
 */
class Sch_Twitter_View_Helper_FormMultiCheckbox extends Zend_View_Helper_FormMultiCheckbox
{
    public function formMultiCheckbox($name, $value = null, $attribs = null,
                                      $options = null, $listsep = "<br />\n")
    {
        if (!is_array($attribs)) {
            $attribs = array();
        }
        $attribs['label_class'] = 'checkbox';
        $output = parent::formMultiCheckbox($name, $value, $attribs, $options, "\n");
        $output = '<div class="controls">' . $output . '</div>';
        return $output;
    }


}
