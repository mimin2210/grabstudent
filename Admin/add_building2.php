<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['ADMIN_ID'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $location = isset($_POST['location']) ? mysqli_real_escape_string($conn, $_POST['location']) : '';

    if (isset($_FILES['building']) && $_FILES['building']['error'] === UPLOAD_ERR_OK) {
        $uploadedFile = $_FILES['building'];
        $fileName = basename($uploadedFile['name']);
        $fileTmpPath = $uploadedFile['tmp_name'];
        $fileSize = $uploadedFile['size'];
        $fileType = $uploadedFile['type'];

        $uploadDir = '../Images/Building/';
        $destPath = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $logoPath = $fileName;

            $stmt = $conn->prepare("INSERT INTO building (BUILDING_NAME, BUILDING_LOCATION, BUILDING_LOGO) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $location, $logoPath);

            if ($stmt->execute()) {
                $message = "Building added successfully";
            } else {
                $message = "Error adding building: " . $stmt->error;
            }
    
            $stmt->close();
    
            echo "<script>
            window.alert('" . $message . "');
            window.location.href = 'add_building.php';
            </script>";

        } else {
            echo "Error moving the uploaded file.";
        }
    } else {
        echo "No file uploaded or an error occurred during file upload.";
    }
}
?>
