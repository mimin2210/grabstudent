<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['ADMIN_ID'])) {
    header("Location: login.html");
    exit;
} else {
    $admin_id = $_SESSION['ADMIN_ID'];
}

$buildings = [];
$stmt = $conn->prepare("SELECT * FROM building");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $buildings[] = $row;
    }
} else {
    $error_message = "No buildings found.";
}

$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body id="body">
    <?php include('includes/navigation.php'); ?>
    <div class="view-building-section">
    <h1 id="title">UTEM BUILDING <i class="fa-solid fa-building"></i></h1>

    <section>
        <div class="ride-form">
            <a href="add_building.php" class="add-building">Add Building</a>
            <table class="building-table">
                <tr>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Logo</th>
                    <th>Action</th>
                </tr>
                <?php
                if (!empty($buildings)) {
                    foreach ($buildings as $building) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($building['BUILDING_NAME']) . "</td>";
                        echo "<td>" . htmlspecialchars($building['BUILDING_LOCATION']) . "</td>";
                        echo "<td><img src='../Images/Building/" . htmlspecialchars($building['BUILDING_LOGO']) . "' alt='Building Logo'></td>";
                        echo "<td class=\"actions\" style='color: #48426D;'>";
                        echo "<a href='edit_building.php?BUILDING_ID=" . urlencode($building['BUILDING_ID']) . "'><i class=\"fa-solid fa-pencil\"></i></a>";
                        echo " | ";
                        echo "<a href='delete_building.php?BUILDING_ID=" . urlencode($building['BUILDING_ID']) . "' onclick='return confirm(\"Are you sure you want to delete this building?\");'><i class=\"fa fa-trash\"></i></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No buildings found.</td></tr>";
                }
                ?>
            </table>
        </div>
    </section><br><br><br><br><br><br><br><br><br>
    </div>
</body>
</html>
