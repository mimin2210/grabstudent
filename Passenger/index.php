<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['PASSENGER_ID'])) {
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
        <?php include('includes/navigation.php');?>
        <h1 id="title">GRAB STUDENT APP</h1>
        <section>
            <form class="ride-form" method="post" action="ride.php">
                <label for="current">Current Location</label>
                <?php
                    $sql = "SELECT BUILDING_NAME FROM building";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        echo '<select name="current" id="building" class="select2" required>';
                        echo '<option value="" disabled selected>Select a building</option>'; // Add a default option
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

                <label for="destination">Destination Location</label>
                <?php
                    $sql = "SELECT BUILDING_NAME FROM building";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        echo '<select name="destination" id="destination" class="select2" required>';
                        echo '<option value="" disabled selected>Select a destination</option>';
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

                <label for="pax">Number of Pax</label>
                <input type="number" name="pax" min="1" max="4" required>
                <br>

                <label for="date">Date of Ride</label>
                <input type="date" name="date" id="date" required>
                <br><br>

                <label for="time">Time of Ride</label>
                <input type="time" name="time" id="time" required>
                <br><br>

                <input type="submit" value="ORDER">
            </form>
        </section>
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: 'Search for a building',
                    allowClear: true
                });

                // Set the minimum date for the date input
                const today = new Date().toISOString().split('T')[0];
                $('#date').attr('min', today);

                // Update time input's min attribute based on the selected date
                $('#date').on('change', function() {
                    const selectedDate = $('#date').val();
                    const currentDate = new Date().toISOString().split('T')[0];
                    const timeInput = $('#time');
                    
                    if (selectedDate === currentDate) {
                        const now = new Date();
                        const currentHours = String(now.getHours()).padStart(2, '0');
                        const currentMinutes = String(now.getMinutes()).padStart(2, '0');
                        const currentTime = `${currentHours}:${currentMinutes}`;
                        timeInput.attr('min', currentTime);
                    } else {
                        timeInput.removeAttr('min');
                    }
                });

                // Trigger time input update if today's date is preselected
                const initialDate = $('#date').val();
                if (initialDate === today) {
                    $('#date').trigger('change');
                }
            });
        </script>

    </body>
</html>
