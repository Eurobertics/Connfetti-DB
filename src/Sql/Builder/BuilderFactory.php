<?php
namespace Connfetti\Db\Sql\Builder;


class BuilderFactory
{
    public static function create(\Connfetti\Db\Sql\QueryInterface $query, \Connfetti\Db\Adapter\Adapter $adapter)
    {
        if($query instanceof \Connfetti\Db\Sql\Select) {
            $builderpath = "Connfetti\\Db\\Sql\\Builder\\" . $adapter->getPlatform() . "\\SelectBuilder";
        }
        if($query instanceof \Connfetti\Db\Sql\Insert) {
            $builderpath = "Connfetti\\Db\\Sql\\Builder\\" . $adapter->getPlatform() . "\\InsertBuilder";
        }
        if($query instanceof \Connfetti\Db\Sql\Update) {
            $builderpath = "Connfetti\\Db\\Sql\\Builder\\" . $adapter->getPlatform() . "\\UpdateBuilder";
        }
        if($query instanceof \Connfetti\Db\Sql\Delete) {
            $builderpath = "Connfetti\\Db\\Sql\\Builder\\" . $adapter->getPlatform() . "\\DeleteBuilder";
        }

        return new $builderpath($query->getQueryDataAsArray(), $adapter);
    }
}
