<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['DRIVER_ID'])) {
    header("Location: login.html");
    exit;
}

$sql = "SELECT DRIVER_PROFILE, DRIVER_NAME, DRIVER_MATRICS, DRIVER_PHONE, DRIVER_EMAIL, DRIVER_CAR
        FROM driver WHERE DRIVER_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['DRIVER_ID']);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $DRIVER = $result->fetch_assoc();
    $profile = $DRIVER['DRIVER_PROFILE'];
    $name = $DRIVER['DRIVER_NAME'];
    $matrics = $DRIVER['DRIVER_MATRICS'];
    $phone = $DRIVER['DRIVER_PHONE'];
    $email = $DRIVER['DRIVER_EMAIL'];
    $car = $DRIVER['DRIVER_CAR'];
} else {
    echo "No DRIVER data found.";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body id="body">
        <?php include('includes/navigation.php'); ?>
        <div class="profile-page">
            <h1 id="title">DRIVER PROFILE</h1>
            <section class="account">
                <div class="profile"><img src="../Images/Driver/<?php echo htmlspecialchars($profile); ?>"></div>
            </section>
            <div class="edit-profile">
                <a href="edit_profile.php" class="edit-button">Edit Profile <i class="fa-solid fa-pencil"></i></a>
            </div>

            <section class="settings">
                <table>
                    <tr>
                        <th colspan="2">Account</th>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td class="value"><?php echo htmlspecialchars($name); ?></td>
                    </tr>
                    <tr>
                        <td>Matrics</td>
                        <td class="value"><?php echo htmlspecialchars($matrics); ?></td>
                    </tr>
                    <tr>
                        <td>Phone No.</td>
                        <td class="value"><?php echo htmlspecialchars($phone); ?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td class="value"><?php echo htmlspecialchars($email); ?></td>
                    </tr>
                    <tr>
                        <td>Car</td>
                        <td class="value"><?php echo htmlspecialchars($car); ?></td>
                    </tr>
                </table>
                <table class="setting">
                    <tr>
                        <th colspan="2">Settings</th>
                    </tr>
                    <tr>
                        <td><a href="report.php"><i class="fa fa-star" aria-hidden="true"></i><p>Monthly Driving Report</p></a></td>
                    </tr>
                </table>
                <div class="info">
                    <form action="logout.php" method="post">
                        <button type="submit">
                            <i class="fa fa-sign-out" aria-hidden="true"></i>
                            <p>Logout</p>
                        </button>
                    </form>
                    <form action="delete.php" method="post" onsubmit="return confirm('Are you sure you want to delete your account?')">
                        <button type="submit">
                            <i class="fa fa-trash" style="color: red;" aria-hidden="true"></i>
                            <p style="color: red;">Delete Account</p>
                        </button>
                    </form>
                </div>
            </section>
        </div>
        <br><br><br>
    </body>
</html>