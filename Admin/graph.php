<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['ADMIN_ID'])) {
    header("Location: login.html");
    exit;
}

// Get the selected driver's ID
if (!isset($_POST['driver_id'])) {
    header("Location: driver_report.php");
    exit;
}

$driver_id = $_POST['driver_id'];

$query = "
    SELECT 
        d.DRIVER_NAME, 
        d.DRIVER_MATRICS, 
        mr.FEEDBACK_RATING, 
        DATE_FORMAT(mr.REPORT_TIME, '%Y-%m') AS report_month
    FROM driver d
    LEFT JOIN feedback mr ON d.DRIVER_ID = mr.DRIVER_ID
    WHERE d.DRIVER_ID = ? 
    ORDER BY report_month DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $driver_id);
$stmt->execute();
$result = $stmt->get_result();

$reports = [];
$driver_info = [];

if ($row = $result->fetch_assoc()) {
    $driver_info = [
        'name' => $row['DRIVER_NAME'],
        'matrics' => $row['DRIVER_MATRICS']
    ];

    do {
        $rating = (int) $row['FEEDBACK_RATING'];
        $report_month = $row['report_month'];

        if (!isset($reports[$report_month])) {
            $reports[$report_month] = [
                'ratings' => [],
                'total_feedback' => 0,
                'sum_good_rating' => 0,
                'weighted_sum' => 0
            ];
        }

        $reports[$report_month]['ratings'][] = $rating;
        $reports[$report_month]['total_feedback'] += 1;
        if ($rating >= 3) {
            $reports[$report_month]['sum_good_rating'] += 1;
        }
        $reports[$report_month]['weighted_sum'] += $rating;
    } while ($row = $result->fetch_assoc());
}

$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php include('includes/navigation.php'); ?>
    <h1 id="title">DRIVER MONTHLY REPORT <i class="fa fa-star" aria-hidden="true"></i></h1>
    <div class="graph-container">
        <a href="graph.php?driver_id=<?php echo $driver_id; ?>" class="graph">View Graph</a>
    </div>
    <section class="performance-section">
        <div class="driver-details">
            <h3><?php echo htmlspecialchars($driver_info['name']); ?></h3>
            <h3><?php echo htmlspecialchars($driver_info['matrics']); ?></h3>
        </div>
        <?php if (empty($reports)): ?>
            <p class="no-reports">No reports available for this driver.</p>
        <?php else: ?>
            <?php foreach ($reports as $month => $report): 
                $score = $report['total_feedback'] > 0 ? round(($report['sum_good_rating'] / $report['total_feedback']) * 100) : 0;
                $star_rating = $report['total_feedback'] > 0 ? round($report['weighted_sum'] / $report['total_feedback'], 1) : 0;
            ?>
                <div class="ride-form">
                    <h4 class="month">Month: <?php echo htmlspecialchars($month); ?></h4>
                    <div class="stars">
                        <?php 
                        $rounded_star_rating = round($star_rating);
                        for ($i = 1; $i <= $rounded_star_rating; $i++): ?>
                            <i class="fa fa-star" aria-hidden="true"></i>
                        <?php endfor; ?>
                    </div>
                    <br>
                    <h4>Score: <?php echo $score; ?>%</h4>
                    <h4>Number of Feedbacks: <?php echo $report['total_feedback']; ?></h4>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</body>
<br><br><br><br><br><br>
</html>
