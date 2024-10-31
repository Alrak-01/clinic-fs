<?php
//require_once("../../modals/admin.login.php");

$login = new Login();
if(isset($_POST['submit'])){
   $email = stripslashes($_POST['email']);
   $password = stripslashes($_POST['password']);

   if (empty($email) || empty($password)){
       header("location:../admin-login.php?e=e");
   }
  else{
      $login->tableName = "tbl_users";
      $result = $login->userLogin();
      if ($result === 0){
          header("location:../admin-login.php?e=e&em=".$email);
//          EMAIL NOT FOUND...
      }
      elseif ($result === 10){
          header("location:../admin-login.php?e=e&em=".$email);
//          PASSWORD IS INCORRECT...
      }
        elseif ($result['level'] === 1){
          session_start();
            $_SESSION['lastname'] = $result['lastname'];
            $_SESSION['firstname'] = $result['firstname'];
            $_SESSION['id'] = $result['id'];
            header("location:../index.php?l=s");
      }
  }
}

// FORGET PASSWORD STARTS
    if (isset($_POST['forgetPassword']){
        $email = stripslashes($_POST['email']);

        if (empty($email)){
            header("location:../admin-forgetPassword.php?e=e&em=".$email);
        }
        else{
            $login->tableName = "tbl_users";
            $token = bin2hex(random_bytes(50));   // GENERATE UNIQUE TOKEN
            $result = $login->forgetPassword($email, $token);

            if ($result === 0){
                header("location:../admin-forgetPassword.php?e=e&em=".$email);
//                EMAIL NOT FOUND
            }
            elseif ($result === 10){
                header("location:../admin-forgetPassword.php?e=e&em=".$email);
//                DATABASE ERRO OCCURED
            }
            else{
                $lastname = $result['lastname'];
                $firstname = $result['firstname'];
                $resetLink = "../resetpassword.php?token=".$token;

                // Define the reset link with token
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
//    SEND EMAIL
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
    if (isset($_POST['resetPassword'])){
        $token = $_GET['token'];
        $password = $_GET['password'];
        $confirmPassword = $_GET['confirmPassword'];

        if (empty($password) || empty($confirmPassword)){
            header("location:../resetPassword.php?e=ep");
//            INPUT FIELD IS EMPTY...
        }
        elseif (strlen($password) < 8 ){
            header("location:../resetPassword.php?e=imp");
//            PASSWORD TOO SHORT...
        }
        elseif ($password !== $confirmPassword){
            header("location:../resetPassword.php?e=nc");
//            PASSWORDS DOES NOT MATCH...
        }
        else{
            $login->tableName = "tbl_users";
            $result = $login ->resetPassword($token, $password);
            if ($result === 0){
                header("location:../resetPassword.php?e=t");
//                TOKEN NOT FOUND OR EXPIRED...
            }
            elseif ($result === 10){
                header("location:../resetPassword.php?e=db");
//                DATABASE ERROR OCCURED PASSWORD NOT UPDATED
            }
            elseif ($result === 100){
                header("location:../resetPassword.php?e=db");
//                PASSWORD UPDATE SUCCESSFULL BUT DELETE FAILLED
            }
            elseif($result === 1){
                header("location:../admin-login.php?s=s");
//                PASSWORD UPDATED SUCCESSFULLY
            }
        }
    }

//    CHANGE PASSWORD BEGINS
        if (isset($_POST['changePassword'])) {
            $oldPassword = $_POST['oldPass'];
            $newPassword = $_POST['newPass'];
            $confirmNewPassword = $_POST['confirmNewPass'];

            if (empty($oldPassword) || empty($newPassword) || empty($confirmNewPassword)) {
                header("location:../password-change.php?e=emp");
//                INPUT FILED IS EMPTY
            } elseif (strlen($newPassword) < 8) {
                header("location:../password-change.php?e=emp");
//                PASSWORD IS NOT STRONG
            } elseif ($newPassword !== $confirmNewPassword) {
                header("location:../password-change.php?e=emp");
//               PASSWORD MUST BE MORE THAN 8
            }
            else{
                session_start();
                $id = $_SESSION['id'];
                $login->tableName = "tbl_users";
                $result =  $login->changePassword($id, $oldPassword, $newPassword);
                if ($result === 0){
                    header("location:../password-change.php?e=db");
//                    DATABASE ERROR OCCURED
                }
                elseif ($result === 1){
                    header("location:../password-change.php?m=s");
//                    CHANGED PASSWORD CORRECTLY
                }
            }
        }

//        CHANGE EMAIL BEGINS
        if (isset($_POST['changeEmail'])){
            $password = $_POST['password'];
            $email = stripslashes( $_POST['email']);

            if (empty($password) || empty($email)){
                header("location:../changePassword.php?e=emp");
//                INPUT FIELD IS EMPTY
            }
            else{
                session_start();
                $id = $_SESSION['id'];
                $login->tableName = "tbl_users";
                $changeEmail = $login->changeEmaIl($id, $password, $email);
                if ($changeEmail === 0){
                    header("location:../admin-login.php?e=uid");
//                    USER ID NOT IDENTIFIED
                }
                elseif ($changeEmail === 10){
                    header("location:../changePassword.php?e=psw&mail=".$email);
//                    PASSWORD NOT CORRECT
                }
                elseif ($changeEmail === 100){
                    header("location:../changePassword.php?e=db&mail=".$email);
//                    DATABASE ERROR OCCURRED WHILE UPDATING EMAIL
                }
                elseif ($changeEmail === 1){
                    header("location:../index.php?m=s");
//                    EMAIL UPDATED SUCCESSFULLY
                }
            }
        }