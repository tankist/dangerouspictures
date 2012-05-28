<?php

class Plugin_Acl extends Zend_Controller_Plugin_Abstract {

    protected $_defaultRole = 0;

    /**
     * @var Zend_Acl
     */
    protected $_acl;

    protected $_aclRoute = '%s.%s';

    /**
     * @var \Entities\User
     */
    protected $_user;

    /**
     * Location to go to if the user isn't authenticated
     * @var array
     */
    protected $_noAuth = array(
        'module' => 'admin',
        'controller' => 'auth',
        'action' => 'login'
    );

    /**
     * Location to go to if the user isn't activated
     * @var array
     */
    protected $_noActivated = array(
        'module' => 'admin',
        'controller' => 'auth',
        'action' => 'reactivation'
    );

    /**
     * Location to go to if the user isn't authorized
     * @var array
     */
    protected $_noAcl = array(
        'module' => 'admin',
        'controller' => 'auth',
        'action' => 'login'
    );

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = Zend_Controller_Action_HelperBroker::getStaticHelper('CurrentUser')->getCurrentUser();
        }
        return $this->_user;
    }

    public function isRegistered() {
        return (null != $this->getUser());
    }

    public function getRole() {
        if ($user = $this->getUser()) {
            return $user->getRole();
        }
        return $this->_defaultRole;
    }

    public function isAllowed($resource = null, $privelege = null) {
        return $this->_acl->isAllowed($this->getRole(), $resource, $privelege);
    }

    public function direct() {
        return $this->getUser();
    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request) {
        $this->_acl = $this->_getAcl();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $module = $request->getModuleName();

        $resource = sprintf($this->_aclRoute, $module, $controller);
        if (!$this->_acl->has($resource)) {
            $resource = $module;
        }
        if (!$this->_acl->has($resource)) {
            $resource = null;
        }
        if ($this->isAllowed($resource, $action)) {
            $user = $this->getUser();
            return;
        }

        // Auth fail

        if (!$request->getParam('return')) {
            $returnUrl = urlencode($request->getRequestUri());
            $request->setParam('return', $returnUrl);
        }

        if ($this->isRegistered()) {
            if ($this->_user->getStatus() != 'Active') {
                $module = $this->_noActivated['module'];
                $controller = $this->_noActivated['controller'];
                $action = $this->_noActivated['action'];
            } else {
                $module = $this->_noAcl['module'];
                $controller = $this->_noAcl['controller'];
                $action = $this->_noAcl['action'];
            }
        } else {
            $module = $this->_noAuth['module'];
            $controller = $this->_noAuth['controller'];
            $action = $this->_noAuth['action'];
        }

        $request
            ->setModuleName($module)
            ->setControllerName($controller)
            ->setActionName($action)
            ->setDispatched(false);
    }

    protected function _getAcl() {
        $front = Zend_Controller_Front::getInstance();
        /** @var $bootstrap Zend_Application_Bootstrap_Bootstrap */
        if (!$bootstrap = $front->getParam('bootstrap')) {
            throw new RuntimeException('Bootstrap not found');
        }
        if (!($acl = $bootstrap->getResource('acl')) || !($acl instanceof Zend_Acl)) {
            throw new RuntimeException('Can not load ACL object.');
        }
        return $acl;
    }

}