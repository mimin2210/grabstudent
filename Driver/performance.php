<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['DRIVER_ID'])) {
    header("Location: login.html");
    exit;
}

if (!isset($_POST['month'])) {
    header("Location: report.php");
    exit;
}

$selected_month = $_POST['month'];

$driver_id = $_SESSION['DRIVER_ID'];

$query = "
    SELECT 
        d.DRIVER_NAME, 
        d.DRIVER_MATRICS, 
        mr.FEEDBACK_RATING, 
        COUNT(mr.FEEDBACK_RATING) AS total
    FROM driver d
    LEFT JOIN feedback mr ON d.DRIVER_ID = mr.DRIVER_ID
    WHERE d.DRIVER_ID = ? AND DATE_FORMAT(mr.FEEDBACK_DATE, '%Y-%m') = ?
    GROUP BY mr.FEEDBACK_RATING";

$stmt = $conn->prepare($query);
$stmt->bind_param("is", $driver_id, $selected_month);
$stmt->execute();
$result = $stmt->get_result();

$ratings = [];
$driver_info = [];
$total_feedback = 0;
$sum_good_rating = 0;
$weighted_sum = 0;

while ($row = $result->fetch_assoc()) {
    if (empty($driver_info)) {
        $driver_info = [
            'name' => $row['DRIVER_NAME'],
            'matrics' => $row['DRIVER_MATRICS']
        ];
    }

    $rating = (int) $row['FEEDBACK_RATING'];
    $count = (int) $row['total'];

    $ratings[$rating] = $count;
    $total_feedback += $count;
    if ($rating >= 3) {
        $sum_good_rating += $count;
    }
    $weighted_sum += $rating * $count;
}

$stmt->close();

$score = $total_feedback > 0 ? round(($sum_good_rating / $total_feedback) * 100) : 0;
$star_rating = $total_feedback > 0 ? round($weighted_sum / $total_feedback, 1) : 0;

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body id="body">
    <?php include('includes/navigation.php'); ?>
    <h1 id="title">DRIVER MONTHLY REPORT <i class="fa fa-star" aria-hidden="true"></i></h1>
    <section>
        <div class="ride-form">
            <div class="stars">
                <?php 
                $rounded_star_rating = round($star_rating);
                for ($i = 1; $i <= $rounded_star_rating; $i++): ?>
                    <i class="fa fa-star" aria-hidden="true" style="color:gold;font-size: 30px;"></i>
                <?php endfor; ?>
            </div>
            <br>
            <h3><?php echo htmlspecialchars($driver_info['name']); ?></h3>
            <h3><?php echo htmlspecialchars($driver_info['matrics']); ?></h3><br>
            <h4>Score: <?php echo $score; ?>%</h4>
            <h4>Number of Feedbacks: <?php echo $total_feedback; ?></h4>
        </div>
    </section>
</body>
</html>
