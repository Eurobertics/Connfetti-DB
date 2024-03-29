<?php
namespace Connfetti\Db\Sql\Builder\Mysql;


use Connfetti\Db\Adapter\Adapter;
use Connfetti\Db\Sql\Builder\BuilderAbstract;
use Connfetti\Db\Sql\Builder\BuilderInterface;

class UpdateBuilder extends BuilderAbstract implements BuilderInterface
{
    private static $VERSION = '1.0.5';

    public function __construct(array $querydata, Adapter $adapter)
    {
        parent::__construct($querydata, $adapter);
        $this->sqlstring = "UPDATE ";
    }

    public function __destruct()
    {
        parent::__destruct();
        $this->sqlstring = "";
    }

    public function setTable()
    {
        $this->sqlstring .= $this->table." ";
    }

    public function setColumns()
    {
        $cols = "";
        foreach($this->columns as $key => $value) {
            $value = $this->escStr($value);
            if(is_int($value) || $value == '?') {
                $v = $value;
            } else {
                $v = "'".$value."'";
            }
            $cols .= $key."=".$v.",";
        }
        $this->sqlstring .= "SET ".substr($cols, 0, -1)." ";
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
                        $this->sqlstring .= $this->where[$i][0]." IN(".implode(',', ((is_int($this->where[$i][2]) || $this->where[$i][2] == '?') ? $this->where[$i][2] : "'" . $this->escStr($this->where[$i][2]) . "'")).") ";
                    } elseif($this->where[$i][1] == 'notin') {
                        $this->sqlstring .= $this->where[$i][0]." NOT IN(".implode(',', ((is_int($this->where[$i][2]) || $this->where[$i][2] == '?') ? $this->where[$i][2] : "'" . $this->escStr($this->where[$i][2]) . "'")).") ";
                    } elseif($this->where[$i][1] == 'between') {
                        $this->sqlstring .= $this->where[$i][0] . " BETWEEN " . ((is_int($this->where[$i][2]) || $this->where[$i][2] == '?') ? $this->where[$i][2] : "'" . $this->escStr($this->where[$i][2]) . "'"). " AND ".((is_int($this->where[$i][3]) || $this->where[$i][3] == '?') ? $this->where[$i][3] : "'" . $this->escStr($this->where[$i][3]) . "'")." ";
                    } elseif($this->where[$i][1] == 'notbetween') {
                        $this->sqlstring .= $this->where[$i][0] . " NOT BETWEEN " . ((is_int($this->where[$i][2]) || $this->where[$i][2] == '?') ? $this->where[$i][2] : "'" . $this->escStr($this->where[$i][2]) . "'"). " AND ".((is_int($this->where[$i][3]) || $this->where[$i][3] == '?') ? $this->where[$i][3] : "'" . $this->escStr($this->where[$i][3]) . "'")." ";
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
        $this->setColumns();
        $this->setWhere();
        return substr($this->sqlstring, 0, -1);
    }

    public static function version()
    {
        return self::$VERSION;
    }
}
