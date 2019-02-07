<?php
namespace Connfetti\Db\Driver\Platform;


use Connfetti\Db\Driver\DriverInterface;
use Connfetti\Db\Exception\DriverException;
use Connfetti\Db\Exception\QueryException;

class Mysql implements DriverInterface
{
    private $host;
    private $user;
    private $pass;
    private $db;

    /** @var \mysqli */
    private $sql;
    /** @var \mysqli_stmt */
    private $stmt;

    public function __construct($config)
    {
        if(!empty($config['host']) && is_string($config['host'])) {
            $this->host = $config['host'];
        }
        if(!empty($config['user']) && is_string($config['user'])) {
            $this->user = $config['user'];
        }
        if(!empty($config['pass']) && is_string($config['pass'])) {
            $this->pass = $config['pass'];
        }
        if(!empty($config['db']) && is_string($config['db'])) {
            $this->db = $config['db'];
        }
    }

    public function __destruct()
    {
        $this->host = null;
        $this->user = null;
        $this->pass = null;
        $this->db = null;
    }

    /** @throws DriverException */
    public function checkEnvironment()
    {
        if(!extension_loaded('mysqli')) {
            throw new DriverException("Extension 'mysqli' is not loaded!", 1);
        }
    }

    /** @throws DriverException */
    public function connection()
    {
        $mysqli = new \mysqli($this->host, $this->user, $this->pass, $this->db);
        if($mysqli->connect_errno) {
            throw new DriverException($mysqli->connect_error, $mysqli->connect_errno);
        }
        $this->sql = $mysqli;
    }

    public function close()
    {
        $this->sql->close();
    }

    public function escStr($str)
    {
        return $this->sql->real_escape_string($str);
    }

    /** @throws QueryException */
    public function query(string $querystring)
    {
        $resul = null;
        /** @var \mysqli_result $result */
        $result = $this->sql->query($querystring);
        if($this->sql->errno) {
            throw new QueryException($this->sql->error, $this->sql->errno);
        }
        if(is_object($result)) {
            if($result->num_rows == 1) {
                return array($result->fetch_assoc());
            } else {
                $retar = array();
                while($row = $result->fetch_assoc()) {
                    $retar[] = $row;
                }
            }
            $result->close();
            return $retar;
        } else {
            return $result;
        }
    }

    public function preparedQuery(string $querystring, array $params)
    {
        $this->stmt = $this->sql->stmt_init();
        $this->stmt->prepare($querystring);
        $this->preparedQueryParams($params);
    }

    public function preparedQueryParams(array $params)
    {
        if(count($params) == 0) { return; }
        $bind_params_array = array();
        $bind_params_type = "";
        for($i = 0; $i < count($params); $i++) {
            if(is_int($params[$i])) { $bind_params_type .= "i"; continue; }
            if(is_float($params[$i])) { $bind_params_type .= "d"; continue; }
            if(is_string($params[$i])) { $bind_params_type .= "s"; continue; }
            $bind_params_type .= "b";
        }
        $bind_params_array[] = $bind_params_type;
        for($i = 0; $i < count($params); $i++) {
            $bind_params_array[] = &$params[$i];
        }
        call_user_func_array(array($this->stmt, 'bind_param'), $bind_params_array);
    }

    /** @throws QueryException */
    public function executePreparedQuery()
    {
        $retar = array();
        $this->stmt->execute();
        if($this->stmt->errno) {
            throw new QueryException($this->stmt->error, $this->stmt->errno);
        }
        $result = $this->stmt->get_result();
        if(is_object($result)) {
            if($result->num_rows == 1) {
                return array($result->fetch_assoc());
            } else {
                while($row = $result->fetch_assoc()) {
                    $retar[] = $row;
                }
            }
            $result->close();
        } else {
            return $result;
        }
        return $retar;
    }

    public function closePreparedQuery()
    {
        $this->stmt->close();
    }
}
