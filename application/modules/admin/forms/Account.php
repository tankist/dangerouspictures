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
            new Sch_Form_Decorator_ViewScript(array('viewScript' => 'forms/account.phtml')),
            'Form'
        ));
        foreach ($this->getElements() as /** @var Zend_Form_Element $element */ $element) {
            $element->addDecorators(array(
                'dl' => new Zend_Form_Decorator_HtmlTag(array('tag' => 'dl'))
            ));
        }
        return parent::prepareDecorators();
    }

}

