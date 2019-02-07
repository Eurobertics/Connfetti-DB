<?php
namespace Connfetti\Db\ResultModel;


class ResultModel extends ResultModelAbstract
{
    private $countCols = 0;

    public function __construct(array $data = null)
    {
        parent::__construct($data);
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function populateByArray(array $data)
    {
        foreach($data as $key => $val) {
            $this->$key = $val;
        }
        $this->countCols = count($data);
    }

    public function getAsArray()
    {
        return $this->returnArray();
    }
}
