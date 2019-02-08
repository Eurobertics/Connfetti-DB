<?php
namespace Connfetti\Db\Sql\Builder;


use Connfetti\Db\Adapter\Adapter;

interface BuilderInterface
{
    public function __construct(array $querydata, Adapter $adapter);
    public function getAsString();
}
