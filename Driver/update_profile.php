<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['DRIVER_ID'])) {
    header("Location: login.html");
    exit;
}

$driver_id = $_SESSION['DRIVER_ID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $car = htmlspecialchars($_POST['car']);

    $profile = null;
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['profile']['tmp_name'];
        $file_name = basename($_FILES['profile']['name']);
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($file_extension), $allowed_extensions)) {
            // Generate a unique file name
            $new_file_name = "driver_" . $driver_id . "." . $file_extension;
            // Set the upload path
            $upload_path = "../Images/Driver/" . $new_file_name;

            if (move_uploaded_file($file_tmp_path, $upload_path)) {
                $profile = $new_file_name;
            } else {
                echo "Error uploading the profile picture.";
                exit;
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
            exit;
        }
    }

    $sql = "UPDATE driver SET DRIVER_NAME = ?, DRIVER_PHONE = ?, DRIVER_EMAIL = ?, DRIVER_CAR = ?, DRIVER_PROFILE = IFNULL(?, DRIVER_PROFILE) WHERE DRIVER_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $name,  $phone, $email, $car, $profile, $driver_id);

    if ($stmt->execute()) {
        header("Location: profile.php?update=success");
        exit;
    } else {
        echo "Error updating profile: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
