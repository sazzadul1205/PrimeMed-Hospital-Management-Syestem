<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

require_once('vendor/autoload.php');

// Get payment ID from URL
$payment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$payment_id) {
    die('Invalid payment ID');
}

// Fetch payment details
$stmt = $con->prepare("
    SELECT pr.*, u.fullName, u.email, d.doctorName, d.docFees
    FROM payment_requests pr
    JOIN users u ON pr.patient_id = u.id
    JOIN doctors d ON pr.doctor_id = d.id
    WHERE pr.id = ? AND pr.patient_id = ?
");
$stmt->bind_param("ii", $payment_id, $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Payment not found or access denied');
}

$payment = $result->fetch_assoc();

// Create PDF
class ReceiptPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 20);
        $this->Cell(0, 15, 'PrimeMed Hospital', 0, 1, 'C');
        $this->SetFont('helvetica', '', 12);
        $this->Cell(0, 10, 'Advanced Healthcare Management System', 0, 1, 'C');
        $this->Cell(0, 10, 'Payment Receipt', 0, 1, 'C');
        $this->Ln(10);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Generated on ' . date('Y-m-d H:i:s'), 0, 0, 'C');
    }
}

$pdf = new ReceiptPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator('PrimeMed Hospital');
$pdf->SetAuthor('PrimeMed System');
$pdf->SetTitle('Payment Receipt #' . $payment_id);

$pdf->setPrintHeader(true);
$pdf->setPrintFooter(true);
$pdf->SetMargins(15, 27, 15);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->AddPage();

// Receipt content
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Receipt Details', 0, 1, 'L');
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 11);

// Receipt information
$receiptData = [
    'Receipt Number' => 'PR-' . str_pad($payment_id, 6, '0', STR_PAD_LEFT),
    'Date' => date('F j, Y', strtotime($payment['created_at'])),
    'Time' => date('g:i A', strtotime($payment['created_at'])),
    'Patient Name' => $payment['fullName'],
    'Patient Email' => $payment['email'],
    'Doctor Name' => $payment['doctorName'],
    'Service Description' => $payment['description'],
    'Amount' => 'BDT ' . number_format($payment['amount'], 2),
    'Payment Method' => ucfirst($payment['payment_method'] ?? 'N/A'),
    'Transaction ID' => $payment['transaction_id'] ?? 'N/A',
    'Status' => ucfirst($payment['status'])
];

foreach ($receiptData as $label => $value) {
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(50, 8, $label . ':', 0, 0);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->Cell(0, 8, $value, 0, 1);
}

$pdf->Ln(10);

// Thank you message
$pdf->SetFont('helvetica', 'I', 10);
$pdf->Cell(0, 10, 'Thank you for choosing PrimeMed Hospital. We wish you a speedy recovery!', 0, 1, 'C');

// Output PDF
$filename = 'receipt_' . $payment_id . '_' . date('Y-m-d') . '.pdf';
$pdf->Output($filename, 'D');
exit;
?>
