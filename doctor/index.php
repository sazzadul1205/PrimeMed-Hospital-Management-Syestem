<?php
session_start();
include("include/config.php");
error_reporting(E_ALL);

$_SESSION['errmsg'] = $_SESSION['errmsg'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$uname = trim($_POST['username'] ?? '');
	$password = trim($_POST['password'] ?? '');

	if ($uname === '' || $password === '') {
		$_SESSION['errmsg'] = "Email and password required";
		return;
	}

	// KEEP MD5 (as requested)
	$password = md5($password);

	// Secure query using prepared statement
	$stmt = $con->prepare(
		"SELECT id FROM doctors WHERE docEmail = ? AND password = ?"
	);

	if (!$stmt) {
		$_SESSION['errmsg'] = "Database error";
		return;
	}

	$stmt->bind_param("ss", $uname, $password);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($row = $result->fetch_assoc()) {

		$_SESSION['dlogin'] = $uname;
		$_SESSION['id'] = $row['id'];

		$uid = $row['id'];
		$uip = $_SERVER['REMOTE_ADDR'];
		$status = 1;

		$logStmt = $con->prepare(
			"INSERT INTO doctorslog (uid, username, userip, status)
             VALUES (?, ?, ?, ?)"
		);
		$logStmt->bind_param("issi", $uid, $uname, $uip, $status);
		$logStmt->execute();

		header("Location: dashboard.php");
		exit();
	} else {

		$uip = $_SERVER['REMOTE_ADDR'];
		$status = 0;

		$logStmt = $con->prepare(
			"INSERT INTO doctorslog (username, userip, status)
             VALUES (?, ?, ?)"
		);
		$logStmt->bind_param("ssi", $uname, $uip, $status);
		$logStmt->execute();

		$_SESSION['errmsg'] = "Invalid email or password";
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Doctor Login</title>

	<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
	<link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
	<link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
	<link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" href="assets/css/styles.css">
	<link rel="stylesheet" href="assets/css/plugins.css">
	<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
</head>

<body class="login">
	<div class="row">
		<div class="main-login col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
			<div class="logo margin-top-30">
				<a href="../../index.php">
					<h2> HMS | Doctor Login</h2>
				</a>
			</div>

			<div class="box-login">
				<form class="form-login" method="post">
					<fieldset>
						<legend>
							Sign in to your account
						</legend>
						<p>
							Please enter your name and password to log in.<br />
							<span style="color:red;"><?php echo $_SESSION['errmsg']; ?><?php echo $_SESSION['errmsg'] = ""; ?></span>
						</p>
						<div class="form-group">
							<span class="input-icon">
								<input type="text" class="form-control" name="username" placeholder="Email" required>
								<i class="fa fa-user"></i> </span>
						</div>
						<div class="form-group form-actions">
							<span class="input-icon">
								<input type="password" class="form-control password" name="password" placeholder="Password" required>
								<i class="fa fa-lock"></i>
							</span>
							<a href="forgot-password.php">
								Forgot Password ?
							</a>
						</div>
						<div class="form-actions">

							<button type="submit" class="btn btn-primary pull-right" name="submit">
								Login <i class="fa fa-arrow-circle-right"></i>
							</button>
						</div>


					</fieldset>
				</form>

				<div class="copyright">
					<span class="text-bold text-uppercase"> Hospital Management System</span>
				</div>

			</div>

		</div>
	</div>
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/modernizr/modernizr.js"></script>
	<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
	<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="vendor/switchery/switchery.min.js"></script>
	<script src="vendor/jquery-validation/jquery.validate.min.js"></script>

	<script src="assets/js/main.js"></script>

	<script src="assets/js/login.js"></script>
	<script>
		jQuery(document).ready(function() {
			Main.init();
			Login.init();
		});
	</script>

</body>
<!-- end: BODY -->

</html>