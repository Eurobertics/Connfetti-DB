<?php
namespace Connfetti\Db\ResultSet;


use Connfetti\Db\ResultModel\ResultModel;

class ResultSet
{
    private $countrows = 0;
    private $datamodels = array();

    public function __construct(array $data)
    {
        for($i = 0; $i < count($data); $i++) {
            $this->storeDataModel($data[$i]);
        }
        $this->countrows = count($data);
    }

    public function __destruct()
    {
        $this->countrows = 0;
        $this->datamodels = array();
    }

    private function storeDataModel($datarow)
    {
        $this->datamodels[] = new ResultModel($datarow);
    }

    public function countRows()
    {
        return $this->countrows;
    }

    public function getModel($iter) {
        return $this->datamodels[$iter];
    }
}
