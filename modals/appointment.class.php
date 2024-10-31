<?php
class Appointment extends Database{
    private $dbCon;
    public $tableName;
    public $instance;
    public function __construct(){
        $this->tableName;
        $this->dbCon = $this->dbConnect();
    }
        public function createAppointment($date, $time, $description, $doctor_id, $patient_id){
        $sql = "INSERT INTO ".$this->tableName." (`date`, `time`, `description`, `doctor_id`, `patient_id`) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute([$date, $time, $description, $doctor_id, $patient_id]);
        if ($stmt){
            return 1;
//            DEPARTMENT CREATED SUCCESSFULLY
        }
        return 0;
//        ERROR OCCURRED WHILE CREATING DEPARTMENT
    }

    public function selectAppointment(){
        $sql = "SELECT * FROM ".$this->tableName;
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() > 0){
            return $stmt;
//            DEPARTMENT SELECTED SUCCESSFULLY...
        }
        return 0;
//        ERROR OCCURRED WHILE SELECTING DEPARTMENT
    }
    public function selectSingleAppointment($id){
        $sql = "SELECT * FROM ".$this->tableName." WHERE ".$this->instance." = ?";
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() > 0){
            return $stmt;
//            SINGLE  DEPARTMENT SELECTED SUCCESSFULLY...
        }
        return 0;
//        ERROR OCCURRED WHILE SELECTING SINGLE  DATA
    }
    public function updateAppointment($date, $time, $description, $id){
        $sql = "UPDATE  ".$this->tableName." SET `date` = ?, `time` = ?, `description` = ? WHERE `id` = ".$id;
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute([$date, $time, $description]);
        if ($stmt){
            return 1;
//            DEPARTMENT UPDATED SUCESSFULLY
        }
        return 0;
//        ERROR OCCURED WHILE UPDATING DEPARTMENT
    }
    public function deleteAppointment($id){
        $sql = "DELETE FROM ".$this->tableName." WHERE `id` = ".$id;
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute();
        if ($stmt){
            return 1;
//          DELETED SUCCESSFULLY
        }
        return 0;
//        ERROR OCCURED WHILE DELETING DEDARTMENT...
    }
}