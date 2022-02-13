<?php
namespace Connfetti\Db\Sql\Builder;


use Connfetti\Db\Sql\Builder\Mysql\SelectBuilder;

abstract class BuilderAbstract implements PerpareableBuilderInterface
{
    /** @var \Connfetti\Db\Adapter\Adapter */
    protected $adapter = null;

    protected $table = null;
    protected $columns = null;
    protected $join = null;
    protected $jointable = null;
    protected $joinlink = null;
    protected $where = null;
    protected $ordercols = null;
    protected $order = null;
    protected $limit1 = null;
    protected $limit2 = null;
    protected $having = null;
    protected $groupby = null;
    protected $union = null;
    protected $in = null;
    protected $insearch = null;

    protected $stmt_param_array = array();

    protected $haswhere = false;
    protected $hasjoin = false;
    protected $hashaving = false;
    protected $hasgroupby = false;
    protected $hasorderby = false;
    protected $hasunion = false;
    protected $hasin = false;

    protected $sqlstring = "";

    public function __construct(array $querydata, $adapter)
    {
        $this->adapter = $adapter;
        foreach($querydata as $key => $value) {
            if(!property_exists($this, $key)) { continue; }
            $this->$key = $value;
        }
    }

    public function __destruct()
    {
        $this->adapter = null;

        $this->table = null;
        $this->columns = null;
        $this->join = null;
        $this->jointable = null;
        $this->joinlink = null;
        $this->where = null;
        $this->ordercols = null;
        $this->order = null;
        $this->limit1 = null;
        $this->limit2 = null;
        $this->having = null;
        $this->groupby = null;
        $this->union = null;
        $this->in = null;
        $this->insearch = null;

        $this->stmt_param_array = array();

        $this->haswhere = false;
        $this->hasjoin = false;
        $this->hashaving = false;
        $this->hasgroupby = false;
        $this->hasorderby = false;
        $this->hasunion = false;
        $this->hasin = false;
    }

    protected function escStr($value)
    {
        if(is_string($value)) {
            return $this->adapter->escStr($value);
        }
        return $value;
    }

    public function setupPreparedStatement()
    {
        if(!($this instanceof SelectBuilder) && $this->columns != null) {
            foreach ($this->columns as $key => $value) {
                $this->stmt_param_array[] = $value;
                $this->columns[$key] = '?';
            }
        }

        if($this->haswhere) {
            for($i = 0; $i < count($this->where); $i++) {
                if(is_array($this->where[$i]) && count($this->where[$i]) == 3) {
                    $this->stmt_param_array[] = $this->where[$i][2];
                    $this->where[$i][2] = '?';
                }
            }
        }
    }

    public function getPreparedParams()
    {
        return $this->stmt_param_array;
    }

    abstract public static function version();
}
