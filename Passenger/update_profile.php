<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['PASSENGER_ID'])) {
    header("Location: login.html");
    exit;
}

$passenger_id = $_SESSION['PASSENGER_ID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $matrics = htmlspecialchars($_POST['matrics']);
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
            $new_file_name = "passenger_" . $passenger_id . "." . $file_extension;
            $upload_path = "../Images/Passenger/" . $new_file_name;

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

    $sql = "UPDATE passenger SET PASSENGER_NAME = ?, PASSENGER_PHONE = ?, PASSENGER_EMAIL = ?, PASSENGER_PROFILE = IFNULL(?, PASSENGER_PROFILE) WHERE PASSENGER_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $phone, $email, $profile, $passenger_id);

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
