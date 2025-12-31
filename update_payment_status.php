<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_id']) && isset($_POST['status'])) {
    $request_id = intval($_POST['request_id']);
    $status = mysqli_real_escape_string($con, $_POST['status']);

    // Verify that the payment request belongs to the logged-in user
    $user_id = $_SESSION['id'];
    $query = "SELECT id FROM payment_requests WHERE id = ? AND patient_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $request_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update the status
        $update_query = "UPDATE payment_requests SET status = ?, updated_at = NOW() WHERE id = ?";
        $update_stmt = $con->prepare($update_query);
        $update_stmt->bind_param("si", $status, $request_id);

        if ($update_stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Payment status updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update payment status']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Payment request not found or access denied']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
