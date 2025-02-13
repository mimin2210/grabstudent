<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['PASSENGER_ID'])) {
    header("Location: login.html");
    exit;
}

if (!isset($_SESSION['order_id'])) {
    echo "<script>
    window.alert('No active order found. Please place an order.');
    window.location.href = 'index.php';
    </script>";
    exit;
}

$order = (int)$_SESSION['order_id'];

$stmt = $conn->prepare("SELECT DRIVER_ID FROM ride_order WHERE ORDER_ID = ?");
$stmt->bind_param("i", $order);
$stmt->execute();

$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $driver_id = $row['DRIVER_ID'];
} else {
    $driver_id = null;
}

$stmt->close();

$select_date = $conn->prepare("SELECT ORDER_DATE FROM ride_order WHERE ORDER_ID = ?");
$select_date->bind_param("i", $order);
if (!$select_date->execute()) {
    die("Execution failed: " . $select_date->error);
}
$result = $select_date->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $date = $row['ORDER_DATE'];
} else {
    echo "No records found.";
}
$select_date->close();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['rating']) && $_POST['rating'] >= 1 && $_POST['rating'] <= 5) {
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
        $passenger_id = $_SESSION['PASSENGER_ID'];

        $sql = "INSERT INTO feedback (DRIVER_ID, PASSENGER_ID, FEEDBACK_RATING, FEEDBACK_COMMENT, FEEDBACK_DATE) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiss", $driver_id, $passenger_id, $rating, $comment, $date);
        if ($stmt->execute()) {
            header("Location: index.php");
            
        } else {
            echo "Error submitting your feedback!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .stars {
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .star {
            font-size: 30px;
            color: gray;
            cursor: pointer;
        }

        .star.selected {
            color: gold;
        }
    </style>
</head>

<body id="body">
    <?php include('includes/navigation.php'); ?>
    <h1 id="title">FEEDBACK <i class="fa fa-star" aria-hidden="true"></i></h1>
    <section>
        <form class="ride-form" method="post" action="" enctype="multipart/form-data">
            <div class="stars" id="stars">
                <i class="fa fa-star star" data-value="1" aria-hidden="true"></i>
                <i class="fa fa-star star" data-value="2" aria-hidden="true"></i>
                <i class="fa fa-star star" data-value="3" aria-hidden="true"></i>
                <i class="fa fa-star star" data-value="4" aria-hidden="true"></i>
                <i class="fa fa-star star" data-value="5" aria-hidden="true"></i>
            </div>
            <div>
                <br>
                <label id="comment">Comment:</label>
                <textarea name="comment" value="" placeholder="Please suggest the improvement that can be done"></textarea>
            </div>

            <input type="hidden" name="rating" id="rating">

            <script>
                const stars = document.querySelectorAll('.star');
                const ratingInput = document.getElementById('rating');

                stars.forEach(star => {
                    star.addEventListener('click', () => {
                        const rating = star.getAttribute('data-value');
                        updateStars(rating);
                        ratingInput.value = rating;
                    });
                });

                function updateStars(rating) {
                    stars.forEach(star => {
                        if (star.getAttribute('data-value') <= rating) {
                            star.classList.add('selected');
                        } else {
                            star.classList.remove('selected');
                        }
                    });
                }
            </script>
            
            <input type="submit" value="SUBMIT">
        </form>
    </section><br><br><br>
</body>
</html>
