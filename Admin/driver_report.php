<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['ADMIN_ID'])) {
    header("Location: login.html");
    exit;
}

//graph
$query = "
    SELECT 
        DATE_FORMAT(FEEDBACK_DATE, '%Y-%m') AS month,
        SUM(CASE WHEN FEEDBACK_RATING = 1 THEN 1 ELSE 0 END) AS rate_1,
        SUM(CASE WHEN FEEDBACK_RATING = 2 THEN 1 ELSE 0 END) AS rate_2,
        SUM(CASE WHEN FEEDBACK_RATING = 3 THEN 1 ELSE 0 END) AS rate_3,
        SUM(CASE WHEN FEEDBACK_RATING = 4 THEN 1 ELSE 0 END) AS rate_4,
        SUM(CASE WHEN FEEDBACK_RATING = 5 THEN 1 ELSE 0 END) AS rate_5
    FROM feedback
    GROUP BY month
    ORDER BY month ASC
";
$stmt = $conn->prepare($query);
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
//end graph

//histogram
$query = "
    SELECT 
        DATE_FORMAT(ORDER_DATE, '%Y-%m') AS month,
        SUM(ORDER_PRICE) AS total_price
    FROM ride_order
    GROUP BY month
    ORDER BY month ASC
";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$months_histogram = [];
$total_prices = [];

while ($row = $result->fetch_assoc()) {
    $months_histogram[] = $row['month'];
    $total_prices[] = $row['total_price'];
}
//end histogram

// Fetch all drivers from the database
$query = "SELECT DRIVER_ID, DRIVER_NAME FROM driver";
$result = $conn->query($query);
$drivers = $result->fetch_all(MYSQLI_ASSOC);
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
    <h1 id="title">MONTHLY REPORT <i class="fa fa-star" aria-hidden="true"></i></h1>
    
    <!-- Monthly Performance Graph -->
    <canvas id="lineChart" width="400" height="200" style="margin:40px"></canvas>
    
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
                        text: 'Drivers Monthly Star Ratings (1 to 5 Stars)'
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
    
    <!-- Monthly Revenue Histogram -->
    <canvas id="barChart" width="400" height="200" style="margin:40px"></canvas>

    <?php
    echo "<script>
        const monthsHistogram = " . json_encode($months_histogram) . ";
        const totalPrices = " . json_encode($total_prices) . ";
    </script>";
    ?>

    <script>
        const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: monthsHistogram,
                datasets: [{
                    label: 'Total Revenue (RM)',
                    data: totalPrices,
                    backgroundColor: 'rgba(67, 56, 120, 0.2)',
                    borderColor: 'rgba(67, 56, 120, 0.7)',
                    borderWidth: 1
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
                        text: 'Drivers Monthly Revenue'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1000
                        },
                        title: {
                            display: true,
                            text: 'Revenue (RM)'
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

    <section class="report-section">
        <!-- Search Form -->
        <form class="search-form" onsubmit="return false;">
            <input 
                type="text" 
                id="search-input" 
                placeholder="Search..." 
                class="search-input" 
                oninput="filterDrivers()">
            <button type="button" class="search-button" onclick="filterDrivers()">
                <i class="fa fa-search" aria-hidden="true"></i>
            </button>
        </form>

        <!-- Driver List -->
        <div id="driver-list">
            <?php foreach ($drivers as $row): ?>
                <form method="POST" action="performance.php" class="driver-selection">
                    <button type="submit" name="driver_id" value="<?php echo htmlspecialchars($row['DRIVER_ID']); ?>">
                        <?php echo htmlspecialchars($row['DRIVER_NAME']); ?>
                    </button>
                </form>
            <?php endforeach; ?>
        </div>
    </section>

    <script>
        const drivers = <?php echo json_encode($drivers); ?>;

        function filterDrivers() {
            const searchValue = document.getElementById('search-input').value.toLowerCase();
            const driverList = document.getElementById('driver-list');

            driverList.innerHTML = '';

            drivers.filter(driver => 
                driver.DRIVER_NAME.toLowerCase().includes(searchValue)
            ).forEach(driver => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'performance.php';
                form.className = 'driver-selection';

                const button = document.createElement('button');
                button.type = 'submit';
                button.name = 'driver_id';
                button.value = driver.DRIVER_ID;
                button.textContent = driver.DRIVER_NAME;

                form.appendChild(button);
                driverList.appendChild(form);
            });

            if (driverList.innerHTML === '') {
                const noResults = document.createElement('p');
                noResults.textContent = 'No drivers found.';
                driverList.appendChild(noResults);
            }
        }
    </script>
</body>
<br><br><br><br><br><br><br><br>
</html>
