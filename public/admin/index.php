<?php
require_once '../../includes/functions.php';
require_once '../../includes/session.php';

if (!$session->isLoggedIn()) { redirect_to("login.php"); }
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Gallery</title>
    <link href="../stylesheets/main.css" media="all" rel="stylesheet" type="text/css">
</head>
<body>
    <div id="header">
        <h1>Admin Area</h1>
    </div>
    <div id="main">
        <h1>Menu</h1>
        <?php echo output_message($message); ?>
        <ul>
            <li><a href="list_photos.php">List Photos</a></li>
            <li><a href="logfile.php">View log file</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div id="footer">Copyright <?php echo date("Y", time()); ?></div>
</body>
</html>
