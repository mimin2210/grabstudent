<?php 
session_start();
include('../connect.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['BUILDING_ID'])) {
    $building_id = intval($_GET['BUILDING_ID']);
    
    $stmt = $conn->prepare("SELECT * FROM building WHERE BUILDING_ID = ?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $building = $result->fetch_assoc();
    } else {
        echo "<script>alert('Building not found!'); window.location.href='view_building.php';</script>";
        exit;
    }
    $stmt->close();
} else {
    header("Location: view_building.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body id="body">
    <?php include('includes/navigation.php'); ?>
    <h1 id="title">EDIT UTEM BUILDING <i class="fa-solid fa-building"></i></h1>
    <section>
        <form class="ride-form" method="post" action="update_building.php" enctype="multipart/form-data">
            <input type="hidden" name="BUILDING_ID" value="<?php echo htmlspecialchars($building['BUILDING_ID']); ?>">

            <label for="name">Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($building['BUILDING_NAME']); ?>" required><br><br>

            <label for="location">Location</label>
            <select name="location">
                <option value="INDUK" <?php echo ($building['BUILDING_LOCATION'] == "INDUK" ? "selected" : ""); ?>>INDUK</option>
                <option value="TEKNOLOGI" <?php echo ($building['BUILDING_LOCATION'] == "TEKNOLOGI" ? "selected" : ""); ?>>TEKNOLOGI</option>
            </select><br><br>

            <label for="building">Logo</label>
            <input type="file" name="building"><br>
            <small>Current Logo:</small>
            <img src="../Images/Building/<?php echo htmlspecialchars($building['BUILDING_LOGO']); ?>" alt="Building Logo" style="max-width: 150px; display: block; margin-top: 10px;"><br><br>

            <input type="submit" value="Update">
        </form>
    </section><br><br><br>
</body>
</html>
