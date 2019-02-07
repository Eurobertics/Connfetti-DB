<?php
namespace Connfetti\Db\ResultModel;


abstract class ResultModelAbstract
{
    private $data = array();

    public function __construct($data)
    {
        if($data == null) {
            $this->data = array();
        } else {
            $this->populateByArray($data);
        }
    }

    public function __destruct()
    {
        $this->data = array();
    }

    public function __set($name, $value)
    {
        if(!array_key_exists($name, $this->data)) {
            $this->data[$name] = $value;
        }
    }

    public function __get($name)
    {
        if(array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return null;
    }

    protected function returnArray()
    {
        return $this->data;
    }

    public function __isset($name)
    {
        return (isset($this->data[$name]) ? true : false);
    }

    abstract public function populateByArray(array $data);
    abstract public function getAsArray();
}