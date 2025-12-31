<?php

/*********************************************************
 * INITIAL SETUP & AUTH
 *********************************************************/
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

error_reporting(E_ALL);
$_SESSION['msg1'] = $_SESSION['msg1'] ?? '';

/*********************************************************
 * AJAX HANDLER: LOAD DOCTORS BY SPECIALIZATION ID
 *********************************************************/
if (isset($_POST['ajax']) && $_POST['ajax'] === 'getDoctors') {

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

/*********************************************************
 * AJAX HANDLER: LOAD DOCTOR FEES
 *********************************************************/
if (isset($_POST['ajax']) && $_POST['ajax'] === 'getFees') {

	$doctorId = (int) $_POST['doctor'];

	$stmt = $con->prepare(
		"SELECT docFees FROM doctors WHERE id = ? LIMIT 1"
	);
	$stmt->bind_param("i", $doctorId);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($row = $result->fetch_assoc()) {
		echo '<option value="' . $row['docFees'] . '">'
			. htmlentities($row['docFees']) . '
        </option>';
	}
	exit();
}

/*********************************************************
 * FORM SUBMISSION: BOOK APPOINTMENT
 *********************************************************/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

	$specId   = (int) ($_POST['Doctorspecialization'] ?? 0);
	$doctorId = (int) ($_POST['doctor'] ?? 0);
	$fees     = trim($_POST['fees'] ?? '');
	$appdate  = trim($_POST['appdate'] ?? '');
	$time     = trim($_POST['apptime'] ?? '');
	$userid   = $_SESSION['id'];

	if (!$specId || !$doctorId || $fees === '' || $appdate === '' || $time === '') {
		$_SESSION['msg1'] = "All fields are required";
		header("Location: book-appointment.php");
		exit();
	}

	$stmt = $con->prepare(
		"INSERT INTO appointment
        (doctorSpecialization, doctorId, userId, consultancyFees,
         appointmentDate, appointmentTime, userStatus, doctorStatus)
        VALUES (?, ?, ?, ?, ?, ?, 1, 1)"
	);

	$stmt->bind_param(
		"iiisss",
		$specId,
		$doctorId,
		$userid,
		$fees,
		$appdate,
		$time
	);

	if ($stmt->execute()) {
		$_SESSION['msg1'] = "Your appointment successfully booked";
	} else {
		$_SESSION['msg1'] = "Failed to book appointment";
	}

	header("Location: book-appointment.php");
	exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>User | Book Appointment</title>

	<!-- CSS -->
	<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="vendor/bootstrap-datepicker/bootstrap-datepicker3.min.css">
	<link rel="stylesheet" href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css">
	<link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
	<div id="app">

		<?php include('include/sidebar.php'); ?>

		<div class="app-content">
			<?php include('include/header.php'); ?>

			<div class="main-content">
				<div class="container-fluid container-fullw bg-white">

					<h3>Book Appointment</h3>

					<p class="text-danger">
						<?php echo htmlentities($_SESSION['msg1']);
						$_SESSION['msg1'] = ""; ?>
					</p>

					<form method="post">

						<!-- SPECIALIZATION -->
						<div class="form-group">
							<label>Doctor Specialization</label>
							<select name="Doctorspecialization" class="form-control" onchange="getdoctor(this.value)" required>
								<option value="">Select Specialization</option>
								<?php
								// Only fetch specializations that have at least one doctor
								$ret = mysqli_query($con, "
            SELECT id, specilization 
            FROM doctorspecilization 
            WHERE id IN (SELECT DISTINCT specialization_id FROM doctors)
        ");

								while ($row = mysqli_fetch_assoc($ret)) {
								?>
									<option value="<?php echo $row['id']; ?>">
										<?php echo htmlentities($row['specilization']); ?>
									</option>
								<?php } ?>
							</select>
						</div>


						<!-- DOCTOR -->
						<div class="form-group">
							<label>Doctor</label>
							<select name="doctor" id="doctor" class="form-control"
								onchange="getfee(this.value)" required>
								<option value="">Select Doctor</option>
							</select>
						</div>

						<!-- FEES -->
						<div class="form-group">
							<label>Consultancy Fees</label>
							<select name="fees" id="fees" class="form-control" readonly></select>
						</div>

						<!-- DATE -->
						<div class="form-group">
							<label>Date</label>
							<input type="text" name="appdate" class="form-control datepicker" required>
						</div>

						<!-- TIME -->
						<div class="form-group">
							<label>Time</label>
							<input type="text" name="apptime" id="timepicker" class="form-control" required>
						</div>

						<button type="submit" name="submit" class="btn btn-primary">
							Book Appointment
						</button>

					</form>

				</div>
			</div>
		</div>

		<?php include('include/footer.php'); ?>
	</div>

	<!-- JS -->
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
	<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>

	<script>
		function getdoctor(val) {
			$.post("book-appointment.php", {
				ajax: "getDoctors",
				specilizationid: val
			}, function(data) {
				$("#doctor").html(data);
				$("#fees").html('');
			});
		}

		function getfee(val) {
			$.post("book-appointment.php", {
				ajax: "getFees",
				doctor: val
			}, function(data) {
				$("#fees").html(data);
			});
		}

		$('.datepicker').datepicker({
			format: 'yyyy-mm-dd',
			startDate: '+0d'
		});

		$('#timepicker').timepicker();
	</script>

</body>

</html>