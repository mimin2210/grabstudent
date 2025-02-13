<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['ADMIN_ID'])) {
    header("Location: login.html");
    exit;
}

if (!isset($_GET['driver_id'])) {
    header("Location: warning.php");
    exit;
}

$driver_id = $_GET['driver_id'];

$stmt = $conn->prepare("SELECT DRIVER_NAME FROM driver WHERE DRIVER_ID = ?");
$stmt->bind_param("i", $driver_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Driver not found.";
    exit;
}

$driver = $result->fetch_assoc();
$driver_name = $driver['DRIVER_NAME'];


// Ban driver
$ban_stmt = $conn->prepare("UPDATE driver SET DRIVER_STATUS = 'BANNED' WHERE DRIVER_ID = ?");
$ban_stmt->bind_param("i", $driver_id);
if ($ban_stmt->execute()) {
    echo "<script>
    alert('Driver account banned successfully.');
    window.location.href = 'warning.php';
    </script>";
    exit;
} else {
    echo "Error banning driver account: " . $ban_stmt->error;
}
$ban_stmt->close();

?>
