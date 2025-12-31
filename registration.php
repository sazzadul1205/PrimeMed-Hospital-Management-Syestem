<?php
include_once('include/config.php');
session_start();

$errors = [];

if (isset($_POST['submit'])) {

	// Collect and sanitize
	$fname   = trim($_POST['full_name']);
	$address = trim($_POST['address']);
	$city    = trim($_POST['city']);
	$gender  = $_POST['gender'] ?? '';
	$email   = trim($_POST['email']);
	$password = $_POST['password'];
	$password_again = $_POST['password_again'];

	// Server-side validations
	if (empty($fname) || empty($address) || empty($city) || empty($gender) || empty($email) || empty($password)) {
		$errors[] = "All fields are required.";
	}

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors[] = "Invalid email format.";
	}

	if (strlen($password) < 6) {
		$errors[] = "Password must be at least 6 characters long.";
	}

	if ($password !== $password_again) {
		$errors[] = "Passwords do not match.";
	}

	// Check if email already exists
	$stmt = $con->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		$errors[] = "Email already registered.";
	}
	$stmt->close();

	// If no errors, insert user
	if (empty($errors)) {
		$password_hashed = md5($password);
		$stmt = $con->prepare("INSERT INTO users(fullname,address,city,gender,email,password) VALUES(?,?,?,?,?,?)");
		$stmt->bind_param("ssssss", $fname, $address, $city, $gender, $email, $password_hashed);
		if ($stmt->execute()) {
			echo "<script>alert('Registration successful! You can login now');</script>";
			// header('Location: user-login.php');
		} else {
			$errors[] = "Database error: failed to register.";
		}
		$stmt->close();
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User Registration | HMS</title>
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

		.main-register {
			background: #fff;
			border-radius: 12px;
			box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
			width: 100%;
			max-width: 450px;
			padding: 40px 30px;
			text-align: center;
		}

		.logo h2 {
			font-weight: 700;
			color: #333;
			margin-bottom: 25px;
		}

		.form-group {
			margin-bottom: 20px;
			text-align: left;
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
			font-size: 0.95rem;
			margin-bottom: 15px;
		}

		.new-account {
			margin-top: 15px;
			font-size: 1rem;
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
	</style>

	<script>
		function checkPasswords() {
			let pass = document.getElementById('password').value;
			let pass2 = document.getElementById('password_again').value;
			if (pass !== pass2) {
				alert("Passwords do not match!");
				return false;
			}
			return true;
		}

		function userAvailability() {
			$("#loaderIcon").show();
			jQuery.ajax({
				url: "check_availability.php",
				data: 'email=' + $("#email").val(),
				type: "POST",
				success: function(data) {
					$("#user-availability-status1").html(data);
					$("#loaderIcon").hide();
				},
				error: function() {}
			});
		}
	</script>
</head>

<body>
	<div class="main-register position-relative">
		<!-- Floating Back Button -->
		<a href="user-login.php"
			style="position:absolute; top:15px; left:15px; font-size:1.2rem; text-decoration:none; color:#4e73df;">
			<i class="fa fa-arrow-left"></i> Back
		</a>

		<div class="logo">
			<h2>HMS | Patient Registration</h2>
		</div>

		<?php if (!empty($errors)): ?>
			<div class="text-danger">
				<?php foreach ($errors as $err) echo $err . "<br>"; ?>
			</div>
		<?php endif; ?>

		<form name="registration" method="post" onsubmit="return checkPasswords();">
			<fieldset>
				<p>Enter your personal details:</p>
				<div class="form-group">
					<input type="text" class="form-control" name="full_name" placeholder="Full Name" required>
				</div>
				<div class="form-group">
					<input type="text" class="form-control" name="address" placeholder="Address" required>
				</div>
				<div class="form-group">
					<input type="text" class="form-control" name="city" placeholder="City" required>
				</div>
				<div class="form-group">
					<label>Gender</label><br>
					<input type="radio" id="female" name="gender" value="female" required>
					<label for="female">Female</label>
					<input type="radio" id="male" name="gender" value="male">
					<label for="male">Male</label>
				</div>

				<p>Enter your account details:</p>
				<div class="form-group">
					<input type="email" class="form-control" name="email" id="email" onBlur="userAvailability()" placeholder="Email" required>
					<span id="user-availability-status1" style="font-size:12px;"></span>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" id="password_again" name="password_again" placeholder="Confirm Password" required>
				</div>

				<div class="form-group">
					<div class="checkbox">
						<input type="checkbox" id="agree" checked readonly>
						<label for="agree">I agree to the terms</label>
					</div>
				</div>

				<button type="submit" class="btn btn-primary" name="submit">
					Register <i class="fa fa-arrow-circle-right"></i>
				</button>

				<div class="new-account mt-3">
					Already have an account? <a href="user-login.php">Log-in</a>
				</div>
			</fieldset>
		</form>

		<div class="copyright mt-3">
			&copy; <span class="current-year"></span> <span class="text-bold text-uppercase">HMS</span>. All rights reserved.
		</div>
	</div>
</body>


</html>