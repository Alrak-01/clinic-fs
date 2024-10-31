<?php

    $patient = new Users();
    $patient->tableName = "tbl_users";
    if (isset($_POST['submit'])){
//        $lastname = stripslashes(trim($_POST['lastname']));
//        $firstname = stripslashes(trim($_POST['firstname']));
//        $middlename = stripslashes($_POST['middlename']);
//        $matricno = stripslashes($_POST['matricno']);
//        $address = stripslashes($_POST['address']);
//        $mobile = stripslashes($_POST['matricno']);
//        $gender = stripslashes($_POST['gender']);
//        $dob = stripslashes($_POST['dob']);
//        $email = stripslashes($_POST['email']);

        $lastname = trim(stripslashes($_POST['lastname']));
        $firstname = trim(stripslashes($_POST['firstname']));
        $middlename = trim(stripslashes($_POST['middlename']));
        $matricno = trim(stripslashes($_POST['matricno']));
        $address = trim(stripslashes($_POST['address']));
        $mobile = trim(stripslashes($_POST['mobile']));
        $gender = trim(stripslashes($_POST['gender']));
        $dob = trim(stripslashes($_POST['dob']));
        $email = trim(stripslashes($_POST['email']));

        $file = $_FILES['file'];

        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileTmpName = $file['tmp_name'];

        $extention = explode(".", $fileName);
        $actualExt = strtolower(end($extention));
        $allowed = array("jpg", "jpeg", "png");

        if (empty($lastname) || empty($firstname) || empty($middlename) || empty($matricno) || empty($address) || empty($mobile) || empty($gender) || empty($dob) || empty($email) || empty($file)){
            header("location:../add-patient.php?e=emp");
//            INPUT FILED IS EMPTY
        }
        elseif (!ctype_alnum($lastname) || !ctype_alnum($firstname) || !ctype_alnum($middlename) || !ctype_alnum($address) || !ctype_alnum($mobile  || !ctype_alnum($gender))){
            header("location:../add-patient.php?e=alp");
//            INPUT FILED CONTAIN INCORRECT PARAMETERS ONLY ALHAPNUMERIC CHARACTERS ARE ALLOWED
            }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            header("location:../add-patient.php?e=mail");
//            EMAIL FORMAT IS NOT CORRECT
        }
        elseif (!in_array($actualExt, $allowed)){
            header("location:../add-patient.php?e=ftype");
//            FILE TYPE IS NOT ALLOWED
        }
        elseif ($fileSize > 1000000){
            header("location:../add-patient.php?e=size");
//            FILE TOO LARGE
        }
        elseif ($fileError !== 0){
            header("location:../add-patient.php?e=size");
//            ERROR OCCURRED WHILE UPLOADING FILE
        }
        else{
            $fileNewName = uniqid("", true).".".$actualExt;
            $fileDestination = "../uploads/assets/img".$fileNewName;
            $password = password_hash($lastname, PASSWORD_DEFAULT);
            $patient->instance = array("lastname", "firstname", "middlename", "matricno", "address", "mobile", "filename", "gender", "dob", "email", "password", "level");
            $patient->values = array("?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?");
            $patient->execute = array($lastname, $firstname, $middlename, $matricno, $address, $mobile, $fileNewName, $gender, $dob, $email, $password, 3);
            $result = $patient->createUser();
            if ($result === 0){
                header("location:../add-patient.php?e=db");
//                DATABASE ERROR OCCURRED
            }
            elseif ($result === 1){
                if (move_uploaded_file($fileTmpName, $fileDestination)){
                        header("location:../display-patient.php?m=s");
                }
               header("location:../add-patient.php?e=db");
//                FILE NOT MOVED TO NEW DESTINATION
            }
        }
    }

        if (isset($_POST['update'])){
            if (isset($_POST['id'])){
//                $lastname = stripslashes($_POST['lastname']);
//                $firstname = stripslashes($_POST['firstname']);
//                $middlename = stripslashes($_POST['middlename']);
//                $matricno = stripslashes($_POST['matricno']);
//                $address = stripslashes($_POST['address']);
//                $mobile = stripslashes($_POST['mobile']);
//                $gender = stripslashes($_POST['gender']);
//                $dob = stripslashes($_POST['dob']);

                $lastname = trim(stripslashes($_POST['lastname']));
                $firstname = trim(stripslashes($_POST['firstname']));
                $middlename = trim(stripslashes($_POST['middlename']));
                $matricno = trim(stripslashes($_POST['matricno']));
                $address = trim(stripslashes($_POST['address']));
                $mobile = trim(stripslashes($_POST['mobile']));
                $gender = trim(stripslashes($_POST['gender']));
                $dob = trim(stripslashes($_POST['dob']));

                if (empty($lastname) || empty($firstname) || empty($middlename) || empty($matricno) || empty($address) || empty($mobile) || empty($gender) || empty($dob)){
                    header("location:../update-patient.php?e=emp");
//                INPUT FIELD IS EMPTY
                }
                elseif (!ctype_alnum($lastname) || !ctype_alnum($firstname) || !ctype_alnum($middlename) || !ctype_alnum($address) || !ctype_alnum($mobile) || !ctype_alnum($gender)){
                    header("location:../update-patient.php?e=alh");
//                INPUT FILED CONTAIN INCORRECT PARAMETERS ONLY ALHAPNUMERIC CHARACTERS ARE ALLOWED
                }
                else{
                    $id = $_GET['id'];
                    $patient->instance = array("lastname = ?", "firstname = ?", "middlename = ?", "matricno = ?", "address = ?", "mobile = ?", "gender = ?", "dob = ?");
                    $patient->execute = array($lastname, $firstname, $middlename, $matricno, $address, $mobile, $gender, $dob);
                    $result = $patient->updateUser($id);
                    if ($result === 0){
                        header("location:../update-patient.php?e=db");
//                    DATABASE ERROR OCCURRED
                    }
                    elseif ($result === 1){
                        header("location:../display-patient.php?m=s");
//                    PATIENT IS UPDATED SUCCESSFULLY$re
                    }
                }
            }
        }

//        DELETE PATIENT BEGINS
    if (isset($_GET['del']) && isset($_GET['id'])){
        $id = $_GET['id'];
        $result = $patient->deleteUser($id);
        if ($result === 0){
            header("location:../display-patient.php?e=db");
//            DATABASE ERROR OCCURRED
        }
        elseif ($result === 1){
            header("location:../display-patient.php?m-s");
//            PATIENT IS DELETED SUCCESSFULLY
        }
    }