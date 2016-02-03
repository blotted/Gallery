<?php

require_once 'database.php';

class User {
    
    protected static $table_name = "user";
    protected static $db_fields = ['id', 'username', 'password', 'first_name', 'last_name'];
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;

    public static function authenticate($username="", $password="") {
        global $database;
        $username = $database->escapeValue($username);
        $password = $database->escapeValue($password);
        
        $sql = "SELECT * FROM user ";
        $sql .= "WHERE username = '{$username}' ";
        $sql .= "AND password = '{$password}' ";
        $sql .= "LIMIT 1";
        
        $result_array = self::findBySql($sql); 
        return !empty($result_array) ? array_shift($result_array) : false;
    }
    
    public function fullName() {
        if(isset($this->first_name) && isset($this->last_name)) {
            return $this->first_name . " " . $this->last_name;
        } else {
            return "";
        }
    }
    
    public static function findALL() {
        return self::findBySql("SELECT * FROM " . self::$table_name);
    }
    
    public static function findById($id=0) {
        global $database;
        $result_array = self::findBySql("SELECT * FROM ". self::$table_name ." WHERE id={$id} LIMIT 1");
        
        return !empty($result_array) ? array_shift($result_array) : false;
    }
    
    public static function findBySql($sql="") {
        global $database;
        $result_set = $database->query($sql);
        $object_array = [];
        
        while($row = $database->fetchArray($result_set)) {
            $object_array[] = self::instantiate($row);
        }
        return $object_array;
    }
   
    private static function instantiate($record) {
        $object = new self;
        
        foreach ($record as $attribute => $value) {
            if($object->hasAttribute($attribute)) {
                $object->$attribute = $value;
            }
        }
        return $object;
    }
    
    private function hasAttribute($attribute) {
        $object_vars = $this->attributes();
        return array_key_exists($attribute, $object_vars);
    }
    
    private function attributes() {
        $attributes = [];
        foreach (self::$db_fields as $field) {
            if(property_exists($this, $field)) {
                $attributes[$field] = $this->$field;
            }
        }
        return $attributes;
    }
    
    protected function sanitizedAttributes() {
        global $database;
        $clean_attributes = [];
        
        foreach ($this->attributes() as $key => $value) {
            $clean_attributes[$key] = $database->escapeValue($value);
        }
        return $clean_attributes;
    }
    
    public function save() {
        return isset($this->id) ? $this->update() : $this->create(); 
    }

    public function create() {
        global $database;
        $attributes = $this->sanitizedAttributes();
        $sql = "INSERT INTO ".self::$table_name." (";
       // $sql .= "filename, type, size, caption";
        $sql .= join(", ", array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";
        
        if($database->query($sql)) {
            $this->id = $database->isertId();
            return true;
        } else {
            return false;
        }
    }
            
    public function update() {
        global $database;
        $attributes = $this->sanitizedAttributes();
        $attribute_pairs = [];
        foreach ($attributes as $key => $value) {
            $attribute_pairs[] = "{$key}='{$value}'";
        }
        $sql = "UPDATE ".self::$table_name." SET ";
        $sql .= join(", ", $attribute_pairs);
        $sql .= " WHERE id=". $database->escapeValue($this->id);      
        $database->query($sql);
        return ($database->affectedRows() == 1) ? true : false;
    }
    
    public function delete() {
        global $database;
        $sql = "DELETE FROM ".self::$table_name;
        $sql .= " WHERE id=". $database->escapeValue($this->id);
        $sql .= " LIMIT 1";
        
        $database->query($sql);
        return ($database->affectedRows() == 1) ? true : false;
    }
}
