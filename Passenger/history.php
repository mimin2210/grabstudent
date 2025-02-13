<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['PASSENGER_ID'])) { 
    header("Location: login.html");
    exit;
}

$passenger_id = $_SESSION['PASSENGER_ID'];

$query = "
    SELECT ro.ORDER_FROM, ro.ORDER_TO, ro.ORDER_PAX, ro.ORDER_PRICE, d.DRIVER_NAME, 
           bf.BUILDING_LOGO AS FROM_LOGO, bt.BUILDING_LOGO AS TO_LOGO
    FROM ride_order ro
    JOIN driver d ON ro.DRIVER_ID = d.DRIVER_ID
    JOIN building bf ON bf.BUILDING_NAME = ro.ORDER_FROM
    JOIN building bt ON bt.BUILDING_NAME = ro.ORDER_TO
    WHERE ro.PASSENGER_ID = ? AND ro.ORDER_STATUS = 'DONE'
    ORDER BY ro.ORDER_DATE DESC
";


$stmt = $conn->prepare($query);
$stmt->bind_param('i', $passenger_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php include('includes/navigation.php'); ?>
    <h1 id="title">RIDE HISTORY <i class="fa fa-history" aria-hidden="true"></i></h1>
    <section class="historyy">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="history">
                    <div class="from-image">
                        <img src="../Images/Building/<?php echo htmlspecialchars($row['FROM_LOGO']); ?>" alt="From Building">
                    </div>
                    <div class="ride-details">
                        <p><?php echo htmlspecialchars($row['ORDER_FROM']); ?> TO <?php echo htmlspecialchars($row['ORDER_TO']); ?></p>
                        <p>Driver: <?php echo htmlspecialchars($row['DRIVER_NAME']); ?></p>
                        <p>Number of Pax: <?php echo htmlspecialchars($row['ORDER_PAX']); ?></p>
                        <p>Total Price: <?php echo htmlspecialchars($row['ORDER_PRICE']); ?></p>
                    </div>
                    <div class="to-image">
                        <img src="../Images/Building/<?php echo htmlspecialchars($row['TO_LOGO']); ?>" alt="To Building">
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No ride history available.</p>
        <?php endif; ?>
    </section>
</body>
<br><br><br><br><br><br><br><br>
</html>
