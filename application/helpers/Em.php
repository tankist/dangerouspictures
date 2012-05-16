<?php

use \Bisna\Doctrine\Container as DoctrineContainer;

class Helper_Em extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * @var DoctrineContainer
     */
    protected $_doctrine;

    /**
     * Return entity manager
     * Could be overriden to support custom entity manager
     *
     * @param string $entityManagerName
     * @return \Doctrine\ORM\EntityManager
     * @throws Zend_Controller_Action_Exception
     */
    public function getEntityManager($entityManagerName = null)
    {
        return $this->getDoctrine()->getEntityManager($entityManagerName);
    }

    /**
     * @param string $entityManagerName
     * @return \Doctrine\ORM\EntityManager
     */
    public function direct($entityManagerName = 'default')
    {
        return $this->getEntityManager($entityManagerName);
    }

    /**
     * @return DoctrineContainer
     * @throws Zend_Controller_Action_Exception
     */
    public function getDoctrine()
    {
        if (!($this->_doctrine instanceof DoctrineContainer)) {
            /** @var $bootstrap Zend_Application_Bootstrap_Bootstrap */
            if (!($bootstrap = $this->getFrontController()->getParam('bootstrap'))) {
                throw new Zend_Controller_Action_Exception('Bootstrap not found');
            }
            /** @var $doctrine \Bisna\Doctrine\Container */
            if (!($this->_doctrine = $bootstrap->getResource('doctrine'))) {
                throw new Zend_Controller_Action_Exception('Doctrine container not found');
            }
        }
        return $this->_doctrine;
    }

}