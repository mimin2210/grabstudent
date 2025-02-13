<?php 
session_start();
include('../connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = mysqli_real_escape_string($conn, trim($_POST['username']));
        $password = trim($_POST['password']);

        $stmt = $conn->prepare("SELECT * FROM passenger WHERE PASSENGER_USERNAME = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $hashed_password = $user['PASSENGER_PASSWORD'];

            if (password_verify($password, $hashed_password)) {
                $_SESSION['PASSENGER_ID'] = $user['PASSENGER_ID'];

                header("Location: index.php");
                exit;
            } else {
                echo "<script>
                        alert('Invalid username or password');
                        window.location.href = 'login.html';
                      </script>";
                exit;
            }
        } else {
            echo "<script>
                    alert('Invalid username or password');
                    window.location.href = 'login.html';
                  </script>";
            exit;
        }
    } else {
        echo "<script>
                alert('Please enter both username and password');
                window.location.href = 'login.html';
              </script>";
        exit;
    }
}

$conn->close();
?>
