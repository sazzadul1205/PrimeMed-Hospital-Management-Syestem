<?php
session_start();
include('include/config.php');

// Redirect if not logged in
if (!isset($_SESSION['id']) || strlen($_SESSION['id']) == 0) {
	header('Location: logout.php');
	exit;
}

// Add Doctor Specialization
if (isset($_POST['submit'])) {
	$doctorspecilization = trim($_POST['doctorspecilization']);

	if (!empty($doctorspecilization)) {
		$stmt = $con->prepare("INSERT INTO doctorSpecilization(specilization) VALUES (?)");
		$stmt->bind_param("s", $doctorspecilization);
		if ($stmt->execute()) {
			$_SESSION['msg'] = "Doctor Specialization added successfully!";
		} else {
			$_SESSION['msg'] = "Error adding specialization.";
		}
	} else {
		$_SESSION['msg'] = "Specialization cannot be empty!";
	}
}

// Delete Doctor Specialization
if (isset($_GET['del']) && isset($_GET['id'])) {
	$sid = intval($_GET['id']);
	$stmt = $con->prepare("DELETE FROM doctorSpecilization WHERE id = ?");
	$stmt->bind_param("i", $sid);
	if ($stmt->execute()) {
		$_SESSION['msg'] = "Doctor Specialization deleted!";
	} else {
		$_SESSION['msg'] = "Error deleting specialization.";
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Admin | Doctor Specialization</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- CSS -->
	<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
	<link rel="stylesheet" href="vendor/animate.css/animate.min.css">
	<link rel="stylesheet" href="vendor/perfect-scrollbar/perfect-scrollbar.min.css">
	<link rel="stylesheet" href="vendor/switchery/switchery.min.css">
	<link rel="stylesheet" href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css">
	<link rel="stylesheet" href="vendor/select2/select2.min.css">
	<link rel="stylesheet" href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css">
	<link rel="stylesheet" href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css">
	<link rel="stylesheet" href="assets/css/styles.css">
	<link rel="stylesheet" href="assets/css/plugins.css">
	<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
</head>

<body>
	<div id="app">
		<?php include('include/sidebar.php'); ?>
		<div class="app-content">
			<?php include('include/header.php'); ?>

			<div class="main-content">
				<div class="wrap-content container" id="container">

					<!-- Page Title -->
					<section id="page-title">
						<div class="row">
							<div class="col-sm-8">
								<h1 class="mainTitle">Admin | Add Doctor Specialization</h1>
							</div>
							<ol class="breadcrumb">
								<li><span>Admin</span></li>
								<li class="active"><span>Add Doctor Specialization</span></li>
							</ol>
						</div>
					</section>

					<!-- Add Specialization Form -->
					<div class="container-fluid container-fullw bg-white">
						<div class="row">
							<div class="col-lg-6 col-md-12">
								<div class="panel panel-white">
									<div class="panel-heading">
										<h5 class="panel-title">Doctor Specialization</h5>
									</div>
									<div class="panel-body">
										<?php if (!empty($_SESSION['msg'])): ?>
											<p style="color:red;"><?php echo htmlentities($_SESSION['msg']);
																						$_SESSION['msg'] = ""; ?></p>
										<?php endif; ?>
										<form role="form" name="dcotorspcl" method="post">
											<div class="form-group">
												<label>Doctor Specialization</label>
												<input type="text" name="doctorspecilization" class="form-control" placeholder="Enter Doctor Specialization" required>
											</div>
											<button type="submit" name="submit" class="btn btn-o btn-primary">Submit</button>
										</form>
									</div>
								</div>
							</div>
						</div>

						<!-- Manage Specializations Table -->
						<div class="row">
							<div class="col-md-12">
								<h5 class="over-title margin-bottom-15">Manage <span class="text-bold">Doctor Specializations</span></h5>
								<table class="table table-hover" id="sample-table-1">
									<thead>
										<tr>
											<th>#</th>
											<th>Specialization</th>
											<th class="hidden-xs">Creation Date</th>
											<th>Updation Date</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$sql = mysqli_query($con, "SELECT * FROM doctorSpecilization");
										$cnt = 1;
										while ($row = mysqli_fetch_assoc($sql)) {
										?>
											<tr>
												<td><?php echo $cnt; ?>.</td>
												<td><?php echo htmlentities($row['specilization']); ?></td>
												<td><?php echo htmlentities($row['creationDate']); ?></td>
												<td><?php echo htmlentities($row['updationDate']); ?></td>
												<td>
													<a href="edit-doctor-specialization.php?id=<?php echo $row['id']; ?>" class="btn btn-xs btn-transparent" title="Edit"><i class="fa fa-pencil"></i></a>
													<a href="doctor-specilization.php?id=<?php echo $row['id']; ?>&del=delete" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-xs btn-transparent" title="Delete"><i class="fa fa-times"></i></a>
												</td>
											</tr>
										<?php $cnt++;
										} ?>
									</tbody>
								</table>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<?php include('include/footer.php'); ?>
		<?php include('include/setting.php'); ?>
	</div>

	<!-- Scripts -->
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/modernizr/modernizr.js"></script>
	<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
	<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="vendor/switchery/switchery.min.js"></script>
	<script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
	<script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
	<script src="vendor/autosize/autosize.min.js"></script>
	<script src="vendor/selectFx/classie.js"></script>
	<script src="vendor/selectFx/selectFx.js"></script>
	<script src="vendor/select2/select2.min.js"></script>
	<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
	<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
	<script src="assets/js/main.js"></script>
	<script src="assets/js/form-elements.js"></script>
	<script>
		jQuery(document).ready(function() {
			Main.init();
			FormElements.init();
		});
	</script>
</body>

</html>