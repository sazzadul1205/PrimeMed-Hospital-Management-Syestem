<?php
header('Content-Type: application/json');
include('../../include/config.php');

$request_id = intval($_POST['request_id']);
$amount = floatval($_POST['amount']);

// Fetch payment request details
$query = "SELECT pr.*, u.fullName, u.email
          FROM payment_requests pr
          JOIN users u ON pr.patient_id = u.id
          WHERE pr.id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$payment_request = $stmt->get_result()->fetch_assoc();

if (!$payment_request) {
    echo json_encode(['success' => false, 'message' => 'Payment request not found']);
    exit;
}

// Generate unique payment ID
$payment_id = 'BKASH_' . time() . '_' . $request_id;

// Store payment ID in database
$update_query = "UPDATE payment_requests SET bkash_payment_id = ? WHERE id = ?";
$update_stmt = $con->prepare($update_query);
$update_stmt->bind_param("si", $payment_id, $request_id);
$update_stmt->execute();

// Check if test mode is enabled (for development/demo purposes)
$test_mode = true; // Set to false when using real bKash credentials

if ($test_mode) {
    // Simulate successful payment creation for testing
    $checkout_url = 'payment-success.php?request_id=' . $request_id . '&test_mode=1';
    echo json_encode([
        'success' => true,
        'checkout_url' => $checkout_url,
        'payment_id' => $payment_id
    ]);
    exit;
}

// bKash API integration (sandbox mode for testing)
$bkash_config = [
    'app_key' => 'your_bkash_app_key',
    'app_secret' => 'your_bkash_app_secret',
    'username' => 'your_bkash_username',
    'password' => 'your_bkash_password',
    'base_url' => 'https://checkout.sandbox.bka.sh/v1.2.0-beta' // Use production URL for live
];

// Get access token
$token_response = getBkashToken($bkash_config);
if (!$token_response['success']) {
    echo json_encode(['success' => false, 'message' => 'Failed to get bKash token']);
    exit;
}

$access_token = $token_response['token'];

// Create payment
$payment_data = [
    'amount' => $amount,
    'currency' => 'BDT',
    'intent' => 'sale',
    'merchantInvoiceNumber' => 'INV_' . $request_id,
    'merchantAssociationInfo' => 'PrimeMed Hospital'
];

$create_response = createBkashPayment($bkash_config, $access_token, $payment_data);

if ($create_response['success']) {
    echo json_encode([
        'success' => true,
        'checkout_url' => $create_response['bkashURL'],
        'payment_id' => $payment_id
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create payment']);
}

function getBkashToken($config) {
    $url = $config['base_url'] . '/checkout/token/grant';
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
        'username: ' . $config['username'],
        'password: ' . $config['password']
    ];

    $data = [
        'app_key' => $config['app_key'],
        'app_secret' => $config['app_secret']
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($http_code == 200 && isset($result['id_token'])) {
        return ['success' => true, 'token' => $result['id_token']];
    } else {
        return ['success' => false, 'error' => $result];
    }
}

function createBkashPayment($config, $token, $payment_data) {
    $url = $config['base_url'] . '/checkout/payment/create';
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: ' . $token,
        'X-APP-Key: ' . $config['app_key']
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payment_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($http_code == 200 && isset($result['bkashURL'])) {
        return ['success' => true, 'bkashURL' => $result['bkashURL'], 'paymentID' => $result['paymentID']];
    } else {
        return ['success' => false, 'error' => $result];
    }
}
?>
