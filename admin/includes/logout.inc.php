<?php
session_start();
//UNSET ALL SESSION
$_SESSION = [];
// DESTROY ALL SESSION
session_destroy();
//REDIRECTING TO THE LOGIN PAGE
header("location:../admin-login.php?lo=s");
exit();