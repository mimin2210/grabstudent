<?php
session_start();
include('../connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name'], $_POST['admin_matrics'], $_POST['phone'], $_POST['email'], $_POST['password'], $_POST['password2'])) {
        $admin_matrics = mysqli_real_escape_string($conn, $_POST['admin_matrics']);
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];

        if ($password !== $password2) {
            echo "<script>
                    alert('Passwords do not match. Please try again.');
                    window.location.href = 'signup.html';
                  </script>";
            exit;
        }

        $checkAdminSql = "SELECT * FROM admin WHERE ADMIN_MATRICS = '$admin_matrics'";
        $checkAdminResult = $conn->query($checkAdminSql);

        if ($checkAdminResult && $checkAdminResult->num_rows > 0) {
            echo "<script>
                    alert('An account with this Matrics/Staff number already exists.');
                    window.location.href = 'signup.html';
                  </script>";
            exit;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO admin (ADMIN_NAME, ADMIN_MATRICS, ADMIN_PHONE, ADMIN_EMAIL, ADMIN_PASSWORD) 
                VALUES ('$name', '$admin_matrics', '$phone', '$email', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    alert('Registration successful! You can now log in.');
                    window.location.href = 'login.html';
                  </script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    } else {
        echo "<script>
                alert('Please fill in all required fields.');
                window.location.href = 'signup.html';
              </script>";
    }
}
?>
