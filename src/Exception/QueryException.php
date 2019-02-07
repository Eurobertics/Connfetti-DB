<?php
namespace Connfetti\Db\Exception;


class QueryException extends \Exception implements ExcpetionInterface
{
    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function prettyOutputFormat()
    {
        if(php_sapi_name() == "cli") {
            return "\n";
        } else {
            return "<br />";
        }
    }

    public function getError()
    {
        $error = "Error occourred in query:".$this->prettyOutputFormat();
        $error .= "SQL Errorcode: ".$this->getCode().$this->prettyOutputFormat();
        $error .= "Message: ".$this->getMessage().$this->prettyOutputFormat();
        $error .= "File: ".$this->getFile().$this->prettyOutputFormat();
        $error .= "Line: ".$this->getLine().$this->prettyOutputFormat();
        return $error;
    }

    public function getErrorTrace()
    {
        $error = "Error occourred in query:".$this->prettyOutputFormat();
        $error .= "SQL Errorcode: ".$this->getCode().$this->prettyOutputFormat();
        $error .= "Message: ".$this->getMessage().$this->prettyOutputFormat();
        $error .= "Stacktrace: ".$this->prettyOutputFormat().str_replace("\n", $this->prettyOutputFormat(), $this->getTraceAsString());
        return $error;
    }
}