<?php

class Root_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{

    /**
     * @var string
     */
    protected $_module = '';

    /**
     * @var string
     */
    protected $_passwordFile = '';

    public function __construct($module, $passwordFile)
    {
        $this->setModule($module);
        $this->setPasswordFile($passwordFile);
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     */
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        if ($this->getModule() == $this->getRequest()->getModuleName()) {
            $auth = Zend_Auth::getInstance();

            if (!$auth->hasIdentity()) {

                $config = array(
                    'accept_schemes' => 'basic',
                    'realm' => 'Root restricted Area',
                    'nonce_timeout' => 3600,
                );
                $adapter = new Zend_Auth_Adapter_Http($config);
                $adapter->setRequest($this->getRequest());
                $adapter->setResponse($this->getResponse());

                $basicResolver = new Zend_Auth_Adapter_Http_Resolver_File();
                $basicResolver->setFile($this->getPasswordFile());
                $adapter->setBasicResolver($basicResolver);

                $result = $auth->authenticate($adapter);
                if (!$result->isValid()) {
                    $adapter->getResponse()->sendResponse();
                    print 'Unauthorized';
                    exit;
                }

            }
        }
    }

    /**
     * @param string $module
     * @return Root_Plugin_Auth
     */
    public function setModule($module)
    {
        $this->_module = $module;
        return $this;
    }

    /**
     * @return string
     */
    public function getModule()
    {
        return $this->_module;
    }

    /**
     * @param string $passwordFile
     * @return Root_Plugin_Auth
     */
    public function setPasswordFile($passwordFile)
    {
        $this->_passwordFile = $passwordFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordFile()
    {
        return $this->_passwordFile;
    }

}
