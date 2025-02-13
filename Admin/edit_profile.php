<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['ADMIN_ID'])) {
    header("Location: login.html");
    exit;
}

$sql = "SELECT * FROM admin WHERE ADMIN_ID = ?";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body id="body">
    <?php include('includes/navigation.php');?>
    <h1 id="title">UPDATE YOUR PROFILE</h1>
    <section>
        <form class="ride-form" method="post" action="update_profile.php" enctype="multipart/form-data">
            
            <label for="name">Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>

            <label for="phone">Phone</label>
            <input type="tel" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required><br><br>

            <label for="email">Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>

            <label for="profile">Profile Picture</label>
            <input type="file" name="profile"><br>
            <?php if (!empty($profile)): ?>
                <small>Current Profile Picture:</small><br>
                <img src="../Images/Admin/<?php echo htmlspecialchars($profile); ?>" alt="Admin Profile Picture" style="max-width: 150px; display: block; margin-top: 10px;"><br><br>
            <?php else: ?>
                <p>No profile picture uploaded.</p>
            <?php endif; ?>

            <input type="submit" value="Update">
        </form>
    </section><br><br><br>
</body>
</html>
