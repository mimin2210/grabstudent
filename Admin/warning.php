<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['ADMIN_ID'])) {
    header("Location: login.html");
    exit;
}

$admin_id = $_SESSION['ADMIN_ID'];

$stmt = $conn->prepare("
    SELECT d.DRIVER_ID, d.DRIVER_NAME, COUNT(r.REPORT_ID) AS AcceptedCount, d.DRIVER_WARNING, d.DRIVER_STATUS
    FROM driver d
    JOIN report r ON d.DRIVER_ID = r.DRIVER_ID
    WHERE r.REPORT_STATUS = 'ACCEPT'
    GROUP BY d.DRIVER_ID, d.DRIVER_NAME
    HAVING COUNT(r.REPORT_ID) >= 3
");
$stmt->execute();
$result = $stmt->get_result();
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
        <h1 id="title">REPORTED DRIVER <i class="fa fa-flag"></i></h1>
        <section class="ride-form">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Driver</th>
                        <th>Report</th>
                        <th>Email</th>
                        <th>Warning</th>
                        <th>Ban</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['DRIVER_NAME']); ?></td>
                                <td><?php echo htmlspecialchars($row['AcceptedCount']); ?></td>
                                <td><a href="warning_action.php?driver_id=<?php echo htmlspecialchars ($row['DRIVER_ID']); ?>" class="warning-button">EMAIL</a></td>
                                <td><?php echo htmlspecialchars($row['DRIVER_WARNING']); ?></td>
                                <td><a href="ban_driver.php?driver_id=<?php echo htmlspecialchars ($row['DRIVER_ID']); ?>" class="ban-button">BAN</a></td>
                                <td style="color:red;font-weight:bold;"><?php echo htmlspecialchars($row['DRIVER_STATUS']); ?></td>
                                </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No drivers with more than 3 accepted reports found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
