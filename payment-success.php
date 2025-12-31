<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

if (!isset($_GET['request_id'])) {
    header('Location: pay-bill.php');
    exit;
}

$request_id = intval($_GET['request_id']);
$test_mode = isset($_GET['test_mode']);

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
    $payment_request['status'] = 'completed';
    $payment_request['transaction_id'] = $transaction_id;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Payment Successful | PrimeMed Hospital</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.6.1/jspdf.umd.min.js"></script>
    <style>
        .success-container {
            max-width: 650px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .success-icon {
            font-size: 64px;
            color: #28a745;
            margin-bottom: 20px;
        }

        .payment-details {
            text-align: left;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .btn-group {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="success-container" id="payment-content">
            <div class="success-icon"><i class="fa fa-check-circle"></i></div>
            <h2 class="text-success">Payment Successful!</h2>
            <p>Thank you for your payment. Your transaction has been completed successfully.</p>

            <div class="payment-details" id="payment-details">
                <h4>Payment Details</h4>
                <p><strong>Transaction ID:</strong> <span id="transaction-id"><?= htmlspecialchars($payment_request['transaction_id'] ?? 'Processing...') ?></span></p>
                <p><strong>Amount:</strong> ৳<span id="amount"><?= number_format($payment_request['amount'], 2) ?></span></p>
                <p><strong>Description:</strong> <span id="description"><?= htmlspecialchars($payment_request['description']) ?></span></p>
                <p><strong>Doctor:</strong> <span id="doctor"><?= htmlspecialchars($payment_request['doctorName']) ?></span></p>
                <p><strong>Patient:</strong> <span id="patient"><?= htmlspecialchars($payment_request['fullName']) ?></span></p>
                <p><strong>Date:</strong> <span id="date"><?= date('F j, Y, g:i a', strtotime($payment_request['payment_date'] ?? 'now')) ?></span></p>
            </div>

            <div class="btn-group">
                <button id="download-pdf" class="btn btn-warning btn-lg">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </button>
                <button onclick="window.print();" class="btn btn-success btn-lg">
                    <i class="fas fa-print"></i> Print
                </button>
                <a href="dashboard.php" class="btn btn-primary btn-lg">Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Include jsPDF and html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        window.onload = function() {
            const {
                jsPDF
            } = window.jspdf;

            document.getElementById('download-pdf').addEventListener('click', () => {
                const successMessage = document.querySelector('.success-icon').parentNode; // success message block
                const paymentDetails = document.getElementById('payment-details');

                // Create a temporary wrapper inside the DOM
                const wrapper = document.createElement('div');
                wrapper.style.position = 'absolute';
                wrapper.style.left = '-9999px'; // move off-screen
                wrapper.appendChild(successMessage.cloneNode(true));
                wrapper.appendChild(paymentDetails.cloneNode(true));
                document.body.appendChild(wrapper);

                html2canvas(wrapper).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const pdf = new jsPDF('p', 'mm', 'a4');
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                    pdf.save('payment_receipt.pdf');

                    document.body.removeChild(wrapper); // clean up
                }).catch(err => console.error(err));
            });
        };
    </script>



</body>

</html>