<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['ADMIN_ID'])) {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include('includes/navigation.php');?>
    <section>
        <h1 id="title">UTeM MAP</h1>

        <div class="section-header">
            <h4>Main Campus</h4>
            <a href="https://maps.app.goo.gl/LrF8vrxFEXCjS1n19" target="_blank" class="map-btn">View in Map</a>
        </div>
        <div class="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3986.5654305927296!2d102.31853611119003!3d2.313796997656234!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d1e46c6eaa869b%3A0xb8935957e3536888!2sUniversiti%20Teknikal%20Malaysia%20Melaka!5e0!3m2!1sen!2smy!4v1732175941848!5m2!1sen!2smy" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        <br>

        <div class="section-header">
            <h4>Technology Campus</h4>
            <a href="https://maps.app.goo.gl/kRQVAJq3zsu8Wms96" target="_blank" class="map-btn">View in Map</a>
        </div>
        <div class="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3986.665120775107!2d102.26999151118991!3d2.278061297692361!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d1e53cbadb27f3%3A0x47c7cce4a70dba9d!2sUTeM%20Holdings%20Sdn%20Bhd!5e0!3m2!1sen!2smy!4v1732176404916!5m2!1sen!2smy" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>
</body>
<br><br><br><br><br>
</html>