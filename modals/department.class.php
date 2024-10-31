<?php
class Department extends Database{
    private $dbCon;
    public $tableName;
    public function __construct(){
        $this->tableName;
        $this->dbCon = $this->dbConnect();
    }
    public function createDepartment($department, $description){
        $sql = "INSERT INTO ".$this->tableName." (`department`, `description`) VALUES (?, ?, ?)";
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute([$department, $description]);
        if ($stmt){
            return 1;
//            DEPARTMENT CREATED SUCCESSFULLY
        }
        return 0;
//        ERROR OCCURED WHILE CREATING DEPARTMENT
    }
    public function selectDepartment(){
        $sql = "SELECT * FROM ".$this->tableName;
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() > 0){
            return $stmt;
//            DEPARTMENT SELECTED SUCCESSFULLY...
        }
        return 0;
//        ERROR OCCURED WHILE SELECTING DEPARTMENT
    }
    public function selectSingleDepartment($id){
        $sql = "SELECT * FROM ".$this->tableName." WHERE `id` = ?";
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() > 0){
            return $stmt;
//            SINGLE  DEPARTMENT SELECTED SUCCESSFULLY...
        }
        return 0;
//        ERROR OCCURRED WHILE SELECTING SINGLE  DATA
    }
    public function updateDepartment($department, $description, $id){
        $sql = "UPDATE  ".$this->tableName." SET `department` = ?, `description` = ? WHERE `id` = ".$id;
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute([$department, $description]);
        if ($stmt){
            return 1;
//            DEPARTMENT UPDATED SUCESSFULLY
        }
        return 0;
//        ERROR OCCURED WHILE UPDATING DEPARTMENT
    }
    public function deleteDepartment($id){
        $sql = "DELETE FROM ".$this->tableName." WHERE `id` = ".$id;
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute();
        if ($stmt){
            return 1;
//          DELETED SUCCESSFULLY
        }
        return 0;
//        ERROR OCCURRED WHILE DELETING DEPARTMENT...
    }
}