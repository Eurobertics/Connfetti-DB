<?php
namespace Connfetti\Db\Sql;


use Connfetti\Db\Adapter\Adapter;
use Connfetti\Db\Exception\QueryException;

class Sql
{
    const ORDER_ASC = "ASC";
    const ORDER_DESC = "DESC";
    const COMPERABLE_EQ = "=";
    const COMPERABLE_NEQ = "!=";
    const COMPERABLE_GT = ">";
    const COMPERABLE_LT = "<";
    const COMPERABLE_GET = ">=";
    const COMPERABLE_LET = "<=";
    const COMPERABLE_LIKE = 'LIKE';

    /** @var Adapter */
    private $adapter = null;

    /** @var \Connfetti\Db\Sql\Builder\BuilderInterface|\Connfetti\Db\Sql\Builder\PerpareableBuilderInterface */
    private $builder = null;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function __destruct()
    {
        $this->adapter = null;
        $this->builder = null;
    }

    public function select($table = '')
    {
        return new Select($table);
    }

    public function insert($table = '')
    {
        return new Insert($table);
    }

    public function update($table = '')
    {
        return new Update($table);
    }

    public function delete($table = '')
    {
        return new Delete($table);
    }

    public function buildQuery(QueryInterface $query, $returntype = Adapter::QUERY_EXECUTE)
    {
        $this->builder = \Connfetti\Db\Sql\Builder\BuilderFactory::create($query, $this->adapter);
        if($returntype == Adapter::QUERY_EXECUTE) {
            $res = null;
            try {
                $res = $this->adapter->query($this->builder->getAsString());
            } catch(QueryException $e) {
                throw $e;
            }
            return $res;
        } else {
            return $this->builder->getAsString();
        }
    }

    public function prepareStatement(QueryInterface $query)
    {
        $this->builder = \Connfetti\Db\Sql\Builder\BuilderFactory::create($query, $this->adapter);
        $this->builder->setupPreparedStatement();
        return new Statement(
            $this->builder->getAsString(),
            $this->builder->getPreparedParams(),
            $this->adapter
        );
    }
}
