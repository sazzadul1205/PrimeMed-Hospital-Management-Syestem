<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

$user_id = $_SESSION['id'];

// Fetch paid payment requests for this user
$query = "SELECT pr.*, d.doctorName, d.docFees
          FROM payment_requests pr
          LEFT JOIN doctors d ON pr.doctor_id = d.id
          WHERE pr.patient_id = ? AND pr.status = 'paid'
          ORDER BY pr.updated_at DESC";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$payment_history = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>User | Payment History</title>

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
		.history-card {
			border: 1px solid #e0e0e0;
			border-radius: 10px;
			padding: 20px;
			margin-bottom: 20px;
			background: #fff;
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
		}
		.history-header {
			border-bottom: 1px solid #eee;
			padding-bottom: 15px;
			margin-bottom: 15px;
		}
		.amount {
			font-size: 24px;
			font-weight: bold;
			color: #28a745;
		}
		.status-paid {
			color: #28a745;
			font-weight: bold;
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
								<h1 class="mainTitle">Payment History</h1>
							</div>
							<ol class="breadcrumb">
								<li><span>User</span></li>
								<li class="active"><span>Payment History</span></li>
							</ol>
						</div>
					</section>

					<div class="container-fluid container-fullw bg-white">
						<div class="row">
							<div class="col-md-12">
								<?php if ($payment_history->num_rows > 0): ?>
									<?php while ($request = $payment_history->fetch_assoc()): ?>
										<div class="history-card">
											<div class="history-header">
												<h4><?php echo htmlspecialchars($request['description']); ?></h4>
												<p><strong>Doctor:</strong> <?php echo htmlspecialchars($request['doctorName']); ?></p>
												<p><strong>Payment Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($request['updated_at'])); ?></p>
											</div>

											<div class="row">
												<div class="col-md-6">
													<p><strong>Amount:</strong> <span class="amount">৳<?php echo number_format($request['amount'], 2); ?></span></p>
													<p><strong>Status:</strong> <span class="status-paid">Paid</span></p>
												</div>
												<div class="col-md-6 text-right">
													<p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($request['id']); ?></p>
													<p><strong>Payment Method:</strong> <?php echo htmlspecialchars($request['payment_method'] ?? 'N/A'); ?></p>
												</div>
											</div>
										</div>
									<?php endwhile; ?>
								<?php else: ?>
									<div class="text-center py-5">
										<i class="fa fa-history fa-3x text-muted mb-3"></i>
										<h4>No Payment History</h4>
										<p>You have no completed payments at this time.</p>
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
</body>

</html>
