<?php
session_start();
include('include/config.php');

date_default_timezone_set('Asia/Dhaka');

$did = $_SESSION['id'] ?? null;

if ($did) {
  $ldate = date('d-m-Y h:i:s A');
  mysqli_query(
    $con,
    "UPDATE doctorslog 
         SET logout='$ldate' 
         WHERE uid='$did' 
         ORDER BY id DESC 
         LIMIT 1"
  );
}

/* Clear session properly */
$_SESSION = [];
session_unset();
session_destroy();

/* Set logout message */
session_start();
$_SESSION['errmsg'] = "You have successfully logged out";

/* Proper PHP redirect */
header("Location: ../index.php");
exit();