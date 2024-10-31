<?php

$doctor = new Users();
$doctor->tableName = "tbl_users";
// CREATE DOCTOR BEGINS
if (isset($_POST['submit'])){
    $lastname = trim(stripslashes($_POST['lastname']));
    $firstname = trim(stripslashes($_POST['firstname']));
    $middlename = trim(stripslashes($_POST['middlename']));
    $email = trim(stripslashes($_POST['email']));
    $mobile = trim(stripslashes($_POST['mobile']));
    $department = trim(stripslashes($_POST['department']));
    $address = stripslashes($_POST['address']);
    $gender = trim(stripslashes($_POST['gender']));
    $dob = trim(stripslashes($_POST['dob']));

    $file = $_FILES['file'];

    $fileTmpName = $file['tmp_name'];
    $fileError = $file['error'];
    $fileName = $file['name'];
    $fileSize = $file['size'];

    $extention = explode(".", $fileName);
    $actualExt = strtolower(end($extention));
    $allowed = array("jpg", "png", "jpeg");

    if (empty($lastname) || empty($firstname) || empty($middlename) || empty($email) || empty($mobile) || empty($department) || empty($address) || empty($file)){
        header("location:../add-doctors.php?e=em");
//        INPUT FIELD IS EMPTY
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header("location:../add-doctors.php?e=mail");
//        EMAIL ADDRESS NOT VALID
    }
    elseif (!ctype_alnum($lastname) || !ctype_alnum($firstname) || !ctype_alnum($middlename) || !ctype_alnum($mobile) || !ctype_alnum($department)){
        header("location:../add-doctors.php?e=ah");
//        INPUT CONTAINS OTHER CHARACTERS ASIDE ALPHANUMERIC CHARACTERS
    }
    elseif (!in_array($actualExt, $allowed)){
        header("location:../add-doctors.php?e=ah");
//        FILE TYPE NOT ACCEPTED
    }
    elseif ($fileSize > 1000000){
        header("location:../add-doctors.php?e=sz");
//        FILE SIZE IS TOO LARGE
    }
    elseif ($fileError !== 0 ){
        header("location:../add-doctors.php?e=er");
//        AN ERROR OCCURED WHILE UPLOADING THE FILE
    }
    else{
    $fileNewName = uniqid("", true).".".$actualExt;
    $fileDestination = "../assets/uploads/".$fileNewName;

    $password = password_hash($lastname, PASSWORD_DEFAULT);

    $doctor->instance = array("lastname", "firstname", "middlename", "address", "mobile",  "filename", "gender", "dob",  "department", "email", "password", "level");
    $doctor->values = array("?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?");
    $doctor->execute = array($lastname, $firstname, $middlename, $address, $mobile, $fileNewName, $gender, $dob, $department, $email, $password, 2);
    $result = $doctor->createUser();

    if ($result === 0){
    header("location:../add-doctors.php?e=db");
//        DATABASE ERROR OCCURRED WHILE UPLOADING
    }
    elseif ($result === 1){
      if (move_uploaded_file($fileTmpName, $fileDestination)){
          header("location:../display-doctors.php?m=s");
//        DOCTOR CREATED SUCCESSFULLY AND FILE IS MOVED TO NEW FOLDER
      }
      else{
          header("location:../add-doctors.php?e=file");
//          ERROR OCCURRED WHILE MOVING THE FILE
      }
    }
    }
}

//  UPDATE DOCTOR BEGINS
    if (isset($_POST['update'])){
        $lastname = trim(stripslashes($_POST['lastname']));
        $firstname = trim(stripslashes($_POST['firstname']));
        $middlename = trim(stripslashes($_POST['middlename']));
        $email = trim(stripslashes($_POST['email']));
        $mobile = trim(stripslashes($_POST['mobile']));
        $department = trim(stripslashes($_POST['department']));
        $address = stripslashes($_POST['address']);

        if (empty($lastname) || empty($firstname) || empty($middlename) || empty($email) || empty($mobile) || empty($department)){
            header("location:../update-doctors.php?e=em");
//        INPUT FIELD IS EMPTY
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            header("location:../update-doctors.php?e=mail");
//        EMAIL ADDRESS NOT VALID
        }
        elseif (!ctype_alnum($lastname) || !ctype_alnum($firstname) || !ctype_alnum($middlename) || !ctype_alnum($mobile) || !ctype_alnum($department)){
            header("location:../update-doctors.php?e=ah");
//        INPUT CONTAINS OTHER CHARACTERS ASIDE ALPHANUMERIC CHARACTERS
        }
        else{
            $id = $_GET['id'];
            $doctor->instance = array("lastname = ?", "firstname = ?", "middlename = ?", "mobile = ?", "department = ?", "address = ?");
            $doctor->execute = array($lastname, $firstname, $middlename, $mobile, $department, $address);
            $result = $doctor->updateUser($id);

            if ($result === 0){
                header("location:../update-doctors.php?e=db");
//                DATABASE ERROR OCCURRED
            }
            elseif ($result === 1){
                header("location:../update-doctors.php?m=s");
//                DOCTOR UPDATED SUCCESSFULLY
            }
        }
    }

//    DELETE DOCTOR BEGINS
        if (isset($_GET['del']) && isset($_GET['id'])){
            $id = $_GET['id'];
            $result = $doctor->deleteUser($id);
            if ($result === 0){
                header("location:../list-doctors.php?e=db");
//                DATABASE ERROR OCCURRED WHILE UPLOADING
            }
            elseif ($result === 1){
                header("location:../list-doctors.php?m=s");
//                DOCTOR DELETED SUCCESSFULLY
            }
        }