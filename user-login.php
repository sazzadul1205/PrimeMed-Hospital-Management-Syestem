<?php
session_start();
include 'include/config.php';
error_reporting(E_ALL);

$_SESSION['errmsg'] = $_SESSION['errmsg'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$username = trim($_POST['username'] ?? '');
	$password = trim($_POST['password'] ?? '');
	$userip   = $_SERVER['REMOTE_ADDR'];

	if ($username === '' || $password === '') {
		$_SESSION['errmsg'] = "Email and password required";
		header("Location: user-login.php");
		exit();
	}

	// KEEP MD5
	$password = md5($password);
	$status   = 0;

	// Secure login query
	$stmt = $con->prepare(
		"SELECT id, email FROM users WHERE email = ? AND password = ? LIMIT 1"
	);

	if (!$stmt) {
		$_SESSION['errmsg'] = "Database error";
		header("Location: user-login.php");
		exit();
	}

	$stmt->bind_param("ss", $username, $password);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($user = $result->fetch_assoc()) {

		session_regenerate_id(true);

		$_SESSION['login'] = $user['email'];
		$_SESSION['id']    = $user['id'];
		$status = 1;

		// Log success
		$logStmt = $con->prepare(
			"INSERT INTO userlog (uid, username, userip, status)
             VALUES (?, ?, ?, ?)"
		);
		$logStmt->bind_param("issi", $user['id'], $username, $userip, $status);
		$logStmt->execute();
		$logStmt->close();

		header("Location: dashboard.php");
		exit();
	} else {

		// Log failure
		$logStmt = $con->prepare(
			"INSERT INTO userlog (username, userip, status)
             VALUES (?, ?, ?)"
		);
		$logStmt->bind_param("ssi", $username, $userip, $status);
		$logStmt->execute();
		$logStmt->close();

		$_SESSION['errmsg'] = "Invalid email or password";
		header("Location: user-login.php");
		exit();
	}

	$stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User Login | Advanced Healthcare Management System</title>

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
			<!-- Logo -->
			<div class="logo margin-top-30 text-center">
				<a href="../index.php">
					<h2>HMS | Patient Login</h2>
				</a>
			</div>

			<!-- Login Box -->
			<div class="box-login">
				<form class="form-login" method="post" autocomplete="on">
					<fieldset>
						<legend>Sign in to your account</legend>
						<p>
							Please enter your email and password to log in.<br>
							<?php if (!empty($_SESSION['errmsg'])): ?>
								<span class="text-danger"><?php echo $_SESSION['errmsg'];
																					$_SESSION['errmsg'] = ""; ?></span>
							<?php endif; ?>
						</p>

						<!-- Email -->
						<div class="form-group">
							<span class="input-icon">
								<input type="email" name="username" class="form-control" placeholder="Email" required aria-label="Email">
								<i class="fa fa-user"></i>
							</span>
						</div>

						<!-- Password -->
						<div class="form-group form-actions">
							<span class="input-icon">
								<input type="password" name="password" class="form-control" placeholder="Password" required aria-label="Password">
								<i class="fa fa-lock"></i>
							</span>
							<a href="forgot-password.php" class="forgot-link">Forgot Password?</a>
						</div>

						<!-- Submit Button -->
						<div class="form-actions">
							<button type="submit" name="submit" class="btn btn-primary pull-right">
								Login <i class="fa fa-arrow-circle-right"></i>
							</button>
						</div>

						<!-- Registration Link -->
						<div class="new-account text-center mt-3">
							Don't have an account yet?
							<a href="registration.php">Create an account</a>
						</div>
					</fieldset>
				</form>

				<!-- Footer -->
				<div class="copyright text-center mt-4">
					<span class="text-bold text-uppercase">Hospital Management System</span>
				</div>
			</div>
		</div>
	</div>

	<!-- Scripts -->
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/modernizr/modernizr.js"></script>
	<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
	<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="vendor/switchery/switchery.min.js"></script>
	<!-- <script src="vendor/jquery-validation/jquery.validate.min.js"></script> -->

	<script src="assets/js/main.js"></script>
	<script src="assets/js/login.js"></script>
	<script>
		jQuery(document).ready(function() {
			Main.init();
			Login.init();
		});
	</script>
</body>



</html>