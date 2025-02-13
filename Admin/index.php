<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['ADMIN_ID'])) {
    header("Location: login.html");
    exit;
}

// Count the rows in the passenger table
$passenger_count = 0;
$driver_count = 0;
$pending_report_count = 0;
$building_count = 0;

// Count passengers
$passenger_query = $conn->query("SELECT COUNT(*) AS count FROM passenger");
if ($passenger_query) {
    $passenger_result = $passenger_query->fetch_assoc();
    $passenger_count = $passenger_result['count'];
}

// Count drivers
$driver_query = $conn->query("SELECT COUNT(*) AS count FROM driver");
if ($driver_query) {
    $driver_result = $driver_query->fetch_assoc();
    $driver_count = $driver_result['count'];
}

// Count pending reports
$pending_report_query = $conn->query("SELECT COUNT(*) AS count FROM report WHERE REPORT_STATUS = ''");
if ($pending_report_query) {
    $pending_report_result = $pending_report_query->fetch_assoc();
    $pending_report_count = $pending_report_result['count'];
}

// Count buildings
$building_query = $conn->query("SELECT COUNT(*) AS count FROM building");
if ($building_query) {
    $building_result = $building_query->fetch_assoc();
    $building_count = $building_result['count'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h1 id="title">ADMIN DASHBOARD</h1>
    <p style="text-align:center;color:#48426D;font-size:20px">Current number of passengers, drivers, pending reports, and buildings</p>

    <section class="index">
        <?php include('includes/navigation.php'); ?>
        <div class="purple"><?php echo $passenger_count; ?> PASSENGER</div>
        <div class="purple"><?php echo $driver_count; ?> DRIVERS</div>
        <div class="purple"><?php echo $pending_report_count; ?> PENDING REPORT</div>
        <div class="purple"><?php echo $building_count; ?> BUILDING</div>
    </section>
</body>
</html>
