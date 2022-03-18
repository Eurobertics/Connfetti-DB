<?php
namespace Connfetti\Db\Sql;


class Delete implements QueryInterface
{
    private $table = "";
    private $where = array();

    private $haswhere = false;

    public function __construct($table = "")
    {
        $this->table = $table;
    }

    public function __destruct()
    {
        $this->table = "";
        $this->where = array();

        $this->haswhere = false;
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

    public function where($col, $compareable, $value)
    {
        $this->haswhere = true;
        $this->where[] = array($col, $compareable, $value);
        return $this;
    }

    public function whereKeywordOnly()
    {
        $this->haswhere = true;
        return $this;
    }

    public function whereBetween($col, $value1, $value2)
    {
        $this->haswhere = true;
        $this->where[] = array($col, 'between', $value1, $value2);
    }

    public function in($insearch, $indata = array())
    {
        if($this->haswhere) {
            $this->where[] = array($insearch, 'in', $indata);
        }
        return $this;
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
