<?php
include('include/config.php');
error_reporting(E_ALL);

/**
 * Load doctors by specialization
 */
if (!empty($_POST['specilizationid'])) {

  $specId = (int) $_POST['specilizationid'];

  $stmt = $con->prepare(
    "SELECT id, doctorName FROM doctors WHERE specialization_id = ?"
  );
  $stmt->bind_param("i", $specId);
  $stmt->execute();
  $result = $stmt->get_result();

  echo '<option value="">Select Doctor</option>';

  while ($row = $result->fetch_assoc()) {
    echo '<option value="' . $row['id'] . '">'
      . htmlentities($row['doctorName']) . '
        </option>';
  }

  exit();
}

/**
 * Load doctor fee
 */
if (!empty($_POST['doctor'])) {

  $doctorId = (int) $_POST['doctor'];

  $stmt = $con->prepare(
    "SELECT docFees FROM doctors WHERE id = ? LIMIT 1"
  );
  $stmt->bind_param("i", $doctorId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    echo '<option value="' . htmlentities($row['docFees']) . '">'
      . htmlentities($row['docFees']) .
      '</option>';
  }

  $stmt->close();
  exit();
}
