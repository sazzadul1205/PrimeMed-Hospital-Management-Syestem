<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$con = mysqli_connect('localhost', 'root', '', 'hms');
if (!$con) {
    die('Connection failed: ' . mysqli_connect_error());
}

$result = mysqli_query($con, 'SHOW TABLES LIKE "payment_requests"');
if (mysqli_num_rows($result) > 0) {
    echo 'payment_requests table exists';
} else {
    echo 'payment_requests table does not exist';
}

mysqli_close($con);
?>
