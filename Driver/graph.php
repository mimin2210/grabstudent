<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['DRIVER_ID'])) {
    header("Location: login.html");
    exit;
}

$driver_id = $_SESSION['DRIVER_ID'];

$query = "
    SELECT 
        DATE_FORMAT(FEEDBACK_DATE, '%Y-%m') AS month,
        SUM(CASE WHEN FEEDBACK_RATING = 1 THEN 1 ELSE 0 END) AS rate_1,
        SUM(CASE WHEN FEEDBACK_RATING = 2 THEN 1 ELSE 0 END) AS rate_2,
        SUM(CASE WHEN FEEDBACK_RATING = 3 THEN 1 ELSE 0 END) AS rate_3,
        SUM(CASE WHEN FEEDBACK_RATING = 4 THEN 1 ELSE 0 END) AS rate_4,
        SUM(CASE WHEN FEEDBACK_RATING = 5 THEN 1 ELSE 0 END) AS rate_5
    FROM feedback
    WHERE DRIVER_ID = ?
    GROUP BY month
    ORDER BY month ASC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $driver_id);
$stmt->execute();
$result = $stmt->get_result();

$months = [];
$ratings = [];
$feedbacks = [];

while ($row = $result->fetch_assoc()) {
    $rate_1 = $row['rate_1'];
    $rate_2 = $row['rate_2'];
    $rate_3 = $row['rate_3'];
    $rate_4 = $row['rate_4'];
    $rate_5 = $row['rate_5'];

    $total_ratings = $rate_1 + $rate_2 + $rate_3 + $rate_4 + $rate_5;
    if ($total_ratings > 0) {
        $weighted_average = (
            (1 * $rate_1) +
            (2 * $rate_2) +
            (3 * $rate_3) +
            (4 * $rate_4) +
            (5 * $rate_5)
        ) / $total_ratings;
    } else {
        $weighted_average = 0;
    }

    $months[] = $row['month']; 
    $ratings[] = round($weighted_average); 
    $feedbacks[] = $total_ratings;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include('includes/navigation.php'); ?>
    <h1 id="title">MONTHLY STAR RATING <i class="fa fa-star" aria-hidden="true"></i></h1>
    <section>
        <canvas id="lineChart" width="400" height="200"></canvas>

        <?php
        echo "<script>
            const labels = " . json_encode($months) . ";
            const values = " . json_encode($ratings) . ";
            const feedbacks = " . json_encode($feedbacks) . ";
        </script>";
        ?>

        <script>
            const ctx = document.getElementById('lineChart').getContext('2d');
            const lineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels, 
                    datasets: [{
                        label: 'Star Ratings',
                        data: values,
                        borderColor: 'rgba(67, 56, 120, 0.7)',
                        backgroundColor: 'rgba(67, 56, 120, 0.2)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Monthly Star Ratings (1 to 5 Stars)'
                        },
                        tooltip: {
                            callbacks: {
                                title: function(tooltipItem) {
                                    return tooltipItem[0].label;
                                },
                                label: function(tooltipItem) {
                                    const starRating = tooltipItem.raw;
                                    const totalFeedbacks = feedbacks[tooltipItem.dataIndex];
                                    return 'Star Ratings: ' + starRating + '\nTotal Feedbacks: ' + totalFeedbacks;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 1,
                            max: 5,
                            ticks: {
                                stepSize: 1
                            },
                            title: {
                                display: true,
                                text: 'Stars'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Months'
                            }
                        }
                    }
                }
            });
        </script>

    </section>
</body>
</html>
