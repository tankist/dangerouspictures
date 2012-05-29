<?php

class Admin_Form_Account extends Sch_Form
{

    public function init()
    {
        $email = new Zend_Form_Element_Text('email');
        $email->addValidator('EmailAddress');
        $email->setRequired(true);

        $password = new Zend_Form_Element_Password('password');
        $password->setRequired(true);
        $password->addValidator(new Zend_Validate_StringLength(6));

        $confirm = new Zend_Form_Element_Password('confirm');
        $confirm->setRequired(true);
        $confirm->addValidator(new Sch_Validate_PasswordConfirm('password'));
        $confirm->addValidator(new Zend_Validate_StringLength(6));

        $twitter = new Zend_Form_Element_Text('twitter');
        $facebook = new Zend_Form_Element_Text('facebook');
        $vimeo = new Zend_Form_Element_Text('vimeo');

        $this->addElements(array($email, $password, $confirm, $twitter, $facebook, $vimeo));
    }

    public function prepareDecorators()
    {
        $this->setDecorators(array(
            'FormErrors',
            new Sch_Form_Decorator_ViewScript(array('viewScript' => 'forms/account.phtml')),
            'Fieldset',
            'Form'
        ));
        $this->setElementDecorators(array(
            'ViewHelperTwitter'
        ));
        return parent::prepareDecorators();
    }

}

