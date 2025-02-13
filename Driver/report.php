<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['DRIVER_ID'])) {
    header("Location: login.html");
    exit;
}

$driver = (int)$_SESSION['DRIVER_ID'];

$query = "
    SELECT DISTINCT YEAR(FEEDBACK_DATE) AS report_year, MONTH(FEEDBACK_DATE) AS report_month
    FROM feedback
    WHERE DRIVER_ID = ?
    ORDER BY report_year DESC, report_month DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $driver);
$stmt->execute();
$result = $stmt->get_result();

$reports_by_year = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $report_year = $row['report_year'];
        $report_month = str_pad($row['report_month'], 2, '0', STR_PAD_LEFT); // Format month as two digits
        $reports_by_year[$report_year][] = $report_month;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php include('includes/navigation.php'); ?>
    <h1 id="title">MONTHLY REPORT <i class="fa fa-star" aria-hidden="true"></i></h1>
    <div class="graph-container">
        <a href="graph.php" class="graph">View Graph</a>
    </div>
    <section class="report-section">
        <?php foreach ($reports_by_year as $year => $months): ?>
            <div class="report-year"><?php echo htmlspecialchars($year); ?></div><br>
            <form method="POST" action="performance.php" class="month-selection">
                <?php foreach ($months as $month): 
                    $month_name = date("F", mktime(0, 0, 0, $month, 10));
                    $formatted_month = $year . '-' . $month;
                ?>
                    <button type="submit" name="month" value="<?php echo htmlspecialchars($formatted_month); ?>">
                        <?php echo htmlspecialchars($month_name); ?>
                    </button>
                <?php endforeach; ?>
            </form><br><br>
        <?php endforeach; ?>
    </section>
</body>
</html>
