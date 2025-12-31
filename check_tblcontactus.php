<?php
include('admin/include/config.php');

$result = mysqli_query($con, 'DESCRIBE tblcontactus');
echo "Columns in tblcontactus:\n";
while($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . "\n";
}
?>
