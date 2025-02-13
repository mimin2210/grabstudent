<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['DRIVER_ID'])) {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    </head>
    
    <body id="body">
        <?php include('includes/navigation.php'); ?>
        <h1 id="title">GRAB STUDENT APP</h1>
        <section>
            <form class="ride-form" method="post" action="drive.php">
                <label for="availabilty">Availability</label>
                <label class="switch">
                    <input type="checkbox" name="availability" required>
                    <span class="slider round"></span>
                </label>
                <br><br>

                <label for="current">Current Location</label><br>
                <?php
                    $sql = "SELECT BUILDING_NAME FROM building";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        echo '<select name="current" id="building" class="select2">';
                        echo '<option value="" disabled selected>Select your current building</option>'; // Add placeholder option
                        while ($building = $result->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($building['BUILDING_NAME']) . '">'
                                . htmlspecialchars($building['BUILDING_NAME']) . '</option>';
                        }
                        echo '</select><br>';
                    } else {
                        echo 'No buildings found.';
                    }
                ?>

                <br>

                <input type="submit" value="VIEW ORDER">
            </form>
        </section>
    </body>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Search for a building',
                allowClear: true
            });
        });
    </script>
</html>
