<?php

class MedicalReport extends Database {
    public $tableName;
    private  $dbCon;

    public function  __construct(){
        $this->tableName;
        $this->dbCon = $this->dbConnect();
    }

    public function createPatientMedicalReport($patient_id, $height, $weight,$blood_group, $blood_pressure, $pulse, $respiration, $allergy, $diet){
        $sql = "INSERT INTO ".$this->tableName." (`patient_id`, `height`, `weight`, `blood_group`, `blood_pressure`, `pulse`, `respiration`, `allergy`, `diet`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute([$patient_id, $height, $weight,$blood_group, $blood_pressure, $pulse, $respiration, $allergy, $diet]);
        if ($stmt){
            return 1;
//            MEDICAL REPORT CREATED SUCCESSFULLY
        }
        return 0;
//        DATABASE ERROR OCCURRED

    }

    public function selectPatientMedicalReport(){
        $sql = "SELECT * FROM ".$this->tableName;
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() > 0){
            return $stmt;
        }
        return 0;
    }

    public function selectSinglePatientMedicalReport($id){
        $sql = "SELECT * FROM ".$this->tableName." WHERE `id` = ?";
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() > 0){
            return $stmt;
        }
        return 0;
    }

    public function updatePatientMedicalReport($height, $weight, $blood_group, $blood_pressure, $pulse, $respiration, $allergy, $diet, $id) {
        $sql = "UPDATE ".$this->tableName." SET `height` = ?, `weight` = ?, `blood_group` = ?, `blood_pressure` = ?, `pulse` = ?, `respiration` = ?, `allergy` = ?,  `diet` = ? WHERE `id` = ?";
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute([$height, $weight, $blood_group, $blood_pressure, $pulse, $respiration, $allergy, $diet, $id]);
        if ($stmt) {
            return 1;
        }
        return 0;
    }

    public function deletePatientMedicalReport($id){
        $sql = "DELETE FROM ".$this->tableName." WHERE `id` = ?";
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute($id);
        if ($stmt){
            return 1;
        }
        return  0;
    }
}