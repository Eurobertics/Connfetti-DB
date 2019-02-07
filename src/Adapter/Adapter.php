<?php
namespace Connfetti\Db\Adapter;

use Connfetti\Db\Driver\Driver;
use Connfetti\Db\Exception\DriverException;
use Connfetti\Db\Exception\QueryException;

class Adapter
{
    const QUERY_EXECUTE = 0;
    const QUERY_AS_STRING = 1;
    const PREPARED_STATEMENT = 2;
    const DEFAULT_QUERY = 3;

    private $config = array();

    /** @var \Connfetti\Db\Driver\Driver */
    private $driver = null;

    /** @throws DriverException */
    public function __construct($config)
    {
        $this->config = array('driver' => '', 'host' => '', 'user' => 'pass', 'db' => 'db');

        if(is_array($config)) {
            if(isset($config['driver'])) {
                $this->config['driver'] = $config['driver'];
            }
            if(isset($config['host'])) {
                $this->config['host'] = $config['host'];
            }
            if(isset($config['user'])) {
                $this->config['user'] = $config['user'];
            }
            if(isset($config['pass'])) {
                $this->config['pass'] = $config['pass'];
            }
            if(isset($config['db'])) {
                $this->config['db'] = $config['db'];
            }
        }

        try {
            $driver = new Driver($this->config);
            $this->setDriver($driver);
        } catch(DriverException $e) {
            throw $e;
        }
    }

    public function __destruct()
    {
        $this->config = array('driver' => '', 'host' => '', 'user' => 'pass', 'db' => 'db');
        $this->driver->unload();
        $this->driver = null;
    }

    /** @throws DriverException */
    public function setDriver(Driver $driver)
    {
        $this->driver = $driver;
        try {
            $this->driver->init();
        } catch(DriverException $e) {
            if(!$this->driver->runableState()) {
                throw new DriverException("Driver '".$this->config['driver']."' is not ready!", 2, $e);
            } else {
                throw $e;
            }
        }
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function getPlatform()
    {
        return $this->config['driver'];
    }

    public function escStr(string $str)
    {
        return $this->driver->escStr($str);
    }

    /** @throws QueryException */
    public function query(string $querystring)
    {
        $res = null;
        try {
            $res = $this->driver->query($querystring);
        } catch(QueryException $e) {
            throw $e;
        }
        return $res;
    }

    public function prepareableQuery(string $querystring, array $params)
    {
        $this->driver->prepareableQuery($querystring, $params);
    }

    public function preparedQueryParams(array $params)
    {
        $this->driver->preparedQueryParams($params);
    }

    /** @throws QueryException */
    public function executePreparedQuery()
    {
        $res = null;
        try {
            $res = $this->driver->executePreparedQuery();
        } catch(QueryException $e) {
            throw $e;
        }
        return $res;
    }

    public function closePreparedQuery()
    {
        $this->driver->closePreparedQuery();
    }
}
