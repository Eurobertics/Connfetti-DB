<?php
namespace Connfetti\Db\Driver;


use Connfetti\Db\Exception\DriverException;
use Connfetti\Db\Exception\QueryException;

class Driver extends DriverAbstract
{
    private $config = array();

    /** @var \Connfetti\Db\Driver\DriverInterface */
    private $platform = null;

    /** @throws DriverException */
    public function __construct($config)
    {
        parent::__construct();
        if(is_array($config)) {
            $this->config = $config;
        }

        $platformns = 'Connfetti\\Db\\Driver\\Platform\\' . $this->config['driver'];
        $this->platform = new $platformns($this->config);
        try {
            $this->platform->checkEnvironment();
        } catch(DriverException $e) {
            $this->setNotRunable();
            throw $e;
        }
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    /** @throws DriverException */
    public function init()
    {
        try {
            $this->platform->connection();
            $this->setIsRunable();
        } catch(DriverException $e) {
            $this->setNotRunable();
            throw $e;
        }
    }

    public function escStr(string $str)
    {
        return $this->platform->escStr($str);
    }

    public function unload()
    {
        $this->platform->close();
        $this->setNotRunable();
    }

    /** @throws QueryException */
    public function query(string $querystring)
    {
        try {
            $result = $this->platform->query($querystring);
        } catch(QueryException $e) {
            throw $e;
        }
        if(is_array($result)) {
            return new \Connfetti\Db\ResultSet\ResultSet($result);
        } else {
            return $result;
        }
    }

    public function prepareableQuery(string $querystring, array $params)
    {
        $this->platform->preparedQuery($querystring, $params);
    }

    public function preparedQueryParams(array $params)
    {
        $this->platform->preparedQueryParams($params);
    }

    /** @throws QueryException */
    public function executePreparedQuery()
    {
        $result = null;
        try {
            $result = $this->platform->executePreparedQuery();
        } catch(QueryException $e) {
            throw $e;
        }
        if(is_array($result)) {
            return new \Connfetti\Db\ResultSet\ResultSet($result);
        } else {
            return $result;
        }
    }

    public function closePreparedQuery()
    {
        $this->platform->closePreparedQuery();
    }


}
