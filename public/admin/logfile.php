<?php
require_once '../../includes/functions.php';
require_once '../../includes/session.php';

if (!$session->isLoggedIn()) {
    redirect_to("login.php");
}

$logfile = "../../logs/log.txt";
$check = isset($_GET['clear']) ? 'true' : 'false';

if($check == "true") {
    file_put_contents($logfile, '');
    log_action('Logs Cleared', "by User Id {$session->user_id}");
    redirect_to("logfile.php");
}
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
        <h1>Gallery</h1>
    </div>
    <div id="main">
        <a href="index.php">&laquo; Back</a><br>
        <br>
        <h2>Log File</h2>
        <p><a href="logfile.php?clear=true">Clear log file</a></p>
<?php 
if (file_exists($logfile) && is_readable($logfile) && $handle = fopen($logfile, 'r')) {
    echo "<ul class=\"log-entries\">";
    while (!feof($handle)) {
        $entry = fgets($handle);
        if(trim($entry) != "") {
            echo "<li>{$entry}</li>";
        }
    }
    echo "</ul>";
    fclose($handle);
} else {
    echo "Could not red from {$logfile}.";
}
?>
    </div>
    <div id="footer">Copyright <?php echo date("Y", time()); ?></div>
</body>
</html>