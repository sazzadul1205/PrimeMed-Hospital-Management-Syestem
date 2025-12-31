<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

// Get payment request details
if (!isset($_GET['request_id']) || !isset($_GET['method'])) {
    header('Location: pay-bill.php');
    exit;
}

$request_id = intval($_GET['request_id']);
$method = $_GET['method'];

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

// Payment gateway SDKs will be loaded conditionally if available

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment Gateway | <?php echo ucfirst($method); ?></title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <style>
        .payment-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .payment-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .amount-display {
            font-size: 32px;
            font-weight: bold;
            color: #28a745;
            text-align: center;
            margin: 20px 0;
        }
        .payment-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-container">
            <div class="payment-header">
                <h2><?php echo ucfirst($method); ?> Payment</h2>
                <p>Complete your payment securely</p>
            </div>

            <div class="payment-details">
                <h4>Payment Details</h4>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($payment_request['description']); ?></p>
                <p><strong>Doctor:</strong> <?php echo htmlspecialchars($payment_request['doctorName']); ?></p>
                <p><strong>Patient:</strong> <?php echo htmlspecialchars($payment_request['fullName']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($payment_request['email']); ?></p>
            </div>

            <div class="amount-display">
                ৳<?php echo number_format($payment_request['amount'], 2); ?>
            </div>

            <div id="payment-form">
                <?php if ($method === 'bkash'): ?>
                    <button id="bkash-pay-btn" class="btn btn-danger btn-lg btn-block">
                        <i class="fa fa-mobile"></i> Pay with bKash
                    </button>
                <?php elseif ($method === 'nagad'): ?>
                    <button id="nagad-pay-btn" class="btn btn-warning btn-lg btn-block">
                        <i class="fa fa-mobile"></i> Pay with Nagad
                    </button>
                <?php elseif ($method === 'rocket'): ?>
                    <button id="rocket-pay-btn" class="btn btn-info btn-lg btn-block">
                        <i class="fa fa-mobile"></i> Pay with Rocket
                    </button>
                <?php elseif ($method === 'card'): ?>
                    <form id="card-payment-form">
                        <div class="form-group">
                            <label>Card Number</label>
                            <input type="text" class="form-control" placeholder="1234 5678 9012 3456" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Expiry Date</label>
                                <input type="text" class="form-control" placeholder="MM/YY" required>
                            </div>
                            <div class="col-md-6">
                                <label>CVV</label>
                                <input type="text" class="form-control" placeholder="123" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg btn-block mt-3">
                            <i class="fa fa-credit-card"></i> Pay Now
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <div class="text-center mt-3">
                <a href="pay-bill.php" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script>
        // bKash Payment Integration
        <?php if ($method === 'bkash'): ?>
        $(document).ready(function() {
            $('#bkash-pay-btn').click(function() {
                const btn = $(this);
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

                // Initialize bKash payment
                $.ajax({
                    url: 'api/bkash/create-payment.php',
                    method: 'POST',
                    data: {
                        request_id: <?php echo $request_id; ?>,
                        amount: <?php echo $payment_request['amount']; ?>
                    },
                    success: function(response) {
                        if (response.success) {
                            // Redirect to bKash checkout
                            window.location.href = response.checkout_url;
                        } else {
                            alert('Payment initialization failed: ' + response.message);
                            btn.prop('disabled', false).html('<i class="fa fa-mobile"></i> Pay with bKash');
                        }
                    },
                    error: function() {
                        alert('Network error. Please try again.');
                        btn.prop('disabled', false).html('<i class="fa fa-mobile"></i> Pay with bKash');
                    }
                });
            });
        });
        <?php endif; ?>

        // Nagad Payment Integration
        <?php if ($method === 'nagad'): ?>
        $(document).ready(function() {
            $('#nagad-pay-btn').click(function() {
                const btn = $(this);
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

                $.ajax({
                    url: 'api/nagad/create-payment.php',
                    method: 'POST',
                    data: {
                        request_id: <?php echo $request_id; ?>,
                        amount: <?php echo $payment_request['amount']; ?>
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = response.checkout_url;
                        } else {
                            alert('Payment initialization failed: ' + response.message);
                            btn.prop('disabled', false).html('<i class="fa fa-mobile"></i> Pay with Nagad');
                        }
                    },
                    error: function() {
                        alert('Network error. Please try again.');
                        btn.prop('disabled', false).html('<i class="fa fa-mobile"></i> Pay with Nagad');
                    }
                });
            });
        });
        <?php endif; ?>

        // Rocket Payment Integration
        <?php if ($method === 'rocket'): ?>
        $(document).ready(function() {
            $('#rocket-pay-btn').click(function() {
                const btn = $(this);
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

                $.ajax({
                    url: 'api/rocket/create-payment.php',
                    method: 'POST',
                    data: {
                        request_id: <?php echo $request_id; ?>,
                        amount: <?php echo $payment_request['amount']; ?>
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = response.checkout_url;
                        } else {
                            alert('Payment initialization failed: ' + response.message);
                            btn.prop('disabled', false).html('<i class="fa fa-mobile"></i> Pay with Rocket');
                        }
                    },
                    error: function() {
                        alert('Network error. Please try again.');
                        btn.prop('disabled', false).html('<i class="fa fa-mobile"></i> Pay with Rocket');
                    }
                });
            });
        });
        <?php endif; ?>

        // Card Payment
        <?php if ($method === 'card'): ?>
        $('#card-payment-form').submit(function(e) {
            e.preventDefault();
            const btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

            // Simulate card payment processing
            setTimeout(function() {
                alert('Card payment processed successfully!');
                window.location.href = 'payment-success.php?request_id=<?php echo $request_id; ?>';
            }, 3000);
        });
        <?php endif; ?>
    </script>
</body>
</html>
