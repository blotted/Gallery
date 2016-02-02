<?php

require_once 'config.php';

class MySQLDatabase {
    
    private $connection;
    private $result_set;
    public $last_query;


    public function __construct() {
        $this->connection();
    }
    
    public function connection() {
        $this->connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_errno) {
            echo "Не удалось подключиться к MySQL: " . $this->connection->connect_error;
        }
    }
    
    public function query($sql) {
       $this->last_query = $sql;
       $result = $this->connection->query($sql);
       $this->confirmQuery($result);
       $this->result_set = $result;
       return $result;
    }
    
    public function escapeValue ($value) {
        $value = $this->connection->real_escape_string ($value);
        return $value;
    }
    
    public function closeConnection() {
        if (isset($this->connection)) {
          $this->connection->close();
          unset($this->connection);
        }
    }
    
    public function fetchArray() {
        return $this->result_set->fetch_assoc();
    }
    
    public function numRows() {
        return $this->result_set->num_rows;
    }
    
    public function affectedRows() {
        return $this->connection->affected_rows;
    }
    
    public function isertId() {
        return $this->connection->insert_id;
    }
   
    private function confirmQuery($result) {
        if (!$result) {
            $output = "Не удалось подключиться к MySQL: " . $this->connection->error . "<br>";
            // $output .= "Последний запрос: " . $this->last_query;
            echo $output;
        }
    }
}

$database = new MySQLDatabase();