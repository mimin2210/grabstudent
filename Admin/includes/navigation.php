<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
        <style>
            body {
                margin: 0;
                font-family: Arial, sans-serif;
                background-color: #f5f5f5;
            }

            .nav {
                display: flex;
                justify-content: space-around;
                align-items: center;
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                background-color: white;
                padding: 10px 20px;
                width: 90%;
                max-width: 600px;
                border-radius: 25px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                z-index: 1000;
            }

            .nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                color: #b4afcf;
                font-size: 12px;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .nav-item i {
                font-size: 24px;
                margin-bottom: 5px;
            }

            .nav-item.active {
                color: #48426D;
            }

            .nav-item.active i {
                color: #48426D;
                padding: 5px;
                border-radius: 50%;
            }
        </style>
    </head>
    <body>
        <?php
            $current_page = basename($_SERVER['PHP_SELF']);
        ?>
        <div class="nav">
            <a class="nav-item <?= $current_page === 'index.php' ? 'active' : '' ?>" href="index.php">
                <i class="fa fa-home" aria-hidden="true"></i>
                Home
            </a>
            <a class="nav-item <?= $current_page === 'map.php' ? 'active' : '' ?>" href="map.php">
                <i class="fa-solid fa-map" aria-hidden="true"></i>
                Map
            </a>
            <a class="nav-item <?= $current_page === 'driver_report.php' ? 'active' : '' ?>" href="driver_report.php">
                <i class="fa fa-star" aria-hidden="true"></i>
                Performance
            </a>
            <a class="nav-item <?= $current_page === 'report.php' ? 'active' : '' ?>" href="report.php">
                <i class="fa fa-flag" aria-hidden="true"></i>
                Report
            </a>
            <a class="nav-item <?= $current_page === 'profile.php' ? 'active' : '' ?>" href="profile.php">
                <i class="fa fa-user" aria-hidden="true"></i>
                Profile
            </a>
        </div>
    </body>
</html>
