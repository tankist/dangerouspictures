<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Natali
 * Date: 03.03.12
 * Time: 12:36
 * To change this template use File | Settings | File Templates.
 */
abstract class Sch_Form_Builder_Abstract implements Sch_Form_Builder_Interface
{
    /**
     * @param Zend_Form $form
     * @return Zend_Form_Element[]
     */
    public function getElements(Zend_Form $form)
    {}

    /**
     * @param Zend_Form $form
     * @return Zend_Form[]
     */
    public function getSubforms(Zend_Form $form)
    {}

    /**
     * @param Zend_Form $form
     */
    public function prepareDecorators(Zend_Form $form)
    {}

}
