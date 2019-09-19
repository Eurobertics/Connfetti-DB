<?php
namespace Connfetti\Db\Driver;


use Connfetti\Db\Exception\DriverException;
use Connfetti\Db\Exception\QueryException;

interface DriverInterface
{
    /** @throws DriverException */
    public function checkEnvironment();
    /** @throws DriverException */
    public function connection();
    public function close();
    public function escStr($str);
    /** @throws QueryException */
    public function query(string $querystring);
    public function preparedQuery(string $querystring, array $params);
    public function preparedQueryParams(array $params);
    /** @throws QueryException */
    public function executePreparedQuery();
    public function closePreparedQuery();
    public function lastInsertId();
    public function version();
}
