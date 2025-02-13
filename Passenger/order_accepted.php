<?php
session_start();
include('../connect.php');

// Ensure passenger is logged in
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

$sql = "SELECT ro.ORDER_FROM, ro.ORDER_TO, ro.ORDER_PAX, ro.ORDER_PRICE, ro.ORDER_DATE, ro.ORDER_TIME, d.DRIVER_NAME, d.DRIVER_MATRICS, d.DRIVER_PHONE, d.DRIVER_CAR
        FROM ride_order ro
        JOIN driver d ON ro.DRIVER_ID = d.DRIVER_ID
        WHERE ro.ORDER_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($current, $destination, $pax, $price, $date, $time, $driver_name, $driver_matrics, $phone, $driver_car);

if ($stmt->num_rows > 0) {
    $stmt->fetch();
} else {
    echo "<script>
    window.alert('Order not found or driver not assigned.');
    window.location.href = 'pending.php';
    </script>";
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
                    if (response.trim() === 'DONE') {
                        window.location.href = 'feedback.php';
                    }
                }
            });
        }

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
            <img src="../Images/Building/<?php echo htmlspecialchars($destination_logo) ?>" style="height:70px">
            <p>Ride order accepted, your driver will arrive shortly...</p>
        </div>

        <div class="contact">
            <a class="grey-box" href="tel:<?php echo htmlspecialchars($phone); ?>"><i class="fa fa-phone" aria-hidden="true" style="margin-right:15px"></i><p style="font-size:20px;color:white;font-weight:bold">CALL</p></a>
            <!-- <a class="grey-box" href="sms:<?php echo htmlspecialchars($phone); ?>"><i class="fa fa-comments" aria-hidden="true"></i></a> -->
            <a class="grey-box" href="https://wa.link/6jv00p" target="_blank"><i class="fa-brands fa-whatsapp" style="margin-right:15px"></i><p style="font-size:20px;color:white;font-weight:bold">WHATSAPP</p></a>
        </div>

        <div class="route-details">
            <p><strong><?php echo htmlspecialchars($current); ?></strong> ➡️ <strong><?php echo htmlspecialchars($destination); ?></strong></p>
            <p>Passengers: <?php echo htmlspecialchars($pax); ?> person</p>
            <p>Date: <?php echo htmlspecialchars($date); ?></p>
            <p>Time: <?php echo htmlspecialchars($time); ?></p>
            <p>Total: RM<?php echo htmlspecialchars($price); ?></p>


            <br><br>

            <p><strong>Driver: <?php echo htmlspecialchars($driver_name); ?></strong></p>
            <p>Matrics: <?php echo htmlspecialchars($driver_matrics); ?></p>
            <p>Car: <?php echo htmlspecialchars($driver_car); ?></p>
        </div>
    </section>
</body>
<br><br><br><br><br><br><br>
</html>
