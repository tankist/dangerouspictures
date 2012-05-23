<?php

class Admin_Form_Login extends Sch_Form
{

    public function init()
    {
        $return = new Zend_Form_Element_Hidden('return');

        $email = new Zend_Form_Element_Text('email');
        $email->setRequired(true);

        $password = new Zend_Form_Element_Password('password');
        $password->setRequired(true);

        $remember = new Zend_Form_Element_Checkbox('remember');

        $this->addElements(array($return, $email, $password, $remember));
    }

    public function prepareDecorators()
    {
        $this->setElementDecorators(array(
            array('decorator' => 'ViewHelper', 'options' => array('separator' => ''))
        ));
        $this->setDecorators(array(
            new Sch_Form_Decorator_Twitter_FormErrors(),
            new Sch_Form_Decorator_ViewScript(array(
                'viewScript' => 'forms/login.phtml'
            )),
            'Form'
        ));
        return parent::prepareDecorators();
    }

    public function _prepareRememberDecorators(Zend_Form_Element_Checkbox $remember)
    {
        $remember->setDecorators(array('ViewHelper'));
    }

    public function _prepareReturnDecorators(Zend_Form_Element_Hidden $return)
    {
        $return->setDecorators(array('ViewHelper'));
    }

}

