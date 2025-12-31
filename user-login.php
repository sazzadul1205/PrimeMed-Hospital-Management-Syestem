<?php
session_start();
include 'include/config.php';
error_reporting(E_ALL);

$_SESSION['errmsg'] = $_SESSION['errmsg'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$login_type = $_POST['login_type'] ?? '';
	$username   = trim($_POST['username'] ?? '');
	$password   = trim($_POST['password'] ?? '');
	$userip     = $_SERVER['REMOTE_ADDR'];

	if ($login_type === '' || $username === '' || $password === '') {
		$_SESSION['errmsg'] = "All fields are required";
		header("Location: login.php");
		exit();
	}

	$password_hashed = md5($password);
	$status = 0;

	if ($login_type === 'admin') {
		$stmt = $con->prepare("SELECT id, username FROM admin WHERE username = ? AND password = ? LIMIT 1");
		if ($stmt) {
			$stmt->bind_param("ss", $username, $password_hashed);
			$stmt->execute();
			$result = $stmt->get_result();
			if ($admin = $result->fetch_assoc()) {
				session_regenerate_id(true);
				$_SESSION['login'] = $admin['username'];
				$_SESSION['id']    = $admin['id'];
				header("Location: admin/index.php");
				exit();
			}
		}
		$_SESSION['errmsg'] = "Invalid admin credentials";
	} elseif ($login_type === 'doctor') {
		$stmt = $con->prepare("SELECT id FROM doctors WHERE docEmail = ? AND password = ?");
		if ($stmt) {
			$stmt->bind_param("ss", $username, $password_hashed);
			$stmt->execute();
			$result = $stmt->get_result();
			if ($row = $result->fetch_assoc()) {
				session_regenerate_id(true);
				$_SESSION['dlogin'] = $username;
				$_SESSION['id'] = $row['id'];
				$status = 1;

				$logStmt = $con->prepare("INSERT INTO doctorslog (uid, username, userip, status) VALUES (?, ?, ?, ?)");
				$logStmt->bind_param("issi", $row['id'], $username, $userip, $status);
				$logStmt->execute();
				$logStmt->close();

				header("Location: doctor/index.php");
				exit();
			}
		}
		$_SESSION['errmsg'] = "Invalid doctor credentials";
	} elseif ($login_type === 'user') {
		$stmt = $con->prepare("SELECT id, email FROM users WHERE email = ? AND password = ? LIMIT 1");
		if ($stmt) {
			$stmt->bind_param("ss", $username, $password_hashed);
			$stmt->execute();
			$result = $stmt->get_result();
			if ($user = $result->fetch_assoc()) {
				session_regenerate_id(true);
				$_SESSION['login'] = $user['email'];
				$_SESSION['id']    = $user['id'];
				$status = 1;

				$logStmt = $con->prepare("INSERT INTO userlog (uid, username, userip, status) VALUES (?, ?, ?, ?)");
				$logStmt->bind_param("issi", $user['id'], $username, $userip, $status);
				$logStmt->execute();
				$logStmt->close();

				header("Location: user/dashboard.php");
				exit();
			}
		}
		$_SESSION['errmsg'] = "Invalid user credentials";
	} else {
		$_SESSION['errmsg'] = "Invalid login type";
	}

	header("Location: login.php");
	exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login | Advanced Healthcare Management System</title>
	<link href="https://fonts.googleapis.com/css2?family=Mulish:wght@400;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">

	<style>
		body {
			font-family: 'Mulish', sans-serif;
			background: linear-gradient(135deg, #4e73df 0%, #1cc88a 100%);
			height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.main-login {
			display: flex;
			background: #fff;
			border-radius: 12px;
			box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
			overflow: hidden;
			width: 90%;
			max-width: 700px;
		}

		.login-side {
			width: 150px;
			background: #4e73df;
			color: #fff;
			display: flex;
			flex-direction: column;
			align-items: center;
			padding: 30px 10px;
		}

		.login-side h3 {
			margin-bottom: 20px;
			font-size: 1.1rem;
			text-align: center;
		}

		.login-side .type-box {
			display: flex;
			align-items: center;
			margin-bottom: 20px;
			cursor: pointer;
			padding: 10px 8px;
			width: 100%;
			border-radius: 8px;
			transition: all 0.3s;
			text-align: left;
		}

		.login-side .type-box i {
			margin-right: 10px;
			font-size: 1.3rem;
		}

		.login-side .type-box.selected {
			background: #fff;
			color: #4e73df;
			font-weight: 600;
		}

		.login-form {
			flex: 1;
			padding: 40px 30px;
		}

		.logo h2 {
			font-weight: 700;
			color: #333;
			margin-bottom: 25px;
			text-align: center;
		}

		.form-group {
			margin-bottom: 20px;
		}

		.form-control {
			border-radius: 8px;
			padding: 12px 15px;
			border: 1px solid #ddd;
			width: 100%;
			transition: all 0.3s ease;
		}

		.form-control:focus {
			border-color: #4e73df;
			box-shadow: 0 0 8px rgba(78, 115, 223, 0.3);
		}

		.btn-primary {
			background-color: #4e73df;
			border-color: #4e73df;
			border-radius: 8px;
			padding: 10px 20px;
			font-weight: 600;
			width: 100%;
			transition: all 0.3s ease;
		}

		.btn-primary:hover {
			background-color: #2e59d9;
			border-color: #2e59d9;
		}

		.text-danger {
			font-size: 0.9rem;
			margin-bottom: 15px;
		}

		.new-account {
			margin-top: 15px;
			font-size: 1.5rem;
			text-align: center;
		}

		.new-account a {
			color: #4e73df;
			font-weight: 600;
			text-decoration: none;
		}

		.new-account a:hover {
			text-decoration: underline;
		}

		@media (max-width: 768px) {
			.main-login {
				flex-direction: column;
			}

			.login-side {
				width: 100%;
				flex-direction: row;
				justify-content: space-around;
				padding: 15px 0;
			}

			.login-form {
				padding: 30px 20px;
			}
		}
	</style>
</head>

<body>
	<div class="main-login">
		<!-- Side Panel -->
		<div class="login-side">
			<h3>Login Type</h3>
			<div class="type-box" data-value="admin"><i class="fa fa-user-shield"></i> Admin</div>
			<div class="type-box selected" data-value="user"><i class="fa fa-user"></i> User</div>
			<div class="type-box" data-value="doctor"><i class="fa fa-user-md"></i> Doctor</div>
		</div>

		<!-- Form -->
		<div class="login-form">
			<div class="logo">
				<h2>HMS | Login</h2>
			</div>

			<?php if (!empty($_SESSION['errmsg'])): ?>
				<div class="text-danger"><?php echo $_SESSION['errmsg'];
																	$_SESSION['errmsg'] = ""; ?></div>
			<?php endif; ?>

			<form method="post">
				<input type="hidden" name="login_type" id="login_type" value="user">
				<div class="form-group">
					<input type="text" name="username" class="form-control" placeholder="Username / Email" required>
				</div>
				<div class="form-group">
					<input type="password" name="password" class="form-control" placeholder="Password" required>
				</div>
				<button type="submit" class="btn btn-primary">Login <i class="fa fa-arrow-circle-right"></i></button>
				<div class="new-account mt-3">
					Don't have an account? <a href="registration.php">Create one</a>
				</div>
			</form>
		</div>
	</div>

	<script>
		const typeBoxes = document.querySelectorAll('.type-box');
		const loginTypeInput = document.getElementById('login_type');

		typeBoxes.forEach(box => {
			box.addEventListener('click', () => {
				typeBoxes.forEach(b => b.classList.remove('selected'));
				box.classList.add('selected');
				loginTypeInput.value = box.getAttribute('data-value');
			});
		});
	</script>
</body>

</html>