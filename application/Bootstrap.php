<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initModule()
    {
        $loader = $this->getResourceLoader();
        $loader
            ->addResourceType('filter', 'filters', 'Filter')
            ->addResourceType('helper', 'helpers', 'Helper');

        return $loader;
    }

    protected function _initAcl()
    {
        return include APPLICATION_PATH . '/configs/acl.php';
    }

    public function _initAutoloaderNamespaces()
    {
        require_once 'Doctrine/Common/ClassLoader.php';

        $autoloader = \Zend_Loader_Autoloader::getInstance();

        $doctrineAutoloader = new \Doctrine\Common\ClassLoader('Doctrine');
        $autoloader->pushAutoloader(array($doctrineAutoloader, 'loadClass'), 'Doctrine');

        $symfonyAutoloader = new \Doctrine\Common\ClassLoader('Symfony');
        $autoloader->pushAutoloader(array($symfonyAutoloader, 'loadClass'), 'Symfony');

        $doctrineExtensionsAutoloader = new \Doctrine\Common\ClassLoader('DoctrineExtensions');
        $autoloader->pushAutoloader(array($doctrineExtensionsAutoloader, 'loadClass'), 'DoctrineExtensions');

        $fmmAutoloader = new \Doctrine\Common\ClassLoader('Bisna');
        $autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), 'Bisna');

        $fmmAutoloader = new \Doctrine\Common\ClassLoader('Boilerplate');
        $autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), 'Boilerplate');

        $modelsPath = realpath(APPLICATION_PATH . '/models');

        $fmmAutoloader = new \Doctrine\Common\ClassLoader('Entities', $modelsPath);
        $autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), 'Entities');

        $fmmAutoloader = new \Doctrine\Common\ClassLoader('Repository', $modelsPath);
        $autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), 'Repository');
    }

    public function _initModuleLayout()
    {
        $this->bootstrap('frontController');
        /** @var $front Zend_Controller_Front */
        $front = $this->getResource('frontController');

        $front->registerPlugin(
            new Boilerplate_Controller_Plugin_ModuleLayout()
        );
    }

    protected function _initDoctrineLogger()
    {
        $this->bootstrap('doctrine');
        /** @var $doctrine \Bisna\Doctrine\Container */
        $doctrine = $this->getResource('doctrine');
        $logger = null;
        if (APPLICATION_ENV == 'development') {
            $logger = new \Sch\Doctrine\Logger\Firebug();
            $doctrine->getEntityManager()->getConfiguration()->setSQLLogger($logger);
        }
        return $logger;
    }

    protected function _initViewHelpers()
    {
        $this->bootstrap('view');
        /** @var $view Zend_View_Abstract */
        $view = $this->getResource('view');
        $view->getHelper('HeadTitle')->setSeparator(' - ')->headTitle('DangerousPictures');
    }

    protected function _initTwitter()
    {
        Sch_Twitter_View_Helper_FlashMessages::setVersion(Sch_Twitter_View_Helper_FlashMessages::TWITTER_VERSION_2);
    }

    protected function _initRoutes()
    {
        $this->bootstrap('frontcontroller');
        /** @var $front Zend_Controller_Front */
        $front = $this->getResource('frontcontroller');
        /** @var $router Zend_Controller_Router_Rewrite */
        $router = $front->getRouter();
    }

}

