<?php
session_start();
include('../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);

    $sql = "SELECT ORDER_STATUS FROM ride_order WHERE ORDER_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->bind_result($order_status);
    $stmt->fetch();
    $stmt->close();

    echo $order_status;
}

$conn->close();
?>
