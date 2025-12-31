<?php
session_start();
include('include/config.php');

// Set timezone
date_default_timezone_set('Asia/Kolkata');

// Record logout time if user is logged in
if (isset($_SESSION['id'])) {
  $logoutTime = date('d-m-Y h:i:s A');

  // Use prepared statement for safety
  $stmt = $con->prepare("UPDATE userlog SET logout = ? WHERE uid = ? ORDER BY id DESC LIMIT 1");
  $stmt->bind_param("si", $logoutTime, $_SESSION['id']);
  $stmt->execute();
}

// Clear all session data and destroy session
session_unset();
session_destroy();

// Set logout message in a temporary session (optional)
session_start();
$_SESSION['errmsg'] = "You have successfully logged out";

// Redirect to homepage
header("Location: index.php");
exit;
