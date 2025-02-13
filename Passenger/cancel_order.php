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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['order_id'])) {
        $order_id = intval($_POST['order_id']);
        
        $sql = "DELETE FROM ride_order WHERE ORDER_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);

        if ($stmt->execute()) {
            unset($_SESSION['order_id']);
            echo "<script>
            window.alert('Order successfully canceled.');
            window.location.href = 'index.php';
            </script>";
        } else {
            echo "<script>
            window.alert('Failed to cancel the order. Please try again.');
            window.history.back();
            </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
        window.alert('Invalid order ID.');
        window.history.back();
        </script>";
    }
} else {
    echo "<script>
    window.alert('Invalid request.');
    window.history.back();
    </script>";
}

$conn->close();
?>
