<?php
namespace Connfetti\Db\Sql\Builder\Mysql;


use Connfetti\Db\Adapter\Adapter;
use Connfetti\Db\Sql\Builder\BuilderAbstract;
use Connfetti\Db\Sql\Builder\BuilderInterface;

class DeleteBuilder extends BuilderAbstract implements BuilderInterface
{
    private static $VERSION = '1.0.3';

    public function __construct(array $querydata, Adapter $adapter)
    {
        parent::__construct($querydata, $adapter);
        $this->sqlstring = "DELETE ";
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    private function setTable()
    {
        $this->sqlstring .= "FROM ".$this->table." ";
    }

    private function setWhere()
    {
        if(!$this->haswhere) {
            return;
        }
        $this->sqlstring .= "WHERE ";
        for($i = 0; $i < count($this->where); $i++)
        {
            if(is_array($this->where[$i])){
                if(count($this->where[$i]) == 2) {
                    if($this->where[$i][1] == 'notnull') {
                        $this->sqlstring .= $this->where[$i][0]." IS NOT NULL ";
                    }
                    if($this->where[$i][1] == 'null') {
                        $this->sqlstring .= $this->where[$i][0]." IS NULL ";
                    }
                } else {
                    if($this->where[$i][1] == 'in') {
                        $this->sqlstring .= $this->where[$i][0]." IN(".implode(',', $this->where[$i][2]).") ";
                    } elseif($this->where[$i][1] == 'between') {
                        $this->sqlstring .= $this->where[$i][0] . " BETWEEN " . $this->where[$i][2]. " AND ".$this->where[$i][3]." ";
                    } else {
                        $this->sqlstring .= $this->where[$i][0] . " " . $this->where[$i][1] . " " . ((is_int($this->where[$i][2]) || $this->where[$i][2] == '?') ? $this->where[$i][2] : "'" . $this->escStr($this->where[$i][2]) . "'") . " ";
                    }
                }
            } else {
                $this->sqlstring .= $this->where[$i]." ";
            }
        }
    }

    public function getAsString()
    {
        $this->setTable();
        $this->setWhere();
        return substr($this->sqlstring, 0, -1);
    }

    public static function version()
    {
        return self::$VERSION;
    }
}
