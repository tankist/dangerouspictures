<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Victor
 * Date: 19.04.12
 * Time: 17:21
 * To change this template use File | Settings | File Templates.
 */
class Sch_Form_Decorator_Twitter_ViewHelperTwitter extends Zend_Form_Decorator_ViewHelper
{

    public function render($content)
    {
        $separator = $this->getSeparator();
        $content = join($separator, array('<div class="controls">', parent::render($content), '</div>'));
        if ($this->getElement()->getLabel()) {
            $labelDecorator = new Zend_Form_Decorator_Label(array('class' => 'control-label'));
            $labelDecorator->setElement($this->getElement());
            $content = $labelDecorator->render($content);
        }
        return join($separator, array('<div class="control-group">', $content, '</div>'));
    }

}
