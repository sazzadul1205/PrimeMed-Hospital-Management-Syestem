<?php
include('admin/include/config.php');

echo "DB Connection: " . (mysqli_connect_errno() ? 'Failed' : 'OK') . PHP_EOL;

$tables = ['users', 'doctors', 'appointment', 'tblpatient', 'tblcontactus'];
foreach($tables as $table) {
    $result = mysqli_query($con, "SELECT 1 FROM $table LIMIT 1");
    echo "$table: " . ($result ? 'Exists' : 'Not Found - ' . mysqli_error($con)) . PHP_EOL;
}
?>
