<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['ADMIN_ID'])) {
    header("Location: login.html");
    exit;
} else {
    $admin_id = $_SESSION['ADMIN_ID'];
}

$reports = [];
$stmt = $conn->prepare("SELECT rp.*, r.PASSENGER_NAME, d.DRIVER_NAME
FROM report rp 
JOIN passenger r ON rp.PASSENGER_ID = r.PASSENGER_ID
JOIN driver d ON rp.DRIVER_ID = d.DRIVER_ID
WHERE REPORT_STATUS = 'ACCEPT'");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
} else {
    $error_message = "No reports found.";
}

$reject = [];

$stmt1 = $conn->prepare("SELECT rp.*, r.PASSENGER_NAME, d.DRIVER_NAME
FROM report rp 
JOIN passenger r ON rp.PASSENGER_ID = r.PASSENGER_ID
JOIN driver d ON rp.DRIVER_ID = d.DRIVER_ID
WHERE REPORT_STATUS = 'REJECT'");

$stmt1->execute();
$result1 = $stmt1->get_result();

if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        $reject[] = $row;
    }
} else {
    $error_message = "No reports found.";
}

$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body id="body">
    <?php include('includes/navigation.php'); ?>
    <div class="view-report-section">
    <h1 id="title">VIEW REPORT <i class="fa fa-flag"></i></h1>
    <section>
        <div class="ride-form">
            <h2 class="view_report">Accepted Report</h2>
            <table class="report-table">
                <tr>
                    <th>Reported Driver</th>
                    <th>Report Subject</th>
                    <th>Ride Date</th>
                    <th>Report Comment</th>
                    <th>Report File</th>
                </tr>
                <?php
                if (!empty($reports)) {
                    foreach ($reports as $report) {
                        echo "<tr>";
                        echo "<td style='width: 15%;'>" . htmlspecialchars($report['DRIVER_NAME']) . "</td>";
                        echo "<td style='width: 15%;'>" . htmlspecialchars($report['REPORT_SUBJECT']) . "</td>";
                        echo "<td style='width: 10%;'>" . htmlspecialchars($report['RIDE_DATE']) . "</td>";
                        echo "<td style='width: 25%;'>" . htmlspecialchars($report['REPORT_COMMENT']) . "</td>";
                        
                        if (!empty($report['REPORT_FILE'])) {
                            echo '<td style=\'width: 10%;\'><a href="../Passenger/' . htmlspecialchars($report['REPORT_FILE']) . '" target="_blank">View File</a></td>';
                        } else {
                            echo '<td>No file attached</td>';
                        }
                    }
                } else {
                    echo "<tr><td colspan='7'>No reports found.</td></tr>";
                }
                ?>
            </table>
            
            <br><br><h2 class="view_report">Rejected Report</h2>
            <table class="report-table">
                <tr>
                    <th>Reported Driver</th>
                    <th>Report Subject</th>
                    <th>Ride date</th>
                    <th>Report Comment</th>
                    <th>Report File</th>
                </tr>
                <?php
                if (!empty($reject)) {
                    foreach ($reject as $report) {
                        echo "<tr>";
                        echo "<td style='width: 15%;'>" . htmlspecialchars($report['DRIVER_NAME']) . "</td>";
                        echo "<td style='width: 15%;'>" . htmlspecialchars($report['REPORT_SUBJECT']) . "</td>";
                        echo "<td style='width: 10%;'>" . htmlspecialchars($report['RIDE_DATE']) . "</td>";
                        echo "<td style='width: 25%;'>" . htmlspecialchars($report['REPORT_COMMENT']) . "</td>";
                        
                        if (!empty($report['REPORT_FILE'])) {
                            echo '<td style=\'width: 10%;\'><a href="../Passenger/' . htmlspecialchars($report['REPORT_FILE']) . '" target="_blank">View File</a></td>';
                        } else {
                            echo '<td>No file attached</td>';
                        }
                    }
                } else {
                    echo "<tr><td colspan='7'>No reports found.</td></tr>";
                }
                ?>
            </table>
        </div>
    </section>
    </div>
</body>
<br><br><br><br><br><br><br><br>
</html>
