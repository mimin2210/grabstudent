<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['ADMIN_ID'])) {
    header("Location: login.html");
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
        <?php include('includes/navigation.php');?>
        <h1 id="title">ADD UTEM BUILDING <i class="fa-solid fa-building"></i></h1>
        <section>
            <form class="ride-form" method="post" action="add_building2.php" enctype="multipart/form-data">
                <label for="subject">Name</label>
                <input type="text" name="name" required><br>
                <br>

                <label for="subject">Location</label>
                <select name="location">
                    <option value="INDUK">INDUK</option>
                    <option value="TEKNOLOGI">TEKNOLOGI</option>
                </select>
                <br>

                <label for="reportfile">Logo</label>
                <input type="file" name="building">


                <input type="submit" value="ADD">
            </form>
        </section><br><br><br>
    </body>
</html>