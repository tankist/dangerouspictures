<?php

use Doctrine\Common\Cache\Cache as DoctrineCache, Doctrine\ORM\EntityRepository;

class Cache
{

    /**
     * @var DoctrineCache
     */
    protected $_cache;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $_repository;

    /**
     * @param DoctrineCache $cache
     * @return Cache
     */
    public function setCache(DoctrineCache $cache)
    {
        $this->_cache = $cache;
        return $this;
    }

    /**
     * @return DoctrineCache
     */
    public function getCache()
    {
        return $this->_cache;
    }

    public function __call($name, $arguments)
    {
        if (strpos($name, 'find') === 0) {

        }
    }

}
