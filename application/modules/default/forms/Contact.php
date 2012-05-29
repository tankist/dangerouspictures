<?php

class Default_Form_Contact extends Sch_Form
{

    public function init()
    {
        $email = new Zend_Form_Element_Text('email');
        $email->addValidator('EmailAddress');
        $email->setRequired(true);

        $fullname = new Zend_Form_Element_Text('fullname');
        $fullname->setRequired(true);

        $subject = new Zend_Form_Element_Text('subject');
        $subject->setRequired(true);

        $message = new Zend_Form_Element_Textarea('message');
        $message->setRequired(true);

        $this->addElements(array($fullname, $email, $subject, $message));
    }

    public function prepareDecorators()
    {
        $this->setDecorators(array(
            'FormErrors',
            new Sch_Form_Decorator_ViewScript(array('viewScript' => 'forms/contact.phtml')),
            'Fieldset',
            'Form'
        ));
        $this->setElementDecorators(array(
            'ViewHelperTwitter'
        ));
        return parent::prepareDecorators();
    }

}

