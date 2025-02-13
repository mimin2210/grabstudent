<?php 
session_start();
include('../connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['current']) && isset($_SESSION['DRIVER_ID'])) {
        $current = $_POST['current'];
        $driver_id = $_SESSION['DRIVER_ID'];

        $stmt = $conn->prepare("UPDATE driver SET DRIVER_CURRENT = ? WHERE DRIVER_ID = ?");
        $stmt->bind_param("si", $current, $driver_id);

        if ($stmt->execute()) {
            $message = "Your current location has been updated to: " . $current;
            echo "<script>
            alert('$message');
            window.location.href = 'order.php';
            </script>";
        } else {
            $error = $stmt->error;
            echo "<script>
            alert('Error updating location: $error');
            window.location.href = 'index.php';
            </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
        alert('Invalid request. Please try again.');
        window.location.href = 'index.php';
        </script>";
    }
}

$conn->close();
?>
