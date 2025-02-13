<?php
session_start();
include('../connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name'], $_POST['tel'], $_POST['email'], $_POST['username'], $_POST['password'], $_POST['password2'])) {
        $name = $_POST['name'];
        $phone = mysqli_real_escape_string($conn, $_POST['tel']);
        $email = $_POST['email'];
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = $_POST['password'];
        $password2 = $_POST['password2'];

        if ($password !== $password2) {
            echo "<script>
                    alert('Passwords do not match. Please try again.');
                    window.location.href = 'signup.html';
                  </script>";
            exit;
        }

        $checkUserSql = "SELECT * FROM passenger WHERE PASSENGER_USERNAME = '$username'";
        $checkUserResult = $conn->query($checkUserSql);

        if ($checkUserResult && $checkUserResult->num_rows > 0) {
            echo "<script>
                    alert('Account already exists with this username.');
                    window.location.href = 'signup.html';
                  </script>";
            exit;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO passenger (PASSENGER_NAME, PASSENGER_PHONE, PASSENGER_EMAIL, PASSENGER_USERNAME, PASSENGER_PASSWORD) 
                VALUES ('$name', '$phone', '$email', '$username', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    alert('Registration successful!');
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
