<?php
namespace Connfetti\Db\Sql\Builder\Mysql;


use Connfetti\Db\Adapter\Adapter;
use Connfetti\Db\Sql\Builder\BuilderAbstract;
use Connfetti\Db\Sql\Builder\BuilderInterface;

class SelectBuilder extends BuilderAbstract implements BuilderInterface
{
    private static $VERSION = '1.0.1';

    public function __construct(array $querydata, Adapter $adapter)
    {
        parent::__construct($querydata, $adapter);
        $this->sqlstring = "SELECT ";

    }

    public function __destruct()
    {
        parent::__destruct();
    }

    private function setColumns()
    {
        if($this->columns == "*") {
            $this->sqlstring .= "* ";
        } else {
            $cols = "";
            for($i = 0; $i < count($this->columns); $i++) {
                $cols .= $this->columns[$i].",";
            }
            $this->sqlstring .= substr($cols, 0, -1)." ";
        }
    }

    private function setTable()
    {
        $this->sqlstring .= "FROM ".$this->table." ";
    }

    private function setJoin()
    {
        if(!$this->hasjoin) {
            return;
        }
        $this->sqlstring .= $this->join." JOIN ";
        $this->sqlstring .= $this->jointable." ";
        $this->sqlstring .= "ON ".$this->joinlink." ";
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
                    } else {
                        $this->sqlstring .= $this->where[$i][0] . " " . $this->where[$i][1] . " " . ((is_int($this->where[$i][2]) || $this->where[$i][2] == '?') ? $this->where[$i][2] : "'" . $this->escStr($this->where[$i][2]) . "'") . " ";
                    }
                }
            } else {
                $this->sqlstring .= $this->where[$i]." ";
            }
        }
    }

    private function setHaving()
    {
        if(!$this->hashaving) {
            return;
        }
        $this->sqlstring .= "HAVING ".$this->having[0]." ".$this->having[1]." ".$this->having[2]." ";
    }

    private function setGroupBy()
    {
        if(!$this->hasgroupby) {
            return;
        }
        $gb = "";
        for($i = 0; $i < count($this->groupby); $i++) {
            $gb .= $this->groupby[$i].",";
        }
        $this->sqlstring .= "GROUP BY ".substr($gb, 0, -1)." ";
    }

    private function setOrderBy()
    {
        if(!$this->hasorderby) {
            return;
        }
        $this->sqlstring .= "ORDER BY ".implode(",", $this->ordercols)." ".$this->order." ";
    }

    private function setLimit()
    {
        if($this->limit1 != "") {
            $this->sqlstring .= "LIMIT " . $this->limit1;
            if ($this->limit2 !== false) {
                $this->sqlstring .= ", " . $this->limit2;
            }
            $this->sqlstring .= " ";
        }
    }

    private function setUnion()
    {
        if(!$this->hasunion) {
            return;
        }
        $builder = new self($this->union, $this->adapter);
        $this->sqlstring .= "UNION ".$builder->getAsString();
    }

    public function getAsString()
    {
        $this->setColumns();
        $this->setTable();
        $this->setJoin();
        $this->setWhere();
        $this->setHaving();
        $this->setGroupBy();
        $this->setOrderBy();
        $this->setLimit();
        $this->setUnion();
        return substr($this->sqlstring, 0, -1);
    }

    public static function version()
    {
        return self::$VERSION;
    }
}
