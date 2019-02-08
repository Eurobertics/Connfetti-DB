<?php
namespace Connfetti\Db\Driver;


abstract class DriverAbstract
{
    private $runable = false;

    public function __construct()
    {
    }

    public function __destruct()
    {
    }

    protected function setIsRunable()
    {
        $this->runable = true;
    }

    protected function setNotRunable() {
        $this->runable = false;
    }

    public function runableState() {
        return $this->runable;
    }

    abstract public function init();
    abstract public function unload();
    abstract public function query(string $querystring);
    abstract public function prepareableQuery(string $querystring, array $params);
    abstract public function preparedQueryParams(array $params);
    abstract public function executePreparedQuery();
    abstract public function closePreparedQuery();
    abstract public function version();
}
