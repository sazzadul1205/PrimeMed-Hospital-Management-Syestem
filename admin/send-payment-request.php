	<?php
	session_start();
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	include('include/config.php');
	if (!isset($_SESSION['id']) || strlen($_SESSION['id']) == 0) {
		header('location:logout.php');
	} else {

		if (isset($_POST['submit'])) {
			$patient_id = $_POST['patient'];
			$doctor_id = $_POST['doctor'];
			$amount = $_POST['amount'];
			$description = $_POST['description'];

			// Insert payment request
			$insert_query = "INSERT INTO payment_requests (patient_id, doctor_id, amount, description, status, created_at, updated_at)
						VALUES (?, ?, ?, ?, 'pending', NOW(), NOW())";
			$insert_stmt = $con->prepare($insert_query);
			$insert_stmt->bind_param("iids", $patient_id, $doctor_id, $amount, $description);

			if ($insert_stmt->execute()) {
				$payment_request_id = $con->insert_id;

				echo "<script>alert('Payment request sent successfully!');</script>";
			} else {
				echo "<script>alert('Error: Could not send payment request. Please try again.');</script>";
			}
		}

		// Function to send payment request notification (placeholder)
		function sendPaymentRequestNotification($patient_id, $amount, $description)
		{
			// Implement notification logic here, e.g., email or SMS
			// For now, just a placeholder
		}
	?>

		<!DOCTYPE html>
		<html lang="en">

		<head>
			<title>Admin | Send Payment Request</title>

			<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
			<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
			<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
			<link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
			<link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
			<link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
			<link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
			<link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
			<link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
			<link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
			<link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
			<link rel="stylesheet" href="assets/css/styles.css">
			<link rel="stylesheet" href="assets/css/plugins.css">
			<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
		</head>

		<body>
			<div id="app">
				<?php include('include/sidebar.php'); ?>
				<div class="app-content">

					<?php include('include/header.php'); ?>

					<!-- end: TOP NAVBAR -->
					<div class="main-content">
						<div class="wrap-content container" id="container">
							<!-- start: PAGE TITLE -->
							<section id="page-title">
								<div class="row">
									<div class="col-sm-8">
										<h1 class="mainTitle">Admin | Send Payment Request</h1>
									</div>
									<ol class="breadcrumb">
										<li>
											<span>Admin</span>
										</li>
										<li class="active">
											<span>Send Payment Request</span>
										</li>
									</ol>
								</div>
							</section>
							<!-- end: PAGE TITLE -->
							<!-- start: BASIC EXAMPLE -->
							<div class="container-fluid container-fullw bg-white">
								<div class="row">
									<div class="col-md-12">

										<div class="row margin-top-30">
											<div class="col-lg-8 col-md-12">
												<div class="panel panel-white">
													<div class="panel-heading">
														<h5 class="panel-title">Send Payment Request</h5>
													</div>
													<div class="panel-body">

														<form role="form" method="post">
															<div class="form-group">
																<label for="patient">
																	Select Patient
																</label>
																<select name="patient" class="form-control" required="true">
																	<option value="">Select Patient</option>
																	<?php $ret = mysqli_query($con, "select * from users");
																	while ($row = mysqli_fetch_array($ret)) {
																	?>
																		<option value="<?php echo htmlentities($row['id']); ?>">
																			<?php echo htmlentities($row['fullName']); ?>
																		</option>
																	<?php } ?>

																</select>
															</div>

															<div class="form-group">
																<label for="doctor">
																	Select Doctor
																</label>
																<select name="doctor" class="form-control" required="true">
																	<option value="">Select Doctor</option>
																	<?php $ret = mysqli_query($con, "select * from doctors");
																	while ($row = mysqli_fetch_array($ret)) {
																	?>
																		<option value="<?php echo htmlentities($row['id']); ?>">
																			<?php echo htmlentities($row['doctorName']); ?>
																		</option>
																	<?php } ?>

																</select>
															</div>


															<div class="form-group">
																<label for="amount">
																	Amount
																</label>
																<input type="number" name="amount" class="form-control" placeholder="Enter Amount" required="true" step="0.01">
															</div>
															<div class="form-group">
																<label for="description">
																	Description
																</label>
																<textarea name="description" class="form-control" placeholder="Enter Description" required="true"></textarea>
															</div>



															<button type="submit" name="submit" id="submit" class="btn btn-o btn-primary">
																Submit
															</button>
														</form>
													</div>
												</div>
											</div>

										</div>
									</div>
									<div class="col-lg-12 col-md-12">
										<div class="panel panel-white">


										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- end: BASIC EXAMPLE -->






					<!-- end: SELECT BOXES -->

				</div>
			</div>
			</div>
			<!-- start: FOOTER -->
			<?php include('include/footer.php'); ?>
			<!-- end: FOOTER -->

			<!-- start: SETTINGS -->
			<?php include('include/setting.php'); ?>

			<!-- end: SETTINGS -->
			</div>
			<!-- start: MAIN JAVASCRIPTS -->
			<script src="vendor/jquery/jquery.min.js"></script>
			<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
			<script src="vendor/modernizr/modernizr.js"></script>
			<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
			<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
			<script src="vendor/switchery/switchery.min.js"></script>
			<!-- end: MAIN JAVASCRIPTS -->
			<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
			<script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
			<script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
			<script src="vendor/autosize/autosize.min.js"></script>
			<script src="vendor/selectFx/classie.js"></script>
			<script src="vendor/selectFx/selectFx.js"></script>
			<script src="vendor/select2/select2.min.js"></script>
			<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
			<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
			<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
			<!-- start: CLIP-TWO JAVASCRIPTS -->
			<script src="assets/js/main.js"></script>
			<!-- start: JavaScript Event Handlers for this page -->
			<script src="assets/js/form-elements.js"></script>
			<script>
				jQuery(document).ready(function() {
					Main.init();
					FormElements.init();
				});
			</script>
			<!-- end: JavaScript Event Handlers for this page -->
			<!-- end: CLIP-TWO JAVASCRIPTS -->
		</body>

		</html>
	<?php } ?>