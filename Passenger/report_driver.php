<?php 
session_start();
include('../connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['date'], $_POST['time'], $_POST['driver'], $_POST['subject'], $_POST['comment'], $_SESSION['PASSENGER_ID'])) {
        $date = $_POST['date'];
        $time = $_POST['time'];
        $driver_id = $_POST['driver'];
        $subject = $_POST['subject'];
        $comment = $_POST['comment'];

        $passenger_id = $_SESSION['PASSENGER_ID'];

        $reportfile_path = NULL;

        if (isset($_FILES['reportfile']) && $_FILES['reportfile']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['reportfile']['error'] === UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['reportfile']['tmp_name'];
                $file_name = basename($_FILES['reportfile']['name']);
                $upload_dir = 'uploads/';
                $upload_path = $upload_dir . $file_name;

                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $reportfile_path = $upload_path;
                } else {
                    $reportfile_path = NULL;
                }
            } else {
                $message = "Error in file upload: " . $_FILES['reportfile']['error'];
                echo "<script>
                window.alert('" . $message . "');
                window.location.href = 'report.php';
                </script>";
                exit;
            }
        }

        $stmt = $conn->prepare("INSERT INTO report (RIDE_DATE, RIDE_TIME, REPORT_SUBJECT, REPORT_COMMENT, REPORT_FILE, PASSENGER_ID, DRIVER_ID) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssii", $date, $time, $subject, $comment, $reportfile_path, $passenger_id, $driver_id);

        if ($stmt->execute()) {
            $message = "Report added successfully";
        } else {
            $message = "Error: " . $stmt->error;
        }
        

        $stmt->close();

        echo "<script>
        window.alert('" . $message . "');
        window.location.href = 'report.php';
        </script>";
    } else {
        echo "<script>
        window.alert('Invalid input or session. Please try again.');
        window.location.href = 'report.php';
        </script>";
    }
}

$conn->close();
?>
