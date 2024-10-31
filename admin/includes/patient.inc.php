<?php

$patient = new Users();
$patient->tableName = "tbl_users";

if (isset($_POST['submit'])){
    $lastname = stripslashes($_POST['lastname']);
    $firstname = stripslashes($_POST['firstname']);
    $middlename = stripslashes($_POST['middlename']);
    $email = stripslashes($_POST['email']);
    $matricNo = $_POST['matricno'];
    $address = stripslashes($_POST['address']);
    $gender = stripslashes($_POST['gender']);
    $dob = stripslashes($_POST['dob']);
    $mobile = stripslashes($_POST['mobile']);

    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    $extension = explode(".", $fileName);
    $actualExt = strtolower(end($extension));

    $allowed = array("jpg", "jpeg", "png");

    if (empty($lastname) || empty($firstname) || empty($middlename) || empty($email) || empty($matricNo) || empty($address) || empty($file) || empty($gender) || empty($dob) || empty($mobile)){
        header("location:../add-patient.php?e=emp");
//        INPUT FIELD IS EMPTY
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header("location:../add-patient.php?e=mail");
//        EMAIL ADDRESS IS NOT VALID
    }
    elseif (!ctype_alnum($lastname) || !ctype_alnum($firstname) || !ctype_alnum($middlename) || !ctype_alnum($address) || !ctype_alnum($gender) || !ctype_alnum($mobile)){
        header("location:../add-patient.php?e=emp");
//        INPUT CONTAINS OTHER CHARACTERS ASIDE ALPHANUMERIC CHARACTERS
    }
    elseif (!in_array($actualExt, $allowed)){
        header("location:../add-patient.php?e=ftype");
//      FILE TYPE NOT ACCEPTED
    }
    elseif ($fileSize > 1000000){
        header("location:../add-patient.php?e=size");
//        FILE SIZE IS TOO MUCH
    }
    elseif ($fileError !== 0){
        header("location:../add-patient.php?e=e");
//        ERROR OCCURRED WHILE UPLOADING THE IMAGE
    }
    else{
        $password = password_hash($lastname, PASSWORD_DEFAULT);
        $fileNewName = uniqid("", true).".".$actualExt;
        $fileDestination = "../assets/uploads/".$fileNewName;

        $patient->instance = array("lastname", "firstname", "middlename", "matricno", "address", "filename", "gender", "dob", "email", "password", "level");
        $patient->values = array("?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?");
        $patient->execute = array($lastname, $firstname, $middlename, $matricNo, $address, $fileNewName, $gender, $dob, $email, $password, 3);
        $result = $patient->createUser();

        if ($result === 0){
            header("location:../add-patient.php?e=e");
//            DATABASE ERROR OCCURRED WHILE CREATING PATIENT
        }
        elseif ($result === 1){
            if (move_uploaded_file($fileTmpName, $fileDestination)){
                header("location:../display-patient.php?m=s");
//            PATIENT ADDED SUCCESSFULLY
            }
            header("location:../add-patient.php?e=up");
//            ERROR OCCURRED WHILE MOVING THE FILE
        }
    }
}

// PATIENT  UPDATE BEGINS
    if (isset($_POST['update'])){
       if (isset($_GET['id'])){
           $lastname = stripslashes($_POST['lastname']);
           $firstname = stripslashes($_POST['firstname']);
           $middlename = stripslashes($_POST['middlename']);
           $matricNo = $_POST['matricno'];
           $address = stripslashes($_POST['address']);
           $gender = stripslashes($_POST['gender']);
           $dob = stripslashes($_POST['dob']);

//        MEDICAL INFORMATION DETAIL
//        $height = stripslashes($_POST['height']);
//        $weight = stripslashes($_POST['weight']);
//        $blood_group = stripslashes($_POST['blood_group']);
//        $blood_group = stripslashes($_POST['blood_group']);
//        $pulse = stripslashes($_POST['pulse']);
//        $respiration = stripslashes($_POST['respiration']);
//        $allergy = stripslashes($_POST['allergy']);
//        $diet = stripslashes($_POST['diet']);
//        $patient_id =

           if (empty($lastname) || empty($firstname) || empty($middlename) || empty($matricNo) || empty($address)  || empty($gender) || empty($dob) || empty($mobile)){
               header("location:../update-patient.php?e=emp");
//        INPUT FIELD IS EMPTY
           }
           elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
               header("location:../update-patient.php?e=mail");
//        EMAIL ADDRESS IS NOT VALID
           }
           elseif (!ctype_alnum($lastname) || !ctype_alnum($firstname) || !ctype_alnum($middlename) || !ctype_alnum($address) || !ctype_alnum($gender) || !ctype_alnum($mobile)){
               header("location:../update-patient.php?e=emp");
//        INPUT CONTAINS OTHER CHARACTERS ASIDE ALPHANUMERIC CHARACTERS
           }
           else{
               $id = $_GET['id'];
               $patient->instance = array("lastname = ?", "firstname = ?", "middlename = ?", "maticno = ?", "address = ?", "mobile = ?", "gender = ?");
               $patient->execute = array("$lastname", "$firstname", "$middlename", "$matricNo", "$address", "$mobile", "$gender", "dob");
               $result = $patient->updateUser($id);

               if ($result === 0){
                   header("location:../update-patient.php?e=db");
//                DATABASE ERROR OCCURRED
               }
               elseif ($result === 1){
                   header("location:../display-patient.php?m=s");
//                UPDATE SUCCESSFULL
               }
           }
       }
    }

//    DELETE PATIENT BEGINS
    if (isset($_GET['del']) && isset($_GET['id'])){
        $id = $_GET['id'];
        $result = $patient->deleteUser($id);
        if ($result === 0){
            header("location:../display-patient.php?e=db");
//            DATABASE ERROR OCCURRED WHILE DELETING PATIENT
        }
        elseif ($result === 1){
            header("location:../display-patient.php?e=db");
//           PATIENT DELETED SUCCESSFULLY
        }
    }