<?php
class Users extends  Database{
    private $dbcon;
    public $tableName;
    public $instance;
    public $values;
    public $execute;
    public function __construct(){
        $this->tableName;
        $this->dbcon = $this->dbConnect();
    }
        public function createUser(){
        $sql = "INSERT INTO ".$this->tableName." (".$this->instance.") VALUES (".$this->values.")";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->execute($this->execute); // Assuming $this->execute is already an array
        if ($stmt){
            return 1;
//            USER  CREATED SUCCESSFULLY...
        }
        return 0;
//        ERROR OCCURED WHILE CREATING USER
    }

    public function selectUser($level){
        $sql = "SELECT * FROM ".$this->tableName." WHERE  `level` = ?";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->execute([$level]);
        if ($stmt->rowCount() > 0){
            return $stmt;
//            USER SELECTED SUCCESSFULLY
        }
        return 0;
//        NO USER FOUND
    }

    public function selectSingleuser($id){
        $sql = "SELECT * FROM ".$this->tableName." WHERE `id` = ?";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() > 0){
            return $stmt;
        }
        return 0;
    }

    public function updateUser($id){
        $sql = "UPDATE ".$this->tableName." SET ".$this->instance." WHERE `id` = ?";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->execute([$this->execute, $id]);
        if ($stmt){
            return 1;
        }
        return 0;
    }

    public function deleteUser($id){
        $sql = "DELETE FROM ".$this->tableName." WHERE `id` = ?";
        $stmt = $this->dbcon->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt){
            return 1;
//            USER DELETED SUCCESSSFULLY
        }
        return 0;
//        ERROR OCCURED WHILE DELETING THE USER
    }
}