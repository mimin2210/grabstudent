<?php 
session_start();
include('../connect.php');

if (isset($_GET['order_id'], $_GET['action'], $_SESSION['DRIVER_ID'])) {
    $order_id = intval($_GET['order_id']);
    $action = $_GET['action'];
    $driver_id = $_SESSION['DRIVER_ID'];

    if ($action === 'accept') {
        $stmt = $conn->prepare("UPDATE ride_order SET ORDER_STATUS = ?, DRIVER_ID = ? WHERE ORDER_ID = ?");
        $stmt->bind_param("sii", $ride_status, $driver_id, $order_id);
        $ride_status = 'PENDING';
        $stmt->execute();
        $stmt->close();
    }

    $sql = "SELECT * FROM ride_order WHERE ORDER_ID = $order_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();

        $passenger = $order['PASSENGER_ID'];
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

    $orders = [];
    $stmt = $conn->prepare("SELECT * FROM ride_order WHERE ORDER_FROM = ? AND ORDER_STATUS=''");
    $stmt->bind_param("s", $current_location);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    $stmt->close();

    $sql = "SELECT PASSENGER_PHONE FROM passenger WHERE PASSENGER_ID = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $passenger);
        $stmt->execute();
        $stmt->bind_result($phone);
        $stmt->fetch();
        $stmt->close();
    }

} else {
    echo "No order found. Please place an order.";
    exit;
}


?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    
    <body id="body">
        <?php include('includes/navigation.php');?>
        <h1 id="title">GRAB STUDENT APP</h1>
        <section>
            <div class="map-container">
                <img src="../Images/Building/<?php echo htmlspecialchars($current_logo) ?>" style="height:70px">
                ⬇️
                <img src="../Images/Building/<?php echo htmlspecialchars($destination_logo) ?>" style="height:70px">
                <p>Accepted order...</p>
            </div>

            <div class="contact">
                <a class="grey-box" href="tel:<?php echo htmlspecialchars($phone); ?>"><i class="fa fa-phone" aria-hidden="true" style="margin-right:15px"></i><p style="font-size:20px;color:white;font-weight:bold">CALL PASSENGER</p></a>
            </div>

            <div class="route-details">
                <p><strong><?php echo htmlspecialchars($current); ?></strong> ➡️ <strong><?php echo htmlspecialchars($destination); ?></strong></p>
                <p>Passengers: <?php echo htmlspecialchars($pax); ?> person</p>
                <p>Date: <?php echo htmlspecialchars($date); ?></p>
                <p>Time: <?php echo htmlspecialchars($time); ?></p>
                <p>Total: RM<?php echo htmlspecialchars($price); ?></p>
            </div>
            <form method="post" action="done.php" class="done-form">
                <p>Click the DONE button only when passenger have paid</p>
                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
                <input type="submit" class="done-button" value="DONE ORDER">
            </form>
        </section>
    </body>
    <br><br><br><br><br><br><br>
</html>