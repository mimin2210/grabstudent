<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['PASSENGER_ID'])) {
    header("Location: login.html");
    exit;
} else {
    $passenger_id = $_SESSION['PASSENGER_ID'];
}

$reports = [];
$stmt = $conn->prepare("
    SELECT r.*, d.DRIVER_NAME 
    FROM report r
    LEFT JOIN driver d ON r.DRIVER_ID = d.DRIVER_ID
    ORDER BY r.REPORT_TIME DESC
");

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}

$stmt->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Report History</title>
    </head>
    <body id="body">
        <?php include('includes/navigation.php'); ?>

        <h1 id="title">REPORT HISTORY <i class="fa fa-flag" aria-hidden="true"></i></h1>

        <section>
            <div class="ride-form">
                <?php if (count($reports) > 0): ?>
                    <?php foreach ($reports as $report): ?>
                        
                            <table class="report-history">
                                <tr>
                                    <th colspan="2"><?php echo htmlspecialchars($report['REPORT_SUBJECT']); ?></th>
                                </tr>
                                <tr>
                                    <td>Reported Driver</td>
                                    <td><?php echo htmlspecialchars($report['DRIVER_NAME']); ?></td>
                                </tr>
                                <tr>
                                    <td>Ride Date</td>
                                    <td><?php echo htmlspecialchars($report['RIDE_DATE']); ?></td>
                                </tr>
                                <tr>
                                    <td>Ride Time</td>
                                    <td><?php echo htmlspecialchars($report['RIDE_TIME']); ?></td>
                                </tr>
                                <tr>
                                    <td>Comments</td>
                                    <td><?php echo htmlspecialchars($report['REPORT_COMMENT']); ?></td>
                                </tr>
                                <tr>
                                    <td>File</td>
                                    <td>
                                        <?php if (!empty($report['REPORT_FILE'])): ?>
                                            <a href="../Passenger/<?php echo htmlspecialchars($report['REPORT_FILE']); ?>" target="_blank">View File</a>
                                        <?php else: ?>
                                            No file attached
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reports found.</p>
                <?php endif; ?>
            </div>
        </section>
    </body>
    <br><br><br><br><br>
    <br><br><br><br><br>
</html>
