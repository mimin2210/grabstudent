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

        <!-- Include Select2 CSS and JS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    </head>
    
    <body id="body">
        <?php include('includes/navigation.php'); ?>
        <h1 id="title">REPORT A DRIVER <i class="fa fa-flag" aria-hidden="true"></i></h1>
        <section>
            <form class="ride-form" method="post" action="report_driver.php" enctype="multipart/form-data">
                
                <label for="date">Date of Ride</label>
                <input type="date" name="date" id="date" required>
                <br><br>
                
                <label for="time">Time of Ride</label>
                <input type="time" name="time" id="time" required>
                <br><br>

                <label for="driver">Choose a Driver</label>
                <?php
                    $sql = "SELECT DRIVER_ID, DRIVER_NAME FROM driver";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        echo '<select name="driver" id="driver" class="select2">';
                        echo '<option value="" disabled selected>Select a driver</option>'; // Add placeholder option
                        while ($driver = $result->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($driver['DRIVER_ID']) . '">'
                                . htmlspecialchars($driver['DRIVER_NAME']) . '</option>';
                        }
                        echo '</select><br>';
                    } else {
                        echo 'No drivers found.';
                    }
                ?>
                <br>
                
                <label for="subject">Subject of Report / Topic</label>
                <input type="text" name="subject" placeholder="" required><br>
                <br>

                <label for="comment">Comments</label>
                <textarea id="comment" name="comment" rows="4" required></textarea>
                <br>

                <label for="reportfile">Upload File (optional)</label>
                <input type="file" name="reportfile">

                <p>Your report will be sent to the admin to be reviewed. A driver with at least 3 successful reports will be banned from using this app.</p>
                <input type="submit" value="REPORT">
            </form>
            <script>
                $(document).ready(function() {
                    $('.select2').select2({
                        placeholder: 'Search for a driver',
                        allowClear: true
                    });

                    const today = new Date().toISOString().split('T')[0];
                    $('#date').attr('max', today);

                    $('#date').on('change', function() {
                        const selectedDate = $('#date').val();
                        const currentDate = new Date().toISOString().split('T')[0];
                        const timeInput = $('#time');

                        if (selectedDate === currentDate) {
                            const now = new Date();
                            const currentHours = String(now.getHours()).padStart(2, '0');
                            const currentMinutes = String(now.getMinutes()).padStart(2, '0');
                            const currentTime = `${currentHours}:${currentMinutes}`;
                            timeInput.attr('max', currentTime);
                        } else {
                            timeInput.removeAttr('max');
                        }
                    });

                    $('#date').trigger('change');
                });
            </script>
        </section>
    </body>
    <br><br><br><br><br>
</html>
