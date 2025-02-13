<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['DRIVER_ID'])) {
    echo "<script>
            alert('You must be logged in to delete your account.');
            window.location.href = 'login.html';
          </script>";
    exit;
}

$driver_id = $_SESSION['DRIVER_ID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  // Delete feedback
  $delete_feedback = $conn->prepare("DELETE FROM feedback WHERE DRIVER_ID = ?");
  $delete_feedback->bind_param("i", $driver_id);
  if (!$delete_feedback->execute()) {
      echo "Error deleting feedback: " . $delete_feedback->error;
      exit;
  }
  $delete_feedback->close();

  // Delete reports
  $delete_report = $conn->prepare("DELETE FROM report WHERE DRIVER_ID = ?");
  $delete_report->bind_param("i", $driver_id);
  if (!$delete_report->execute()) {
      echo "Error deleting reports: " . $delete_report->error;
      exit;
  }
  $delete_report->close();

  // Delete ride orders
  $delete_ride = $conn->prepare("DELETE FROM ride_order WHERE DRIVER_ID = ?");
  $delete_ride->bind_param("i", $driver_id);
  if (!$delete_ride->execute()) {
      echo "Error deleting rides: " . $delete_ride->error;
      exit;
  }
  $delete_ride->close();

  // Delete driver
  $delete_stmt = $conn->prepare("DELETE FROM driver WHERE DRIVER_ID = ?");
  $delete_stmt->bind_param("i", $driver_id);
  if ($delete_stmt->execute()) {
      echo "Driver account deleted successfully.";
      header("Location: login.html");
      exit;
  } else {
      echo "Error deleting driver account: " . $delete_stmt->error;
  }
  $delete_stmt->close();
}

$conn->close();
?>