<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['ADMIN_ID'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['BUILDING_ID'])) {
    $building_id = intval($_GET['BUILDING_ID']);

    $stmt = $conn->prepare("DELETE FROM building WHERE BUILDING_ID = ?");
    $stmt->bind_param("i", $building_id);

    if ($stmt->execute()) {
        echo "<script>alert('Building deleted successfully!'); window.location.href='view_building.php';</script>";
    } else {
        echo "<script>alert('Error deleting building! Please try again.'); window.location.href='view_building.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: view_building.php");
    exit;
}
?>
