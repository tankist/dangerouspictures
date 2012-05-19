<?php

class Root_Form_Root extends Sch_Form
{

    public function init()
    {
        $email = new Zend_Form_Element_Text('email');
        $email->setRequired(true);
        $email->addValidator('EmailAddress');

        $password = new Zend_Form_Element_Password('password');
        $password->setRequired(true);
        $password->addValidator(new Zend_Validate_StringLength(6));

        $confirm = new Zend_Form_Element_Password('confirm');
        $confirm->setRequired(true);
        $confirm->addValidator(new Sch_Validate_PasswordConfirm('password'));
        $confirm->addValidator(new Zend_Validate_StringLength(6));

        $this->addElements(array($email, $password, $confirm));
    }

    public function prepareDecorators()
    {
        $this->setDecorators(array(
            'FormErrors',
            new Sch_Form_Decorator_ViewScript(array('viewScript' => 'forms/root.phtml')),
            'Fieldset',
            'Form'
        ));
        $this->setElementDecorators(array(
            'ViewHelperTwitter'
        ));
        return parent::prepareDecorators();
    }

}