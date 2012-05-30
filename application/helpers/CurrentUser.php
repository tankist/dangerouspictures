<?php

class Helper_CurrentUser extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * @var \Entities\User
     */
    protected $_currentUser;

    /**
     * @var stdClass
     */
    protected $_identity;

    public function getCurrentUser()
    {
        if (!$this->_currentUser) {
            if (($this->_currentUser = $this->getFrontController()->getParam('user'))) {
                return $this->_currentUser;
            }
            $identity = Zend_Auth::getInstance()->getIdentity();
            if (!$this->_currentUser or $identity != $this->_identity) {
                $this->_identity = $identity;
                /** @var $emHelper Helper_Em */
                if (!($emHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('Em'))) {
                    throw new Zend_Controller_Action_Exception('Entity manager cannot be found');
                }
                $manager = new Service_User($emHelper->getEntityManager());
                $user = is_string($identity) ? $manager->getByUsername($identity) : null;
                $this->_currentUser = $user;
                if ($user) {
                    $now = new DateTime();
                    if ($user->getOnlineLast() &&
                        ($now->getTimestamp() - $user->getOnlineLast()->getTimestamp()) > 15 * 60) {
                        $user->setOnlineLast(new DateTime());
                        $manager->save($user);
                    }
                }
                else {
                    Zend_Auth::getInstance()->clearIdentity();
                }
                $this->getFrontController()->setParam('user', $this->_currentUser);
            }
        }
        return $this->_currentUser;
    }

    public function direct()
    {
        return $this->getCurrentUser();
    }

}
