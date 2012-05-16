<?php

class Sch_View_Helper_FormErrors extends Zend_View_Helper_FormErrors
{

    protected $_htmlElementEnd = '</li></ul>';
    protected $_htmlElementStart = '<ul%s><li>';
    protected $_htmlElementSeparator = '</li><li>';

    /**
     * Render form errors
     *
     * @param  string|array $errors Error(s) to render
     * @param  array $options
     * @return string
     */
    public function formErrors($errors, array $options = null)
    {
        $escape = true;
        if (isset($options['escape'])) {
            $escape = (bool)$options['escape'];
            unset($options['escape']);
        }

        if (empty($options['class'])) {
            $options['class'] = 'errors';
        }

        $start = $this->getElementStart();
        if (strstr($start, '%s')) {
            $attribs = $this->_htmlAttribs($options);
            $start = sprintf($start, $attribs);
        }

        if ($escape) {
            foreach ($errors as $key => $error) {
                $errors[$key] = $this->view->escape($error);
            }
        }

        $html = $start
            . implode($this->getElementSeparator(), (array)$errors)
            . $this->getElementEnd();

        return $html;
    }

}
