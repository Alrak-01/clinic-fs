<?php
session_start();
//UNSET ALL SESSION
$_SESSION = [];
// DESTROY ALL SESSION
session_destroy();
//REDIRECT TO LOGIN PAGE
header("location:../login.php?m=s");
exit();
// USER LOGGED OUT SUCCESSFULLY