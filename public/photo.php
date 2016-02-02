<?php
require_once '../includes/functions.php';
require_once '../includes/session.php';
require_once '../includes/user.php';
require_once '../includes/photo.php';
require_once '../includes/config.php';
require_once '../includes/database.php';

if(empty($_GET['id'])) {
    $session->message('No photo id was provided.');
    redirect_to('index.php');
}

$photo = Photo::findById($_GET['id']);
if(!$photo) {
    $session->message('The photo could not be located.');
    redirect_to('index.php');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Gallery</title>
    <link href="stylesheets/main.css" media="all" rel="stylesheet" type="text/css">
</head>
<body>
    <div id="header">
        <h1>Gallery</h1>
    </div>
    <div id="main">
        <a href="index.php">&laquo; Back</a><br>
        <br>
        
        <div style="margin-left: 20px;">
            <img src="<?php echo $photo->imagePath(); ?>">
            <p><?php echo $photo->caption; ?></p>
        </div>
    </div>
    <div id="footer">Copyright <?php echo date("Y", time()); ?></div>
</body>
</html>