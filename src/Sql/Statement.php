<?php
namespace Connfetti\Db\Sql;


use Connfetti\Db\Adapter\Adapter;
use Connfetti\Db\Exception\QueryException;

class Statement
{
    private $adapter = null;
    private $querystring = "";
    private $queryparams = array();

    public function __construct(string $query, array $params, Adapter $adapter)
    {
        $this->querystring = $query;
        $this->queryparams = $params;
        $this->adapter = $adapter;
        $this->adapter->prepareableQuery($this->querystring, $this->queryparams);
    }

    public function __destruct()
    {
        $this->adapter = null;
        $this->querystring = "";
        $this->queryparams = array();
    }

    public function bind(array $params)
    {
        $this->adapter->preparedQueryParams($params);
    }

    /** @throws QueryException */
    public function execute()
    {
        $res = null;
        try {
            $res = $this->adapter->executePreparedQuery();
        } catch(QueryException $e) {
            throw $e;
        }
        return $res;
    }

    public function close()
    {
        $this->adapter->closePreparedQuery();
    }
}
