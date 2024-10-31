<?php
class Login extends Database{
    public $dbCon;
    public $tableName;

    public function __construct(){
        $this->dbCon = $this->dbConnect();
        $this->tableName;
    }

    public function userLogin($email, $password){
        $sql = "SELECT * FROM ".$this->tableName." WHERE `email` = ?";
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute([$email]);
        //            CONFIRMING EMAIL...
        if ($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $password = password_verify($password, $row['password']);
            if ($password){
                return $row;
//               PASSWORD IS CORRECT...
            }
            return 10;
//            INCORRECT PASSWORD...
        }
        else{
            return 0;
//            EMAIL NOT FOUND...
        }
    }

    public function forgetPassword($email, $token){
        $sql = "SELECT * FROM ".$this->tableName." WHERE `email` = ?";
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0){
            $expire = time() + (10 * 60);   // 10 MINUTES IN SECONDS
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $sql = "INSERT INTO 'tbl_passreset' (`email`, `token`, `expiresat`) VALUES (?, ?, ?)";
            $stmt = $this->dbCon->prepare($sql);
            $stmt->execute([$email, $token, $expire]);
            if ($stmt){
                return $row;
//                INSERTED SUCCESSFULLY...
            }
            return 10;
//              ERROR OCCURRED WHILE INSERTING DATA...
        }
        return 0;
//            EMAIL NOT FOUND...
    }

    public function resetPassword($token, $password){
        $currentTime = time();
        $sql = "SELECT * FROM ".$this->tableName." WHERE `token` = ? AND `expiresat` > ? ";
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute([$token, $currentTime]);
        if ($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $newPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "UPDATE ".$this->tableName." SET `password` = ? WHERE `email` = ?";
            $stmt = $this->dbCon->prepare($sql);
            $stmt->execute([$newPassword, $row['email']]);
            if ($stmt){
                $sql = "DELETE FROM ".$this->tableName." WHERE `email` = ?";
                $stmt = $this->dbCon->prepare($sql);
                $stmt->execute([$row['email']]);
                if ($stmt){
                    return 1;
//                    PASSWORD RESET SUCCESSFUL AND TOKEN INFORMATION DELETED SUCCESSFULLY...
                }
                return 100;
//                DELETE FAILED
            }
            return 10;
//            PASSWORD UPDATE FAILED
        }
        return 0;
//        TOKEN NOT FOUND OR TOKEN EXPIRED...
    }

//    CHANGE PASSWORD MODAL BEGINS
        public function changePassword($id, $oldPassword, $newPassword){
        $sql = "SELECT * FROM ".$this->tableName." WHERE `id` = ?";
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($oldPassword, $row['password'])){
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $sql = "UPDATE ".$this->tableName." SET `password` = ?";
                $stmt = $this->dbCon->prepare($sql);
                $stmt->execute([$hashedPassword]);
                if ($stmt){
                    return 1;
//                    PASSWORD CHANGED SUCCESSFULLY
                }
                return 100;
//                DATABASE ERROR OCCURRED WHILE CHANGING PASSWORD
            }
            return 10;
//            OLD PASSWORD NOT CORRECT
        }
        return 0;
//        ID NOT FOUND
        }

//        CHANGE EMAIL BEGINS
        public function changeEmaIl($id, $password, $email){
        $sql = "SELECT * FROM ".$this->tableName." WHERE `id` = ?";
        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])){
                $sql = "SELECT * FROM ".$this->tableName." WHERE `email` = ?";
                $stmt = $this->dbCon->prepare($sql);
                $stmt->execute($email);
                if (!$stmt){
                    $sql = "UPDATE ".$this->tableName." SET `email` = ?";
                    $stmt = $this->dbCon->prepare($sql);
                    $stmt->execute([$email]);
                    if ($stmt){
                        return 1;
                    }
                    return 1000;
//                DATABASE ERROR OCCURRED
                }
               return 100;
//         EMAIL ALREADY BEEN USED
            }
            return 10;
//            PASSWORD NOT CORRECT
        }
        return 0;
//        USER ID NOT FOUND
        }
}