<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

// Check if request_id is provided
if (!isset($_GET['request_id'])) {
    header('Location: pay-bill.php');
    exit;
}

$request_id = intval($_GET['request_id']);
$test_mode = isset($_GET['test_mode']);

// Fetch payment request details
$query = "SELECT pr.*, u.fullName, u.email, d.doctorName
          FROM payment_requests pr
          JOIN users u ON pr.patient_id = u.id
          LEFT JOIN doctors d ON pr.doctor_id = d.id
          WHERE pr.id = ? AND pr.patient_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("ii", $request_id, $_SESSION['id']);
$stmt->execute();
$payment_request = $stmt->get_result()->fetch_assoc();

if (!$payment_request) {
    header('Location: pay-bill.php');
    exit;
}

// Update payment status if in test mode
if ($test_mode && $payment_request['status'] == 'pending') {
    $transaction_id = 'TEST_' . time() . '_' . $request_id;
    $update_query = "UPDATE payment_requests SET
                     status = 'completed',
                     transaction_id = ?,
                     payment_date = NOW(),
                     updated_at = NOW()
                     WHERE id = ?";
    $update_stmt = $con->prepare($update_query);
    $update_stmt->bind_param("si", $transaction_id, $request_id);
    $update_stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment Successful | PrimeMed Hospital</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <style>
        .success-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-icon {
            font-size: 64px;
            color: #28a745;
            margin-bottom: 20px;
        }
        .success-title {
            color: #28a745;
            margin-bottom: 20px;
        }
        .payment-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-container">
            <div class="success-icon">
                <i class="fa fa-check-circle"></i>
            </div>
            <h2 class="success-title">Payment Successful!</h2>
            <p>Thank you for your payment. Your transaction has been completed successfully.</p>

            <div class="payment-details">
                <h4>Payment Details</h4>
                <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($payment_request['transaction_id'] ?? 'Processing...'); ?></p>
                <p><strong>Amount:</strong> ৳<?php echo number_format($payment_request['amount'], 2); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($payment_request['description']); ?></p>
                <p><strong>Doctor:</strong> <?php echo htmlspecialchars($payment_request['doctorName']); ?></p>
                <p><strong>Patient:</strong> <?php echo htmlspecialchars($payment_request['fullName']); ?></p>
                <p><strong>Date:</strong> <?php echo date('F j, Y, g:i a'); ?></p>
            </div>

            <div class="mt-4">
                <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
                <a href="payment-history.php" class="btn btn-secondary">View Payment History</a>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script>
        // Auto redirect after 5 seconds
        setTimeout(function() {
            window.location.href = 'dashboard.php';
        }, 5000);
    </script>
</body>
</html>
