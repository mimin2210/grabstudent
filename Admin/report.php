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
$stmt = $conn->prepare("
    SELECT r.*, d.DRIVER_NAME, p.PASSENGER_NAME 
    FROM report r
    JOIN driver d ON r.DRIVER_ID = d.DRIVER_ID
    JOIN passenger p ON r.PASSENGER_ID = p.PASSENGER_ID
    WHERE r.REPORT_STATUS = ''
    ORDER BY r.REPORT_ID DESC
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

        <h1 id="title">PENDING REPORT <i class="fa fa-flag" aria-hidden="true"></i></h1>

        <section>
            <div class="ride-form">
                <a href="warning.php" class="view-report">View Drivers with 3+ Reports</a>
                <a href="view_report.php" class="view-report" style="margin-right: 10px">View Report</a>
                <?php if (count($reports) > 0): ?>
                    <?php foreach ($reports as $report): ?>
                        <table class="report-history">
                            <tr>
                                <th colspan="2"><?php echo htmlspecialchars($report['REPORT_SUBJECT']); ?></th>
                            </tr>
                            <tr>
                                <td>Reported Driver</td>
                                <td><?php echo htmlspecialchars($report['DRIVER_NAME'] ?? 'Unknown Driver'); ?></td>
                            </tr>
                            <tr>
                                <td>Reported by</td>
                                <td><?php echo htmlspecialchars($report['PASSENGER_NAME']); ?></td>
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
                        <div class="status">
                            <form class="report" action="status.php" method="post">
                                <input type="hidden" name="report_id" value="<?php echo $report['REPORT_ID']; ?>">
                                <button type="submit" class="accept" name="action" value="accept">ACCEPT</button>
                                <button type="submit" class="reject" name="action" value="reject">REJECT</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <br><p>No pending reports found.</p><br>
                <?php endif; ?>
            </div>
        </section>
    </body>
    <br><br><br><br><br>
    <br><br><br><br><br>
</html>
