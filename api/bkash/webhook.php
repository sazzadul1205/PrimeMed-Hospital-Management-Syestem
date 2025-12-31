<?php
header('Content-Type: application/json');
include('../../include/config.php');

// Get webhook data
$webhook_data = json_decode(file_get_contents('php://input'), true);

// Log webhook data for debugging
file_put_contents('bkash_webhook.log', date('Y-m-d H:i:s') . ' - ' . json_encode($webhook_data) . PHP_EOL, FILE_APPEND);

if (!$webhook_data) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid webhook data']);
    exit;
}

// Check if this is a test mode payment
$test_mode = isset($webhook_data['test_mode']) && $webhook_data['test_mode'] == 1;

if ($test_mode) {
    // Handle test mode payment
    $payment_id = $webhook_data['paymentID'];
    $transaction_status = 'Completed';
    $amount = $webhook_data['amount'];
    $merchant_invoice = $webhook_data['merchantInvoiceNumber'];
    $transaction_id = 'TEST_' . time() . '_' . $webhook_data['request_id'];
} else {
    // Verify webhook signature (implement based on bKash documentation)
    $payment_id = $webhook_data['paymentID'];
    $transaction_status = $webhook_data['transactionStatus'];
    $amount = $webhook_data['amount'];
    $merchant_invoice = $webhook_data['merchantInvoiceNumber'];
    $transaction_id = $webhook_data['trxID'] ?? null;
}

try {
    // Find payment request
    $query = "SELECT pr.*, u.fullName, u.email
              FROM payment_requests pr
              JOIN users u ON pr.patient_id = u.id
              WHERE pr.bkash_payment_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $payment_id);
    $stmt->execute();
    $payment_request = $stmt->get_result()->fetch_assoc();

    if (!$payment_request) {
        throw new Exception('Payment request not found');
    }

    // Update payment status
    $status = ($transaction_status === 'Completed') ? 'completed' : 'failed';
    $update_query = "UPDATE payment_requests SET
                     status = ?,
                     transaction_id = ?,
                     payment_date = NOW(),
                     updated_at = NOW()
                     WHERE bkash_payment_id = ?";
    $update_stmt = $con->prepare($update_query);
    $transaction_id = $webhook_data['trxID'] ?? null;
    $update_stmt->bind_param("sss", $status, $transaction_id, $payment_id);
    $update_stmt->execute();

    // Send notifications
    if ($status === 'completed') {
        // Send email notification
        sendPaymentSuccessEmail($payment_request);

        // Send SMS notification (if SMS service is configured)
        sendPaymentSuccessSMS($payment_request);
    }

    echo json_encode(['status' => 'success', 'message' => 'Payment status updated']);

} catch (Exception $e) {
    error_log('bKash webhook error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

function sendPaymentSuccessEmail($payment_request) {
    $to = $payment_request['email'];
    $subject = "Payment Successful - PrimeMed Hospital";
    $message = "
    <html>
    <head>
        <title>Payment Confirmation</title>
    </head>
    <body>
        <h2>Payment Successful!</h2>
        <p>Dear {$payment_request['fullName']},</p>
        <p>Your payment of ৳{$payment_request['amount']} has been successfully processed.</p>
        <p><strong>Transaction Details:</strong></p>
        <ul>
            <li>Description: {$payment_request['description']}</li>
            <li>Amount: ৳{$payment_request['amount']}</li>
            <li>Transaction ID: {$payment_request['transaction_id']}</li>
            <li>Date: " . date('F j, Y, g:i a') . "</li>
        </ul>
        <p>Thank you for choosing PrimeMed Hospital Management System.</p>
        <p>Best regards,<br>PrimeMed Hospital Team</p>
    </body>
    </html>
    ";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: PrimeMed Hospital <noreply@primemed.com>" . "\r\n";

    mail($to, $subject, $message, $headers);
}

function sendPaymentSuccessSMS($payment_request) {
    // Implement SMS sending logic here
    // You can use services like Twilio, Nexmo, or local Bangladeshi SMS gateways

    $phone = $payment_request['contactno'];
    $message = "Payment Successful! Amount: ৳{$payment_request['amount']}. Transaction ID: {$payment_request['transaction_id']}. Thank you for choosing PrimeMed Hospital.";

    // Example using a hypothetical SMS service
    // $sms_result = sendSMS($phone, $message);

    // Log SMS attempt
    file_put_contents('sms_notifications.log',
        date('Y-m-d H:i:s') . " - SMS sent to {$phone}: {$message}" . PHP_EOL,
        FILE_APPEND
    );
}
?>
