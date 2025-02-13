<?php
session_start();
include('../connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['name'], $_POST['email'], $_POST['matrics'], $_POST['password'], $_POST['password2'], $_POST['tel'])) {
        $matrics = mysqli_real_escape_string($conn, $_POST['matrics']);
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $phone = mysqli_real_escape_string($conn, $_POST['tel']);

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>
                    alert('Invalid email format. Please provide a valid email address.');
                    window.location.href = 'signup.html';
                  </script>";
            exit;
        }

        // Check if passwords match
        if ($password !== $password2) {
            echo "<script>
                    alert('Passwords do not match. Please try again.');
                    window.location.href = 'signup.html';
                  </script>";
            exit;
        }

        // Check if the user already exists
        $checkUserSql = "SELECT * FROM driver WHERE DRIVER_MATRICS = '$matrics'";
        $checkUserResult = $conn->query($checkUserSql);

        if ($checkUserResult && $checkUserResult->num_rows > 0) {
            echo "<script>
                    alert('Account already exists with this Matrics/Staff number.');
                    window.location.href = 'signup.html';
                  </script>";
            exit;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the driver table
        $sql = "INSERT INTO driver (DRIVER_NAME, DRIVER_MATRICS, DRIVER_EMAIL, DRIVER_PASSWORD, DRIVER_PHONE) 
                VALUES ('$name', '$matrics', '$email', '$hashed_password', '$phone')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    alert('Registration successful! You can login now.');
                    window.location.href = 'login.html';
                  </script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close the connection
        $conn->close();
    } else {
        echo "<script>
                alert('Please fill in all required fields.');
                window.location.href = 'signup.html';
              </script>";
    }
}
?>
