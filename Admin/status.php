<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['ADMIN_ID'])) {
    header("Location: login.html");
    exit;
}

$admin_id = $_SESSION['ADMIN_ID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_id = $_POST['report_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($report_id && in_array($action, ['accept', 'reject'])) {
        $status = strtoupper($action);
        $stmt = $conn->prepare("UPDATE report SET REPORT_STATUS = ?, ADMIN_ID = ? WHERE REPORT_ID = ?");
        $stmt->bind_param("sii", $status, $admin_id, $report_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Report updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update the report.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Invalid report or action.";
    }
}

header("Location: report.php");
exit;
?>
