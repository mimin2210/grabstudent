<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['DRIVER_ID'])) {
    echo "<script>
            alert('Please log in first.');
            window.location.href = 'login.html';
          </script>";
    exit;
}

$driver_id = $_SESSION['DRIVER_ID'];
$current_location = '';

$stmt = $conn->prepare("SELECT DRIVER_CURRENT FROM driver WHERE DRIVER_ID = ?");
$stmt->bind_param("i", $driver_id);
$stmt->execute();
$stmt->bind_result($current_location);
$stmt->fetch();
$stmt->close();

if (!$current_location) {
    echo "<script>
            alert('Unable to determine your current location. Please update it.');
            window.location.href = 'index.php';
          </script>";
    exit;
}

// Retrieve building logo based on the current location
$stmt = $conn->prepare("SELECT BUILDING_LOGO FROM building WHERE BUILDING_NAME = ?");
$stmt->bind_param("s", $current_location);
$stmt->execute();
$stmt->bind_result($building_logo);
$stmt->fetch();
$stmt->close();

$orders = [];
$stmt = $conn->prepare("SELECT * FROM ride_order WHERE ORDER_FROM = ? AND ORDER_STATUS IS NULL");
$stmt->bind_param("s", $current_location);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

$stmt->close();

foreach ($orders as $index => $order) {
    $passenger_id = $order['PASSENGER_ID']; 
    $stmt = $conn->prepare("SELECT PASSENGER_PROFILE FROM passenger WHERE PASSENGER_ID = ?");
    $stmt->bind_param("i", $passenger_id);
    $stmt->execute();
    $stmt->bind_result($passenger_profile);
    $stmt->fetch();
    $stmt->close();
    
    // Add the passenger profile to the specific order by index
    $orders[$index]['PASSENGER_PROFILE'] = $passenger_profile;
}


$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            
        </style>
    </head>
    
    <body id="body">
        <?php include('includes/navigation.php'); ?>

        <section>
            <div class="map-container">
                <p>You're currently at <?php echo htmlspecialchars($current_location) ?></p>
                <img src="../Images/Building/<?php echo htmlspecialchars($building_logo) ?>" style="height:70px;" alt="Current Location">
            </div>

            <div class="request-list" style="justify-content:center;">
                <?php if (empty($orders)): ?>
                    <p style="text-align: center; color: #777;">No orders available.</p>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="request-card">
                            <img src="../Images/Passenger/<?php echo htmlspecialchars($order['PASSENGER_PROFILE']) ?>" alt="passenger Profile" style="height: 70px; width: 70px; border-radius: 50%;border: 1px solid black">
                            <div class="request-info" style="margin-left:35px; margin-right:35px;">
                                <p><strong><?php echo htmlspecialchars($order['ORDER_FROM']) ?> > <?php echo htmlspecialchars($order['ORDER_TO']) ?></strong></p><br>
                                <p>Ride Date: <?php echo htmlspecialchars($order['ORDER_DATE']) ?></p>
                                <p>Ride Time: <?php echo htmlspecialchars($order['ORDER_TIME']) ?></p>
                                <p>Number of pax(s): <?php echo htmlspecialchars($order['ORDER_PAX']) ?></p>
                                <p>Total price: RM <?php echo htmlspecialchars($order['ORDER_PRICE']) ?></p>
                            </div> 
                            <div class="action-buttons">
                                <button class="accept" onclick="handleAction(<?php echo $order['ORDER_ID'] ?>, 'accept')">ACCEPT</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        <script>
            function handleAction(orderId, action) {
                const url = `accepted_order.php?order_id=${orderId}&action=${action}`;
                window.location.href = url;
            }
        </script>
    </body>
</html>
