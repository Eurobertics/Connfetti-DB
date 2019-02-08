<?php
namespace Connfetti\Db\Sql\Builder;


interface PerpareableBuilderInterface
{
    public function setupPreparedStatement();
    public function getPreparedParams();
}
