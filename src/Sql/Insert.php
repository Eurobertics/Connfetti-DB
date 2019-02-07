<?php
namespace Connfetti\Db\Sql;


class Insert implements QueryInterface
{
    private $table = "";
    private $columns = array();

    public function __construct($table = "")
    {
        $this->table = $table;
    }

    public function __destruct()
    {
        $this->table = "";
        $this->columns = array();
    }

    public function getQueryDataAsArray()
    {
        return get_object_vars($this);
    }

    public function into($table)
    {
        $this->table = $table;
        return $this;
    }

    public function values(array $cols)
    {
        $this->columns = $cols;
        return $this;
    }
}
