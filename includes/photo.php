<?php
require_once 'database.php';

class Photo {
    protected static $table_name="photo";
    protected static $db_fields = array(
                                'id', 'filename', 'type', 
                                'size', 'caption');
    
    public $id;
    public $filename;
    public $type;
    public $size;
    public $caption;
    
    private $temp_path;
    protected $upload_dir="images";
    
    public $errors = []; 
    protected $upload_errors = array(
        'UPLOAD_ERR_OK' => "No errors.",
        'UPLOAD_ERR_INI_SIZE' => "Larger than upload_max_filesize.",
        'UPLOAD_ERR_FROM_SIZE' => "Larger than from MAX_FILE_SIZE.",
        'UPLOAD_ERR_PARTIAL' => "Partial upload.",
        'UPLOAD_ERR_NO_FILE' => "No file.",
        'UPLOAD_ERR_NO_TMP_DIR' => "No temporary directory.",
        'UPLOAD_ERR_CANT_WRITE' => "Can't write to disc.",
        'UPLOAD_ERR_EXTENSION' => "File upload stopped by extension."
    );

    public function attachFile($file) {
        if(!$file || empty($file) || !is_array($file)) {
            $this->errors[] = "No file was uploaded";
            return false;
        } elseif ($file['error'] != 0) {
            $this->errors[] = $this->upload_errors[$file['error']];
            return false;
        } else {
        $this->temp_path = $file['tmp_name'];
        $this->filename = basename($file['name']);
        $this->type = $file['type'];
        $this->size = $file['size'];
        return true;
        }
    }
    
    public function save() {
        if(isset($this->id)) {
            $this->update();
        } else {
            
            if(!empty($this->errors)){return false;}
            
            if(strlen($this->caption) > 255) {
                $this->errors[] = "The caption can only by 255 characters long.";
                return false;
            }
            
            if(empty($this->filename) || empty($this->temp_path)) {
                $this->errors[] = "The file location was not available.";
                return false;
            }
            
            $target_path = "../../public/" . $this->upload_dir ."/". $this->filename;
            
            if(file_exists($target_path)) {
                $this->errors[] = "The file {$this->filename} already exists.";
                return false;
            }
            if(move_uploaded_file($this->temp_path, $target_path)) {
                // Success
                if($this->create()) {
                    unset($this->temp_path);
                    return true;
                }
            } else {
                // Failure
                $this->errors[] = "The file upload failed.";
            }
        }
    }
    
    public function destroy() {
        if($this->delete()){
            $target_path = "../".$this->imagePath();
            return unlink($target_path) ? true : false;
        } else {
            return false;
        }
    }
    
    public function imagePath() {
        return $this->upload_dir."/".$this->filename;
    }
    
    public function sizeAsText() {
        if($this->size < 1024) {
            return "{$this->size} bytes";
        } elseif ($this->size < 1048576) {
            $size_kb = round($this->size/1024);
            return "{$size_kb} KB"; 
        } else {
            $size_mb = round($this->size/1048576, 1);
            return "{$size_kb} MB"; 
        }
    }
    
    public function comments() {
        return Comment::findCommentsOn($this->id);
    }
    
    public static function findALL() {
        return self::findBySql("SELECT * FROM " . self::$table_name);
    }
    
    public static function findById($id=0) {
        global $database;
        $result_array = self::findBySql("SELECT * FROM ". self::$table_name ." WHERE id=".$database->escapeValue($id)." LIMIT 1");
        
        return !empty($result_array) ? array_shift($result_array) : false;
    }
    
    public static function countAll() {
        global $database;
        $sql = "SELECT COUNT(*) FROM " . self::$table_name;
        $result_set = $database->query($sql);
        $row = $database->fetchArray($result_set);
        return array_shift($row);
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
    
    public function create() {
        global $database;
        $attributes = $this->sanitizedAttributes();
        array_shift($attributes);
        $sql = "INSERT INTO ".self::$table_name." (";
        $sql .= implode(", ", array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= implode("', '", array_values($attributes));
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