<?php 
session_start();
include('../connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['matrics']) && !empty($_POST['password'])) {
        // Get input data and sanitize
        $matrics = trim($_POST['matrics']);
        $password = trim($_POST['password']);

        // Prepare a secure SQL statement
        $stmt = $conn->prepare("SELECT * FROM driver WHERE DRIVER_MATRICS = ? AND DRIVER_STATUS IS NULL");
        $stmt->bind_param("s", $matrics);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $hashed_password = $user['DRIVER_PASSWORD'];

            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Store user information in the session
                $_SESSION['DRIVER_ID'] = $user['DRIVER_ID'];
                header("Location: index.php");
                exit;
            } else {
                echo "<script>
                        alert('Invalid matrics number or password');
                        window.location.href = 'login.html';
                      </script>";
            }
        } else {
            // Check if the user is banned
            $banned_stmt = $conn->prepare("SELECT * FROM driver WHERE DRIVER_MATRICS = ? AND DRIVER_STATUS = 'BANNED'");
            $banned_stmt->bind_param("s", $matrics);
            $banned_stmt->execute();
            $banned_result = $banned_stmt->get_result();

            if ($banned_result && $banned_result->num_rows === 1) {
                echo "<script>
                        alert('Your account has been banned. Please contact support for further assistance.');
                        window.location.href = 'login.html';
                      </script>";
            } else {
                echo "<script>
                        alert('Invalid matrics number or password');
                        window.location.href = 'login.html';
                      </script>";
            }

            $banned_stmt->close();
        }

        $stmt->close();
    } else {
        echo "<script>
                alert('Please fill in all fields.');
                window.location.href = 'login.html';
              </script>";
    }
}

$conn->close();
?>
