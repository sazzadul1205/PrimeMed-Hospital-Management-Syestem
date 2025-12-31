<?php
session_start();
include('../include/config.php');
include('../include/checklogin.php');
check_login();

// Handle status updates
if (isset($_GET['update_status']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $status = mysqli_real_escape_string($con, $_GET['update_status']);

    if (in_array($status, ['pending', 'paid', 'cancelled'])) {
        $query = "UPDATE payment_requests SET status = ? WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("si", $status, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Payment status updated successfully');</script>";
            echo "<script>window.location.href='payment-reports.php';</script>";
        } else {
            echo "<script>alert('Error updating status');</script>";
        }
    }
}

// Fetch all payment requests with patient and doctor details
$query = "SELECT pr.*, u.fullName as patient_name, d.doctorName as doctor_name
          FROM payment_requests pr
          LEFT JOIN users u ON pr.patient_id = u.id
          LEFT JOIN doctors d ON pr.doctor_id = d.id
          ORDER BY pr.created_at DESC";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin | Payment Reports</title>

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
                                <h1 class="mainTitle">Payment Reports</h1>
                            </div>
                            <ol class="breadcrumb">
                                <li><span>Admin</span></li>
                                <li class="active"><span>Payment Reports</span></li>
                            </ol>
                        </div>
                    </section>

                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h5 class="panel-title">All Payment Reports</h5>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped" id="paymentTable">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Patient Name</th>
                                                        <th>Doctor Name</th>
                                                        <th>Amount</th>
                                                        <th>Description</th>
                                                        <th>Transaction ID</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $cnt = 1;
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        $status_class = '';
                                                        switch ($row['status']) {
                                                            case 'pending':
                                                                $status_class = 'label-warning';
                                                                break;
                                                            case 'paid':
                                                                $status_class = 'label-success';
                                                                break;
                                                            case 'cancelled':
                                                                $status_class = 'label-danger';
                                                                break;
                                                        }
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $cnt; ?></td>
                                                            <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                                                            <td>৳<?php echo number_format($row['amount'], 2); ?></td>
                                                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['transaction_id'] ?? 'N/A'); ?></td>
                                                            <td><span class="label <?php echo $status_class; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                                                            <td><?php echo date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>
                                                            <td>
                                                                <?php if ($row['status'] == 'pending') { ?>
                                                                    <a href="?update_status=paid&id=<?php echo $row['id']; ?>" class="btn btn-success btn-xs" onclick="return confirm('Mark as paid?')">Mark Paid</a>
                                                                    <a href="?update_status=cancelled&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Cancel payment?')">Cancel</a>
                                                                <?php } else { ?>
                                                                    <span class="text-muted">Completed</span>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                        $cnt++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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
