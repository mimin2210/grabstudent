<?php
session_start();
include('../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['order_id'], $_SESSION['DRIVER_ID'])) {
        $driver_id = $_SESSION['DRIVER_ID'];
        $order_id = $_POST['order_id'];
        
        $sql = "UPDATE ride_order SET ORDER_STATUS = 'DONE' WHERE ORDER_ID = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $stmt->close();
            header("Location: index.php");
            
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "Invalid order ID.";
    }
}

$conn->close();
?>
