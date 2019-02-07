<?php
namespace Connfetti\Db\Driver;


interface DriverInterface
{
    public function checkEnvironment();
    public function connection();
    public function close();
    public function escStr($str);
    public function query(string $querystring);
    public function preparedQuery(string $querystring, array $params);
    public function preparedQueryParams(array $params);
    public function executePreparedQuery();
    public function closePreparedQuery();
}
