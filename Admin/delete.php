<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['ADMIN_ID'])) {
    echo "<script>
            alert('You must be logged in to delete your account.');
            window.location.href = 'login.html';
          </script>";
    exit;
}

$admin_id = $_SESSION['ADMIN_ID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Update reports
  $update_report = $conn->prepare("UPDATE report SET REPORT_STATUS = '', ADMIN_ID = NULL WHERE ADMIN_ID = ?");
  $update_report->bind_param("i", $admin_id);
  if (!$update_report->execute()) {
    echo "Error updating reports: " . $update_report->error;
    exit;
  }
  $update_report->close();

  // Delete admin
  $delete_stmt = $conn->prepare("DELETE FROM admin WHERE ADMIN_ID = ?");
  $delete_stmt->bind_param("i", $admin_id);
  if ($delete_stmt->execute()) {
    echo "Admin account deleted successfully.";
    header("Location: login.html");
    exit;
  } else {
    echo "Error deleting admin account: " . $delete_stmt->error;
  }
  $delete_stmt->close();
}
$conn->close();
?>