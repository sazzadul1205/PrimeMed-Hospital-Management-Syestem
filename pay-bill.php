<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

$user_id = $_SESSION['id'];

// Fetch pending payment requests for this user
$query = "SELECT pr.*, d.doctorName, d.docFees
          FROM payment_requests pr
          LEFT JOIN doctors d ON pr.doctor_id = d.id
          WHERE pr.patient_id = ? AND pr.status = 'pending'
          ORDER BY pr.created_at DESC";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$payment_requests = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>User | Pay Bill</title>

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

	<style>
		.payment-card {
			border: 1px solid #e0e0e0;
			border-radius: 10px;
			padding: 20px;
			margin-bottom: 20px;
			background: #fff;
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
		}
		.payment-header {
			border-bottom: 1px solid #eee;
			padding-bottom: 15px;
			margin-bottom: 15px;
		}
		.amount {
			font-size: 24px;
			font-weight: bold;
			color: #28a745;
		}
		.payment-method {
			margin-top: 20px;
		}
		.bkash-btn {
			background: linear-gradient(135deg, #e2136e, #f15a29);
			color: white;
			border: none;
			padding: 12px 30px;
			border-radius: 5px;
			font-size: 16px;
			cursor: pointer;
			transition: all 0.3s ease;
		}
		.bkash-btn:hover {
			transform: translateY(-2px);
			box-shadow: 0 5px 15px rgba(226, 19, 110, 0.4);
		}
		.other-methods {
			margin-top: 15px;
		}
		.method-option {
			display: inline-block;
			margin-right: 10px;
			padding: 8px 15px;
			border: 1px solid #ddd;
			border-radius: 5px;
			cursor: pointer;
			transition: all 0.3s ease;
		}
		.method-option:hover {
			background: #f8f9fa;
			border-color: #007bff;
		}
	</style>
</head>

<body>
	<div id="app">
		<?php include('include/sidebar.php'); ?>
		<div class="app-content">

			<?php include('include/header.php'); ?>

			<div class="main-content">
				<div class="wrap-content container" id="container">
					<section id="page-title">
						<div class="row">
							<div class="col-sm-8">
								<h1 class="mainTitle">Pay Bill</h1>
							</div>
							<ol class="breadcrumb">
								<li><span>User</span></li>
								<li class="active"><span>Pay Bill</span></li>
							</ol>
						</div>
					</section>

					<div class="container-fluid container-fullw bg-white">
						<div class="row">
							<div class="col-md-12">
								<?php if ($payment_requests->num_rows > 0): ?>
									<?php while ($request = $payment_requests->fetch_assoc()): ?>
										<div class="payment-card">
											<div class="payment-header">
												<h4><?php echo htmlspecialchars($request['description']); ?></h4>
												<p><strong>Doctor:</strong> <?php echo htmlspecialchars($request['doctorName']); ?></p>
												<p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($request['created_at'])); ?></p>
											</div>

											<div class="row">
												<div class="col-md-6">
													<p><strong>Amount:</strong> <span class="amount">৳<?php echo number_format($request['amount'], 2); ?></span></p>
													<p><strong>Status:</strong> <span class="badge badge-warning">Pending</span></p>
												</div>
												<div class="col-md-6 text-right">
													<div class="payment-method">
														<h5>Choose Payment Method</h5>
														<button class="bkash-btn" onclick="initiatePayment(<?php echo $request['id']; ?>, 'bkash')">
															<i class="fa fa-mobile"></i> Pay with bKash
														</button>
														<div class="other-methods">
															<span class="method-option" onclick="initiatePayment(<?php echo $request['id']; ?>, 'nagad')">
																<i class="fa fa-mobile"></i> Nagad
															</span>
															<span class="method-option" onclick="initiatePayment(<?php echo $request['id']; ?>, 'rocket')">
																<i class="fa fa-mobile"></i> Rocket
															</span>
															<span class="method-option" onclick="initiatePayment(<?php echo $request['id']; ?>, 'card')">
																<i class="fa fa-credit-card"></i> Card
															</span>
														</div>
													</div>
												</div>
											</div>
										</div>
									<?php endwhile; ?>
								<?php else: ?>
									<div class="text-center py-5">
										<i class="fa fa-check-circle fa-3x text-success mb-3"></i>
										<h4>No Pending Bills</h4>
										<p>You have no outstanding payments at this time.</p>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php include('include/footer.php'); ?>
		<?php include('include/setting.php'); ?>
	</div>

	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/modernizr/modernizr.js"></script>
	<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
	<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="vendor/switchery/switchery.min.js"></script>
	<script src="assets/js/main.js"></script>

	<script>
		function initiatePayment(requestId, method) {
			// Redirect to payment gateway
			window.location.href = 'payment-gateway.php?request_id=' + requestId + '&method=' + method;
		}
	</script>
</body>

</html>
