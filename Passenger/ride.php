<?php 
session_start();
include('../connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['current'], $_POST['destination'], $_SESSION['PASSENGER_ID'])) {
        $current = $_POST['current'];
        $destination = $_POST['destination'];
        $pax = $_POST['pax'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $passenger_id = $_SESSION['PASSENGER_ID'];

        error_log("Current: $current, Destination: $destination, Pax: $pax, Date: $date, Time: $time");

        $sql = $conn->prepare("SELECT BUILDING_LOCATION FROM building WHERE BUILDING_NAME IN (?, ?)");
        $sql->bind_param("ss", $current, $destination);
        $sql->execute(); 
        $result = $sql->get_result();

        $locations = [];
        while ($row = $result->fetch_assoc()) {
            $locations[] = $row['BUILDING_LOCATION'];
        }
        $sql->close();

        if (count($locations) === 2) {
            $location_current = $locations[0];
            $location_destination = $locations[1];

            if ($location_current === "INDUK" && $location_destination === "INDUK") {
                $price = 4;
            } elseif ($location_current === "TEKNOLOGI" && $location_destination === "TEKNOLOGI") {
                $price = 2;
            } elseif (($location_current === "INDUK" && $location_destination === "TEKNOLOGI") ||
                      ($location_current === "TEKNOLOGI" && $location_destination === "INDUK")) {
                $price = 7;
            } else {
                $price = 0;
            }

            $stmt = $conn->prepare("
                INSERT INTO ride_order (ORDER_FROM, ORDER_TO, ORDER_PAX, ORDER_PRICE, ORDER_DATE, ORDER_TIME, PASSENGER_ID) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("ssisssi", $current, $destination, $pax, $price, $date, $time, $passenger_id);

            if ($stmt->execute()) {
                $_SESSION['order_id'] = $conn->insert_id;

                $message = "Order added successfully";
                echo "<script>
                window.alert('" . $message . "');
                window.location.href = 'pending.php';
                </script>";
            } else {
                $message = "Error: " . $stmt->error;
                echo "<script>
                window.alert('" . $message . "');
                window.location.href = 'index.php';
                </script>";
            }

            $stmt->close();
        } else {
            echo "<script>
            window.alert('Invalid locations. Please try again.');
            window.location.href = 'index.php';
            </script>";
        }
    } else {
        echo "<script>
        window.alert('Invalid input or session. Please try again.');
        window.location.href = 'index.php';
        </script>";
    }
}

$conn->close();
?>
