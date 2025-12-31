<?php
session_start();
include("include/config.php");
error_reporting(E_ALL);

// If already logged in, redirect to dashboard
if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {
	header("Location: dashboard.php");
	exit();
}

$_SESSION['errmsg'] = $_SESSION['errmsg'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$username = trim($_POST['username'] ?? '');
	$password = trim($_POST['password'] ?? '');

	if ($username === '' || $password === '') {
		$_SESSION['errmsg'] = "Username and password required";
		return;
	}

	// KEEP MD5
	$password = md5($password);

	$stmt = $con->prepare(
		"SELECT id, username FROM admin WHERE username = ? AND password = ? LIMIT 1"
	);

	if (!$stmt) {
		$_SESSION['errmsg'] = "Database error";
		return;
	}

	$stmt->bind_param("ss", $username, $password);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($admin = $result->fetch_assoc()) {

		session_regenerate_id(true); // security hardening

		$_SESSION['login'] = $admin['username'];
		$_SESSION['id'] = $admin['id'];

		header("Location: dashboard.php");
		exit();
	} else {
		$_SESSION['errmsg'] = "Invalid username or password";
	}

	$stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Admin Login | Hospital Management System</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Fonts & CSS -->
	<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
	<link href="vendor/animate.css/animate.min.css" rel="stylesheet">
	<link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet">
	<link href="vendor/switchery/switchery.min.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/styles.css">
	<link rel="stylesheet" href="assets/css/plugins.css">
	<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
</head>

<body class="login">
	<div class="row">
		<div class="main-login col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
			<!-- Logo -->
			<div class="logo margin-top-30 text-center">
				<h2>Admin Login</h2>
			</div>

			<!-- Login Box -->
			<div class="box-login">
				<form class="form-login" method="post" autocomplete="on">
					<fieldset>
						<legend>Sign in to your account</legend>
						<?php if (!empty($_SESSION['errmsg'])): ?>
							<p class="text-danger"><?php echo htmlentities($_SESSION['errmsg']);
																			$_SESSION['errmsg'] = ""; ?></p>
						<?php endif; ?>

						<!-- Username -->
						<div class="form-group">
							<span class="input-icon">
								<input type="text" name="username" class="form-control" placeholder="Username" required aria-label="Username">
								<i class="fa fa-user"></i>
							</span>
						</div>

						<!-- Password -->
						<div class="form-group form-actions">
							<span class="input-icon">
								<input type="password" name="password" class="form-control" placeholder="Password" required aria-label="Password">
								<i class="fa fa-lock"></i>
							</span>
						</div>

						<!-- Submit Button -->
						<div class="form-actions">
							<button type="submit" name="submit" class="btn btn-primary pull-right">
								Login <i class="fa fa-arrow-circle-right"></i>
							</button>
						</div>

						<!-- Home Link -->
						<div class="mt-2">
							<a href="../index.php">Back to Home Page</a>
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

</html>