<?php
/**
 * Created by PhpStorm.
 * User: brobe
 * Date: 05.02.2019
 * Time: 12:51
 */

namespace Connfetti\Db\Sql\Builder;


interface PerpareableBuilderInterface
{
    public function setupPreparedStatement();
    public function getPreparedParams();
}