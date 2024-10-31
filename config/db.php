<?php
class Database {
    private $SERVERNAME;
    private $USERNAME;
    private $PASSWORD;
    private $DBNAME;
    private $dbCon;

    public function dbConnect(){
        $this->SERVERNAME = "localhost";
        $this->DBNAME = "alraclinic";
        $this->USERNAME = "root";
        $this->PASSWORD = "";

        try {
            $this->dbCon = new PDO("mysqli:host=".$this->SERVERNAME.";dbname:=".$this->DBNAME, $this->USERNAME, $this->PASSWORD);
            $this->dbCon-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->dbCon;
        } catch (\Exception $e){
            print "Database error :". $e->getMessage();
            die();
        }
    }
}