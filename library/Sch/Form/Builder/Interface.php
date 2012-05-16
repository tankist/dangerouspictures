<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Natali
 * Date: 03.03.12
 * Time: 12:33
 * To change this template use File | Settings | File Templates.
 */
interface Sch_Form_Builder_Interface
{

    /**
     * @abstract
     * @param Zend_Form $form
     * @return Zend_Form_Element[]
     */
    public function getElements(Zend_Form $form);

    /**
     * @abstract
     * @param Zend_Form $form
     * @return Zend_Form[]
     */
    public function getSubforms(Zend_Form $form);

    /**
     * @abstract
     * @param Zend_Form $form
     */
    public function prepareDecorators(Zend_Form $form);

}
