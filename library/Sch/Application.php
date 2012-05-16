<?php

require_once 'Zend/Application.php';

class Sch_Application extends Zend_Application
{

    /**
     * @var Zend_Cache_Core
     */
    protected $_configCache;

    /**
     * @param string $environment
     * @param array|Zend_Config $options
     * @param Zend_Cache_Core $cache
     */
    public function __construct($environment, $options = null, Zend_Cache_Core $cache = null)
    {
        $this->setConfigCache($cache);
        parent::__construct($environment, $options);
    }

    /**
     * @param Zend_Cache_Core $configCache
     * @return Skaya_Application
     */
    public function setConfigCache(Zend_Cache_Core $configCache)
    {
        $this->_configCache = $configCache;
        return $this;
    }

    /**
     * @return Zend_Cache_Core
     */
    public function getConfigCache()
    {
        return $this->_configCache;
    }

    /**
     * Get cache ID for save/retrieve cached config
     * @param  $file
     * @return string
     */
    protected function _cacheId($file)
    {
        return md5($file . '_' . $this->getEnvironment());
    }

    /**
     * @param $file
     * @return array|false|mixed
     */
    protected function _loadConfig($file)
    {
        $suffix = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!($cache = $this->getConfigCache()) || in_array($suffix, array('php', 'inc'))) {
            return $this->_loadConfigFromFile($file);
        }
        $cache_id = $this->_cacheId($file);
        $configLastModified = filemtime($file);
        $cacheLastModified = $cache->test($cache_id);
        if ($cacheLastModified !== false && $cacheLastModified > $configLastModified) {
            return $cache->load($cache_id);
        }
        $config = $this->_loadConfigFromFile($file);
        $cache->save($config, $cache_id, array());
        return $config;
    }

    /**
     * @param $file
     * @return array|Zend_Config_Writer_Yaml
     */
    protected function _loadConfigFromFile($file)
    {
        $suffix = pathinfo($file, PATHINFO_EXTENSION);
        $suffix = ($suffix === 'dist')
            ? pathinfo(basename($file, ".$suffix"), PATHINFO_EXTENSION)
            : $suffix;

        switch ($suffix) {
            case 'yml':
            case 'yaml':
                $yamlConfigOptions = array();
                if (function_exists('yaml_parse')) {
                    $parser = function($yaml) {
                        $constants = array_keys(get_defined_constants());
                        rsort($constants, SORT_STRING);
                        $values = array_map('constant', $constants);
                        $yaml = str_replace($constants, $values, $yaml);
                        return yaml_parse($yaml);
                    };
                    $yamlConfigOptions['yaml_decoder'] = $parser;
                }
                $config = new Zend_Config_Yaml($file, $this->getEnvironment(), $yamlConfigOptions);
                $config = $config->toArray();
                break;
            default:
                $config = parent::_loadConfig($file);
        }

        return $config;
    }

}
