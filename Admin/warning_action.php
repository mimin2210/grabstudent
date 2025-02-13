<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['ADMIN_ID'])) {
    header("Location: login.html");
    exit;
}

// Check if PHPMailer is installed
if (!file_exists('../vendor/autoload.php')) {
    echo "<script>alert('PHPMailer is not installed. Please run: composer require phpmailer/phpmailer'); window.location.href = 'warning.php';</script>";
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if (isset($_GET['driver_id'])) {
    $driver_id = $_GET['driver_id'];

    // Fetch driver details
    $stmt = $conn->prepare("SELECT DRIVER_NAME, DRIVER_EMAIL FROM driver WHERE DRIVER_ID = ?");
    $stmt->bind_param("i", $driver_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $driver = $result->fetch_assoc();

        // Email content
        $subject = "Action Required: Reports Received";
        $body = "Dear " . htmlspecialchars($driver['DRIVER_NAME']) . ",\n\n" .
                "We have noticed that your account has received 3 or more accepted reports.\n" .
                "Please contact the administration for further assistance.\n\n" .
                "If not, then we will automatically block your account.\n\n" .
                "Best regards,\n" .
                "Grab Student App Admin Team";

        // Send email using PHPMailer
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email';
        $mail->Password = 'your_password';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('your_email', 'Grab Student App Admin');
        $mail->addAddress($driver['DRIVER_EMAIL']);
        $mail->Subject = $subject;
        $mail->Body = $body;

        if ($mail->send()) {
            $stmt1 = $conn->prepare("UPDATE driver SET DRIVER_WARNING = DRIVER_WARNING + 1 WHERE DRIVER_ID = ?");
            $stmt1->bind_param("i", $driver_id);
            $stmt1->execute();
            $stmt1->close();

            echo "<script>
            alert('Email sent successfully to " . htmlspecialchars($driver['DRIVER_NAME']) . "');
            window.location.href = 'warning.php';
            </script>";
        } else {
            echo "<script>alert('Failed to send email. Error: " . addslashes($mail->ErrorInfo) . "');</script>";
        }
    } else {
        echo "<script>alert('Driver not found.');</script>";
    }
} else {
    echo "<script>alert('Invalid request.');</script>";
}
