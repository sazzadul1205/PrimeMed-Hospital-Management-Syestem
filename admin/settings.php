<?php
session_start();
include('../include/config.php');
include('../include/checklogin.php');
check_login();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin | Settings</title>

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
                                <h1 class="mainTitle">Admin Settings</h1>
                            </div>
                            <ol class="breadcrumb">
                                <li><span>Admin</span></li>
                                <li class="active"><span>Settings</span></li>
                            </ol>
                        </div>
                    </section>

                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h5 class="panel-title">System Settings</h5>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4>UI Settings</h4>
                                                <p>Configure the appearance and behavior of the admin interface.</p>

                                                <div class="setting-box clearfix">
                                                    <span class="setting-title pull-left">Fixed Header</span>
                                                    <span class="setting-switch pull-right">
                                                        <input type="checkbox" class="js-switch" id="fixed-header" />
                                                    </span>
                                                </div>

                                                <div class="setting-box clearfix">
                                                    <span class="setting-title pull-left">Fixed Sidebar</span>
                                                    <span class="setting-switch pull-right">
                                                        <input type="checkbox" class="js-switch" id="fixed-sidebar" />
                                                    </span>
                                                </div>

                                                <div class="setting-box clearfix">
                                                    <span class="setting-title pull-left">Closed Sidebar</span>
                                                    <span class="setting-switch pull-right">
                                                        <input type="checkbox" class="js-switch" id="closed-sidebar" />
                                                    </span>
                                                </div>

                                                <div class="setting-box clearfix">
                                                    <span class="setting-title pull-left">Fixed Footer</span>
                                                    <span class="setting-switch pull-right">
                                                        <input type="checkbox" class="js-switch" id="fixed-footer" />
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <h4>Theme Selection</h4>
                                                <p>Choose a color theme for the admin interface.</p>

                                                <div class="colors-row">
                                                    <div class="color-theme theme-1 active">
                                                        <div class="color-layout">
                                                            <label>
                                                                <input type="radio" name="setting-theme" value="theme-1" checked>
                                                                <span class="ti-check"></span>
                                                                <span class="split header">
                                                                    <span class="color th-header"></span>
                                                                    <span class="color th-collapse"></span>
                                                                </span>
                                                                <span class="split">
                                                                    <span class="color th-sidebar"><i class="element"></i></span>
                                                                    <span class="color th-body"></span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="color-theme theme-2">
                                                        <div class="color-layout">
                                                            <label>
                                                                <input type="radio" name="setting-theme" value="theme-2">
                                                                <span class="ti-check"></span>
                                                                <span class="split header">
                                                                    <span class="color th-header"></span>
                                                                    <span class="color th-collapse"></span>
                                                                </span>
                                                                <span class="split">
                                                                    <span class="color th-sidebar"><i class="element"></i></span>
                                                                    <span class="color th-body"></span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="color-theme theme-3">
                                                        <div class="color-layout">
                                                            <label>
                                                                <input type="radio" name="setting-theme" value="theme-3">
                                                                <span class="ti-check"></span>
                                                                <span class="split header">
                                                                    <span class="color th-header"></span>
                                                                    <span class="color th-collapse"></span>
                                                                </span>
                                                                <span class="split">
                                                                    <span class="color th-sidebar"><i class="element"></i></span>
                                                                    <span class="color th-body"></span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="colors-row">
                                                    <div class="color-theme theme-4">
                                                        <div class="color-layout">
                                                            <label>
                                                                <input type="radio" name="setting-theme" value="theme-4">
                                                                <span class="ti-check"></span>
                                                                <span class="split header">
                                                                    <span class="color th-header"></span>
                                                                    <span class="color th-collapse"></span>
                                                                </span>
                                                                <span class="split">
                                                                    <span class="color th-sidebar"><i class="element"></i></span>
                                                                    <span class="color th-body"></span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="color-theme theme-5">
                                                        <div class="color-layout">
                                                            <label>
                                                                <input type="radio" name="setting-theme" value="theme-5">
                                                                <span class="ti-check"></span>
                                                                <span class="split header">
                                                                    <span class="color th-header"></span>
                                                                    <span class="color th-collapse"></span>
                                                                </span>
                                                                <span class="split">
                                                                    <span class="color th-sidebar"><i class="element"></i></span>
                                                                    <span class="color th-body"></span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="color-theme theme-6">
                                                        <div class="color-layout">
                                                            <label>
                                                                <input type="radio" name="setting-theme" value="theme-6">
                                                                <span class="ti-check"></span>
                                                                <span class="split header">
                                                                    <span class="color th-header"></span>
                                                                    <span class="color th-collapse"></span>
                                                                </span>
                                                                <span class="split">
                                                                    <span class="color th-sidebar"><i class="element"></i></span>
                                                                    <span class="color th-body"></span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h4>System Information</h4>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <td><strong>PHP Version:</strong></td>
                                                                <td><?php echo phpversion(); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Server:</strong></td>
                                                                <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Database:</strong></td>
                                                                <td>MySQL</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Time Zone:</strong></td>
                                                                <td><?php echo date_default_timezone_get(); ?></td>
                                                            </tr>
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
    <script src="assets/js/ui-elements.js"></script>

    <script>
        jQuery(document).ready(function() {
            Main.init();
            UIElements.init();
        });
    </script>
</body>

</html>
