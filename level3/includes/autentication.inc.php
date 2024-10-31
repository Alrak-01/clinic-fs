<?php

$authentication = new Login();
$authentication->tableName = "tbl_users";
if (isset($_POST['submit'])){
    $email = stripslashes($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)){
        header("location:../login.php?e=emp&mail=".$email);
//        INPUT FIELD IS EMPTY
    }
    else{
    $result = $authentication->userLogin($email, $password);
    if ($result === 0){
        header("location:../login.php?e=emp&mail=".$email);
//        EMAIL NOT FOUND
    }
    elseif ($result === 10){
        header("location:../login.php?e=emp&mail=".$email);
//        PASSWORD NOT CORRECT
    }
    else{
        session_start();
        $_SESSION['lastname'] = $result['lastname'];
        $_SESSION['firstname'] = $result['firstname'];
        $_SESSION['middlename'] = $result['middlename'];
        $_SESSION['id'] = $result['id'];
//  REDIRECTING TO THE DASHBOARD
        header("location:../index.php?m=s");
//        USER SUCCESSFULLY LOGGED IN
    }
    }
}

//    FORGET PASSWORD BEGINS
    if (isset($_POST['update'])){
        $email = stripslashes($_POST['email']);

        if (empty($email)){
            header("location:../forgetPassword.php?e=emp");
//            INPUT FIELD IS EMPTY
        }
        else{
            $token = bin2hex(random_bytes(50));
            $result = $authentication->forgetPassword($email, $token);
            if ($result === 0){
                header("location:../forgetPassword.php?e=enf");
//                EMAIL NOT FOUND
            }
            elseif ($result === 10){
                header("location:../forgetPassword.php?e=db");
//                DATABASE ERROR OCCURRED
            }
            else{
                $lastname = $result['lastname'];
                $firstname = $result['firstname'];

                $resetLink = 'https://' . $_SERVER['HTTP_HOST'] . '/resetPassword.php?token=' . $token;

                $text = "<pre>Dear $lastname $firstname,
                                
                                We have received a request to reset your password. To proceed with the password reset, please click on the link below:
                                
                                <a href='" . htmlspecialchars($resetLink) . "'>" . htmlspecialchars($resetLink) . "</a>
                                
                                If you did not request a password reset, you can safely ignore this email. Your account remains secure.
                                
                                Please note that this link is valid for a limited time. If you do not use it within 10 minutes, you will need to request another password reset.
                                
                                Thank you,
                                [Khealtrite]</pre>";

                $to = $email;
                $subject = "Reset Lost Password";
                $message = $text;
                $headers = "From:khealtrite.gmail.com\r\n";
                $headers .= "Reply-To: khealtrite.gmail.com\r\n";
//                      SEND EMAIL
                if (mail($to, $subject, $message, $headers)) {
                    header("location:../processing.php");
//                    REDIRECT TO PROCESSING PAGE...
                } else {
                    header("location:../forgetPassword.php?e=e&em=".$email);
//                    REDIRECT BACK TO FORGET PASSWORD PAGE AND RESTATE THE PROCESS
                }
            }
        }
    }

//        RESET PASSWORD BEGINS
    if (isset($_POST['resetPassword'])){
        if (isset($_GET['token'])){
            $password = $_POST['password'];
            $confirmPassword = $_POST['newPassword'];
            $token = $_POST['token'];
            if (empty($password) || empty($confirmPassword)){
                header("location:../resetPassword.php?e=emp&token=".$token);
//            INPUT FIELD IS EMPTY
            }
            elseif (strlen($password) < 8){
                header("location:../resetPassword.php?e=pl&token=".$token);
//                PASSWORD MUST BE MORE THAN 8
            }
            elseif ($password !== $confirmPassword){
                header("location:../resetPassword.php?e=pl&token=".$token);
//                PASSWORD DOES NOT MATCH
            }
            else{
                $result = $authentication->resetPassword($token, $password);
                if ($result === 0){
                    header("location:../login.php?e=tnf");
//                    TOKEN NOT FOUND OR TOKEN EXPIRED
                }
                elseif ($result === 10){
                    header("location:../resetPassword.php?e=dbp&token=".$token);
//                    PASSWORD UPDATE FAILED (DATABASE ERROR)
                }
                elseif ($result === 1){
                    header("location:../login.php?m=s");
                }
            }
            header("location:../login.php?e=t");
//            TOKEN NOT FOUND IN URL
        }
    }

//        PASSWORD CHANGE STARTS
    if (isset($_POST['changePassword'])){
       $oldPassword = $_POST['oldPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmNewPassword = $_POST['confirmNewPassword'];

        if (empty($oldPassword) || empty($newPassword) || empty($confirmNewPassword)){
            header("location:../changePassword.php?e=emp");
//            INPUT FIELD IS EMPTY
        }
        elseif (strlen($newPassword) < 8){
            header("location:../changePassword.php?e=emp");
//            PASSWORD FIELD MUST BE ABOVE 8
        }
        elseif ($newPassword !== $confirmNewPassword){
            header("location:../changePassword.php?e=pnm");
//            PASSWORD DOES NOT MATCH
        }
        else{
            $id = $_SESSION['id'];
            $result = $authentication->changePassword($id, $oldPassword, $newPassword);
            if ($result === 0){
                header("location:../changePassword.php?e=db");
//                DATABASE ERROR OCCURRED
            }
            elseif ($result === 1){
                header("location:../changePassword.php?m=s");
//                PASSWORD CHANGED SUCCESSFULLY
            }
        }
    }

//    CHANGE EMAIL STARTS
    if (isset($_POST['changeEmail'])){
        $email = stripslashes($_POST['email']);
        $password = $_POST['password'];
        $id = $_SESSION['id'];
        if (empty($email) || empty($password)){
            header("location:../changeEmail.php?e=emp");
//            INPUT FIELD IS EMPTY
        }
        else{
            $result = $authentication->changeEmaIl($id, $password, $email);
            if ($result === 0){
                header("location:../login.php?e=id");
//              SESSION ID NOT FOUND
            }
            elseif ($result === 10){
                header("location:../changeEmail.php?e=pwd");
//                PASSWORD IS NOT CORRECT
            }
            elseif ($result === 100){
                header("location:../changeEmail.php?e=et");
//                EMAIL TAKEN
            }
            elseif ($result === 1000){
                header("location:../changeEmail.php?e=db");
//                DATABASE ERROR OCCURRED
            }
            elseif ($result === 1){
                header("location:../changeEmail.php?m=s");
//                EMAIL CHANGED SUCCESSFULLY
            }
        }
    }