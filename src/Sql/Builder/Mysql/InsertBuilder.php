<?php
namespace Connfetti\Db\Sql\Builder\Mysql;


use Connfetti\Db\Adapter\Adapter;
use Connfetti\Db\Sql\Builder\BuilderAbstract;
use Connfetti\Db\Sql\Builder\BuilderInterface;

class InsertBuilder extends BuilderAbstract implements BuilderInterface
{
    public function __construct(array $querydata, Adapter $adapter)
    {
        parent::__construct($querydata, $adapter);
        $this->sqlstring = "INSERT ";
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function setTable()
    {
        $this->sqlstring .= "INTO ".$this->table." ";
    }

    public function setColumns()
    {
        $cols = $vals = "";
        foreach($this->columns as $key => $value) {
            $value = $this->escStr($value);
            $v = "";
            if(is_int($value) || $value == '?') {
                $v = $value;
            } else {
                $v = "'".$value."'";
            }
            $cols .= $key.",";
            $vals .= $v.",";
        }
        $this->sqlstring .= "(".substr($cols, 0, -1).") VALUES (".substr($vals, 0, -1).") ";
    }

    public function getAsString()
    {
        $this->setTable();
        $this->setColumns();
        return substr($this->sqlstring, 0, -1);
    }


}
