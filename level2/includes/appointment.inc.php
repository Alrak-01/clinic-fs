<?php

    $appointment = new Appointment();
    $appointment->tableName = "tbl_appointment";
    if (isset($_POST['submit'])){
        $date = stripslashes($_POST['date']);
        $to = $_POST['to'];
        $from = $_POST['from'];
        $description = stripslashes($_POST['description']);
        $patient_id = $_POST['patient'];

        $pattern = '/^[a-zA-Z0-9.,()"\' ]+$/';
        if (empty($date) || empty($to) || empty($from) || empty($description)){
            header("location:../add-appointment.php?e=emp");
//            INPUT FIELD IS EMPTY
        }
        elseif (!preg_match($pattern, $description))){
            header("location:../add-appointment.php?e=alh");
//            INPUT CONTAIN CHARACTERS THAT'S NOT ALLOWED
        }
        else{
            $doctor_id = $_SESSION['id'];
            $time = $from." to".$to;
            $result = $appointment->createAppointment($date, $time, $description, $patient_id, $doctor_id);
            if ($result === 0){
                header("location:../add-appointment.php?e=alh");
//                DATABASE ERROR OCCURRED
            }
            elseif ($result === 1){
                header("location:../add-appointment.php?m=s");
//                APPOINTMENT CREATED SUCCESSFULLY
            }
        }
    }

    if (isset($_POST['update'])){
        $date = $_POST['date'];
        $description = stripslashes($_POST['description']);
        $to = $_POST['to'];
        $from = $_POST['from'];

        $pattern = '/^[a-zA-Z0-9.,()"\' ]+$/';
        if (empty($date) || empty($description) || empty($to) || empty($from)){
            header("location:../update-appointment?e=emp");
//            INPUT FIELD IS EMPTY
        }
        elseif (!preg_match($pattern, $description)){
            header("location:../update-appointment?e=alh");
//          INPUT CONTAIN CHARACTERS THAT'S NOT ALLOWED
        }
        else{
            $time = $from."  to ".$to;
            $id = $_GET['id'];
            $result = $appointment->updateAppointment($date, $time, $description, $id);
            if ($result === 0){
                header("location:../update-appointment.php?e=db");
//                DATABASE ERROR OCCURRED
            }
            elseif ($result === 1){
                header("location:../display-appointment.php?m=s");
//                APPOINTMENT UPDATED SUCCESSFULLY
            }
        }
    }

        if (isset($_GET['del']) && isset($_GET['id'])){
            $id = $_GET['id'];
            $result = $appointment->deleteAppointment($id);
            if ($result === 0){
                header("location:../display-appointment.php?e=db");
//                DATABASE ERROR OCCURRED
            }
            elseif ($result === 1){
                header("location:../display-appointment.php?m=s");
//                APPOINTMENT DELETED SUCCESSFULLY
            }
        }