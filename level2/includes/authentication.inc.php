<?php

$authenticate = new Login();
$authenticate->tableName = "tbl_users";
if (isset($_POST['submit'])){
    $email = stripslashes($_POST['email']);
    $password = $_POST['email'];

    if (empty($email) || empty($password)){
        header("location:../login.php?e=emp&mail=".$email);
//      INPUT FIELD IS EMPTY
    }
    else{
        $result = $authenticate->userLogin($email, $password);
        if ($result === 0){
            header("location:../login.php?e=m&mail=".$email);
//            EMAIL NOT FOUND
        }
        elseif ($result === 10){
            header("location:../login.php?e=p&mail=".$email);
//            PASSWORD IS INCORRECT
        }
        else{
            session_start();
            $_SESSION['lastname'] = $result['lastname'];
            $_SESSION['firstname'] = $result['firstname'];
            $_SESSION['middlename'] = $result['middlename'];
            $_SESSION['id'] = $result['id'];
            header("location:../index.php?m=s");
//            USER LOGGED IN SUCCESSFULLY
        }
    }
}

//      FORGET PASSWORD STARTS
    if (isset($_POST['forgetPassword'])){
        $email = stripslashes($_POST['email']);
        if (empty($email)){
            header("location:../forget-password.php?e=emp&mail=".$email);
//            INPUT FIELD IS EMPTY
        }
        else{
            $token = bin2hex(random_bytes(50));
            $result = $authenticate->forgetPassword($email, $token);
            if ($result === 0){
                header("location:../forget-password.php?e=nf&mail=".$email);
//                EMAIL NOT FOUND
            }
            elseif ($result === 10){
                header("location:../forget-password.php?e=nf&mail=".$email);
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
                    header("location:../admin-forgetPassword.php?e=e&em=".$email);
//                    REDIRECT BACK TO FORGET PASSWORD PAGE AND RESTATE THE PROCESS
                }
            }
        }
    }

//    RESET PASSWORD STARTS
        if (isset($_POST['resetPassword']){
          if (isset($_GET['token'])){
              $password = $_POST['password'];
              $confirmPassword = $_POST['confirmPassword'];

              if (empty($password) || empty($confirmPassword)){
                  header("location:../resetPassword.php?e=emp");
//                INPUT FILED IS EMPTY
              }
              elseif (strlen($password) < 8){
                  header("location:../resetPassword.php?e=sp");
//                PASSSWORD IS NOT SECURE (SHORT)
              }
              elseif ($password !== $confirmPassword){
                  header("location:../resetPassword.php?e=ne");
//                PASSWORD DOES NOT CORRELATE
              }
              else{
                  $token = $_GET['token'];
                  $result = $authenticate->resetPassword($token, $password);
                  if ($result === 0){
                      header("location:../forgetPassword.php?e=tnf");
//                      TOKEN NOT FOUND OR TOKEN EXPIRED
                  }
                  elseif ($result === 10){
                      header("location:../resetPassword.php?e=db");
//                      PASSWORD UPDATE FAILED (DATABASE ERROR)
                  }
                  elseif ($result === 100){
                      header("location:../resetPassword.php?e=dbd");
//                      DELETE FAILED (DATABASE ERROR)
                  }
                  elseif ($result === 1){
                      header("location:../login.php?m=s");
//                      PASSWORD UPDATED SUCCESSFULLY
                  }
              }
          }
        }

//        CHANGE PASSWORD BEGINS
        if (isset($_POST['changePassword'])){
            $oldPassword = $_POST['oldPassword'];
            $newPassword = $_POST['newPassword'];
            $confirmNewPassword = $_POST['confirmPassword'];

            if (empty($oldPassword) || empty($newPassword) || empty($confirmNewPassword)){
                header("location:../change-password.php?e=emp");
//                INPUT FIELD IS EMPTY
            }
            elseif (strlen($newPassword) < 8){
                header("location:../change-password.php?e=ts");
//                PASSWORD IS TOO SHORT
            }
            elseif ($newPassword !== $confirmNewPassword){
                header("location:../change-password.php");
//                PASSWORD DOES NOT MATCH
            }
            else{
                $id = $_SESSION['id'];
                $result = $authenticate->changePassword($id, $oldPassword, $newPassword);
                if ($result === 0){
                    header("location:../login.php?e=id");
//                    SESSION ID NOT FOUND
                }
                elseif ($result === 10){
                    header("location:../change-password.php?e=pwdnf");
//                    CURRENT PASSWORD NOT CORRECT
                }
                elseif ($result === 100){
                    header("location:../change-password.php?e=db");
//                    DATABASE ERROR OCCURRED
                }
                elseif ($result === 1){
                    header("location:../change-password.php?m=s");
//                    PASSWORD CHANGED SUCCESSFULLY
                }
            }
        }

//            CHANGE EMAIL STARTS
        if (isset($_POST['changeEmail'])){
            $email = stripslashes($_POST['email']);
            $password = $_POST['password'];
            $id = $_SESSION['id'];

            if (empty($email) || empty($password)){
                header("location:../change-email.php?e=emp");
//                INPUT FIELD IS EMPTY
            }
            else{
                $result = $authenticate->changeEmaIl($id, $password, $email);
                if ($result === 0){
                    header("location:../login.php?e=tnf");
//                    SESSION ID NOT FOUND
                }
                elseif ($result === 10){
                    header("location:../change-email.php?e=pwdnf");
//                    PASSWORD IS NOT CORRECT
                }
                elseif ($result === 100){
                    header("location:../change-email.php?e=et");
//                    EMAIL IS TAKEN
                }
                elseif ($result === 1000){
                    header("location:../change-email.php?e=db");
//                    DATABASE ERROR OCCURRED
                }
                elseif ($result === 1){
                    header("location:../change-email.php?m=s");
//                    EMAIL IS CHANGED SUCCESSFULLY
                }
            }
        }