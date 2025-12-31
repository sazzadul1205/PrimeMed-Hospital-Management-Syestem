<?php
include('admin/include/config.php');

$tables = [
    'users' => ['fullName', 'regDate'],
    'doctors' => ['doctorName', 'creationDate'],
    'appointment' => ['postingDate'],
    'tblcontactus' => ['PostingDate', 'name', 'IsRead']
];

foreach($tables as $table => $columns) {
    echo "Table: $table\n";
    $result = mysqli_query($con, "DESCRIBE $table");
    $existing_columns = [];
    while($row = mysqli_fetch_assoc($result)) {
        $existing_columns[] = $row['Field'];
    }
    foreach($columns as $col) {
        echo "  $col: " . (in_array($col, $existing_columns) ? 'Exists' : 'Not Found') . "\n";
    }
    echo "\n";
}
?>
