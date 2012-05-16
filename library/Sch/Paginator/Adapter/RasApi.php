<?php

class Sch_Paginator_Adapter_RasApi implements Zend_Paginator_Adapter_Interface
{

    /**
     * @var int
     */
    protected $_count = null;

    /**
     * @var Sch_Rest_Client
     */
    protected $_client;

    /**
     * @var array
     */
    protected $_apiOptions = array();

    /**
     * @var array
     */
    protected $_parameters = array();

    /**
     * @var string
     */
    protected $_root = '';

    /**
     * @var string
     */
    protected $_endpoint = '';

    /**
     * Returns an collection of items for a page.
     *
     * @param  integer $offset Page offset
     * @param  integer $itemCountPerPage Number of items per page
     * @return array
     * @throws Zend_Paginator_Exception
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $items = array();
        $page = floor($offset / $itemCountPerPage) + 1;
        $client = $this->getClient();
        $params = $this->getParameters();
        $params['page'] = $page;
        try {
            $response = $client->restGet($this->getEndpoint(), $params);
            $options = Zend_Json::decode($response->getBody());
            if (array_key_exists($this->getRoot(), $options)) {
                $items = $options[$this->getRoot()];
            }
        }
        catch (Zend_Exception $e) {
            throw new Zend_Paginator_Exception($e->getMessage(), $e->getCode(), $e);
        }
        return $items;
    }

    /**
     * @return int|null
     */
    public function count()
    {
        if ($this->_count === null) {
            $options = $this->_getApiOptions();
            $this->_count = (is_array($options) && array_key_exists('totalCount', $options)) ? (int)$options['totalCount'] : 0;
        }
        return $this->_count;
    }

    /**
     * @param array $apiOptions
     * @return Sch_Paginator_Adapter_RasApi
     */
    protected function _setApiOptions($apiOptions)
    {
        $this->_apiOptions = $apiOptions;
        return $this;
    }

    /**
     * @return array
     * @throws Zend_Paginator_Exception
     */
    protected function _getApiOptions()
    {
        if (!$this->_apiOptions) {
            $client = $this->getClient();
            try {
                $response = $client->restOptions($this->getEndpoint(), $this->getParameters());
                $options = Zend_Json::decode($response->getBody());
                $this->_setApiOptions($options);
            }
            catch (Zend_Exception $e) {
                throw new Zend_Paginator_Exception($e->getMessage(), $e->getCode(), $e);
            }
        }
        return $this->_apiOptions;
    }

    /**
     * @param array $parameters
     * @return Sch_Paginator_Adapter_RasApi
     */
    public function setParameters($parameters)
    {
        $this->_parameters = $parameters;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * @return Sch_Rest_Client
     */
    public function getClient()
    {
        if (!$this->_client) {
            $this->_client = new Sch_Rest_Client();
            $this->_client->getHttpClient()->setHeaders('Accept', 'application/json');
        }
        return $this->_client;
    }

    /**
     * @param \Sch_Rest_Client $httpClient
     * @return Sch_Paginator_Adapter_RasApi
     */
    public function setClient(Sch_Rest_Client $httpClient)
    {
        $this->_client = $httpClient;
        return $this;
    }

    /**
     * @param string $root
     * @return Sch_Paginator_Adapter_RasApi
     */
    public function setRoot($root)
    {
        $this->_root = $root;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoot()
    {
        return $this->_root;
    }

    /**
     * @param string $endpoint
     * @return Sch_Paginator_Adapter_RasApi
     */
    public function setEndpoint($endpoint)
    {
        $this->_endpoint = $endpoint;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->_endpoint;
    }

}