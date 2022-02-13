<?php
namespace Connfetti\Db\Sql;


class Select implements QueryInterface
{
    private $table = "";
    private $columns = "*";
    private $join = "";
    private $jointable = "";
    private $joinlink = "";
    private $where = array();
    private $ordercols = array();
    private $order = "";
    private $limit1 = "";
    private $limit2 = false;
    private $having = array();
    private $groupby = array();
    private $union = null;
    private $in = null;
    private $insearch = null;

    private $haswhere = false;
    private $hasjoin = false;
    private $hashaving = false;
    private $hasgroupby = false;
    private $hasorderby = false;
    private $hasunion = false;
    private $hasin = false;

    public function __construct($table = "")
    {
        $this->table = $table;
    }

    public function __destruct()
    {
        $this->table = "";
        $this->columns = "*";
        $this->join = "";
        $this->jointable = "";
        $this->joinlink = "";
        $this->where = array();
        $this->ordercols = array();
        $this->order = "";
        $this->limit1 = "";
        $this->limit2 = false;
        $this->having = array();
        $this->groupby = array();
        $this->union = null;
        $this->in = null;
        $this->insearch = null;

        $this->haswhere = false;
        $this->hasjoin = false;
        $this->hashaving = false;
        $this->hasgroupby = false;
        $this->hasorderby = false;
        $this->hasunion = false;
        $this->hasin = false;
    }

    public function getQueryDataAsArray()
    {
        return get_object_vars($this);
    }

    public function from($table)
    {
        $this->table = $table;
        return $this;
    }

    public function columns($cols = '*')
    {
        $this->columns = $cols;
        return $this;
    }

    public function join($type, $table)
    {
        $this->hasjoin = true;
        $this->join = $type;
        $this->jointable = $table;
        return $this;
    }

    public function on($leftside, $rightside, $compareable) {
        if(!empty($this->join) && !empty($this->jointable)) {
            $this->joinlink = $leftside.$compareable.$rightside;
        }
        return $this;
    }

    public function where($col, $compareable, $value)
    {
        $this->haswhere = true;
        $this->where[] = array($col, $compareable, $value);
        return $this;
    }

    public function in($insearch, $indata = array())
    {
        $this->hasin = true;
        $this->in = $indata;
        $this->insearch = $insearch;
    }

    public function isNotNull($col)
    {
        $this->where[] = array($col, 'notnull');
        return $this;
    }

    public function isNull($col)
    {
        $this->where[] = array($col, 'null');
        return $this;
    }

    public function and()
    {
        $this->where[] = "AND";
        return $this;
    }

    public function or()
    {
        $this->where[] = "OR";
        return $this;
    }

    public function having($col, $compareable, $value)
    {
        $this->hashaving = true;
        $this->having = array($col, $compareable, $value);
        return $this;
    }

    public function groupby(array $cols)
    {
        $this->hasgroupby = true;
        $this->groupby = $cols;
        return $this;
    }

    public function order(array $cols, $order)
    {
        $this->hasorderby = true;
        $this->ordercols = $cols;
        $this->order = $order;
        return $this;
    }

    public function limit($limit1, $limit2 = false)
    {
        $this->limit1 = $limit1;
        $this->limit2 = $limit2;
        return $this;
    }

    public function union(Select $selectobj)
    {
        $this->hasunion = true;
        $this->union = $selectobj;
        return $this;
    }

    public function condStart()
    {
        $this->where[] = '(';
        return $this;
    }

    public function condEnd()
    {
        $this->where[] = ')';
        return $this;
    }
}
