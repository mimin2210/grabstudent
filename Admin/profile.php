<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['ADMIN_ID'])) {
    header("Location: login.html");
    exit;
}

$sql = "SELECT ADMIN_PROFILE, ADMIN_NAME, ADMIN_MATRICS, ADMIN_PHONE, ADMIN_EMAIL
        FROM admin WHERE ADMIN_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['ADMIN_ID']);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $ADMIN = $result->fetch_assoc();
    $profile = $ADMIN['ADMIN_PROFILE'];
    $name = $ADMIN['ADMIN_NAME'];
    $matrics = $ADMIN['ADMIN_MATRICS'];
    $phone = $ADMIN['ADMIN_PHONE'];
    $email = $ADMIN['ADMIN_EMAIL'];
} else {
    echo "No ADMIN data found.";
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
            <h1 id="title">ADMIN PROFILE</h1>
            <section class="account">
                <div class="profile"><img src="../Images/Admin/<?php echo htmlspecialchars($profile); ?>"></div>
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
                </table>
                <table class="setting">
                    <tr>
                        <th colspan="2">Settings</th>
                    </tr>
                    <tr>
                        <td><a href="view_building.php"><i class="fa-solid fa-building" aria-hidden="true"></i><p>View UTEM Building</p></a></td>
                    </tr>
                    <tr>
                        <td><a href="signup.html"><i class="fa-solid fa-user"></i><p>Add Admin</p></a></td>
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
    </body>
    <br><br><br><br><br><br>
</html>