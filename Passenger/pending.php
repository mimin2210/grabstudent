<?php 
session_start();
include('../connect.php');

if (!isset($_SESSION['PASSENGER_ID'])) {
    echo "<script>
    window.alert('Please log in first.');
    window.location.href = 'login.html';
    </script>";
    exit;
}

if (!isset($_SESSION['order_id'])) {
    echo "<script>
    window.alert('No active order found. Please place an order.');
    window.location.href = 'index.php';
    </script>";
    exit;
}

$order_id = $_SESSION['order_id'];

// Fetch order details
$sql = "SELECT * FROM ride_order WHERE ORDER_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
    $current = $order['ORDER_FROM'];
    $destination = $order['ORDER_TO'];
    $pax = $order['ORDER_PAX'];
    $price = $order['ORDER_PRICE'];
    $date = $order['ORDER_DATE'];
    $time = $order['ORDER_TIME'];
} else {
    echo "Order not found.";
    exit;
}

$stmt->close();

$stmt = $conn->prepare("SELECT BUILDING_LOGO FROM building WHERE BUILDING_NAME = ?");
$stmt->bind_param("s", $current);
$stmt->execute();
$stmt->bind_result($current_logo);
$stmt->fetch();
$stmt->close();

if (!$current_logo) {
    $current_logo = 'default.png';
}

$stmt = $conn->prepare("SELECT BUILDING_LOGO FROM building WHERE BUILDING_NAME = ?");
$stmt->bind_param("s", $destination);
$stmt->execute();
$stmt->bind_result($destination_logo);
$stmt->fetch();
$stmt->close();

if (!$destination_logo) {
    $destination_logo = 'default.png';
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Function to poll the server for order status
        function checkOrderStatus() {
            $.ajax({
                url: 'check_order.php',
                method: 'POST',
                data: { order_id: <?php echo $order_id; ?> },
                success: function(response) {
                    if (response.trim() === 'PENDING') {
                        window.location.href = 'order_accepted.php';
                    }
                }
            });
        }

        // Poll the server every 5 seconds
        setInterval(checkOrderStatus, 5000);
    </script>
</head>

<body id="body">
    <?php include('includes/navigation.php'); ?>
    <h1 id="title">GRAB STUDENT APP</h1>
    <section class="pending-page">
        <div class="map-container">
            <img src="../Images/Building/<?php echo htmlspecialchars($current_logo) ?>" style="height:70px">
            ⬇️
            <img src="../Images/Building/<?php echo htmlspecialchars($destination_logo) ?>" style="height:70px"><br>
            <p>Hold on while we reach your driver...</p>
        </div>

        <div class="route-details">
            <p><strong><?php echo htmlspecialchars($current); ?></strong> ➡️ <strong><?php echo htmlspecialchars($destination); ?></strong></p>
            <p>Passengers: <?php echo htmlspecialchars($pax); ?></p>
            <p>Price: RM<?php echo htmlspecialchars($price); ?></p>
            <p>Date: <?php echo htmlspecialchars($date); ?></p>
            <p>Time: <?php echo htmlspecialchars($time); ?></p>
        </div>
        <form action="cancel_order.php" method="post">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
            <input type="submit" class="cancel-button" value="CANCEL ORDER">
        </form>
    </section>
</body>
</html>
