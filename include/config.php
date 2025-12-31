<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hms');

try {
  $con = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
  $con->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
  error_log($e->getMessage()); // log error, don’t expose details
  die('Database connection failed.');
}
