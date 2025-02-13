<?php 
session_start();
include('../connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['BUILDING_ID'], $_POST['name'], $_POST['location'])) {
        $building_id = intval($_POST['BUILDING_ID']); 
        $building_name = htmlspecialchars($_POST['name']); 
        $building_location = htmlspecialchars($_POST['location']);
        $uploaded_file = $_FILES['building']; 

        $logo_file_name = null;

        if ($uploaded_file['error'] === UPLOAD_ERR_OK) {
            $target_dir = "../Images/Building/";
            $target_file = $target_dir . basename($uploaded_file['name']);

            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($file_type, $allowed_types)) {
                if (move_uploaded_file($uploaded_file['tmp_name'], $target_file)) {
                    $logo_file_name = htmlspecialchars(basename($uploaded_file['name']));
                } else {
                    echo "<script>alert('Failed to upload file.'); window.history.back();</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.'); window.history.back();</script>";
                exit;
            }
        }

        if ($logo_file_name) {
            $sql = "UPDATE building SET BUILDING_NAME = ?, BUILDING_LOCATION = ?, BUILDING_LOGO = ? WHERE BUILDING_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $building_name, $building_location, $logo_file_name, $building_id);
        } else {
            $sql = "UPDATE building SET BUILDING_NAME = ?, BUILDING_LOCATION = ? WHERE BUILDING_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $building_name, $building_location, $building_id);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Building details updated successfully!'); window.location.href='view_building.php';</script>";
        } else {
            echo "<script>alert('Failed to update building details.'); window.history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Invalid request. Please fill out all required fields.'); window.history.back();</script>";
    }
} else {
    header("Location: view_building.php");
    exit;
}
?>
