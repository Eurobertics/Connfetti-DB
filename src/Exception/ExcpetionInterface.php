<?php
namespace Connfetti\Db\Exception;


interface ExcpetionInterface
{
    public function prettyOutputFormat();
    public function getError();
    public function getErrorTrace();
}