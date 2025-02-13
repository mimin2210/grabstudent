<?php
session_start();
include('../connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['admin_matrics']) && isset($_POST['password'])) {
        $admin_matrics = mysqli_real_escape_string($conn, $_POST['admin_matrics']);
        $password = $_POST['password'];

        $sql = "SELECT * FROM admin WHERE ADMIN_MATRICS = '$admin_matrics'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows == 1) {
            $admin = $result->fetch_assoc();
            $hashed_password = $admin['ADMIN_PASSWORD'];

            if (password_verify($password, $hashed_password)) {
                $_SESSION['admin_matrics'] = $admin_matrics;
                $_SESSION['ADMIN_ID'] = $admin['ADMIN_ID'];
                header("Location: index.php");
                exit;
            } else {
                echo "<script>
                        alert('Invalid Matrics or Password');
                        window.location.href = 'login.html';
                      </script>";
                exit;
            }
        } else {
            echo "<script>
                    alert('Invalid Matrics or Password');
                    window.location.href = 'login.html';
                  </script>";
            exit;
        }
    }
}

$conn->close();
?>
