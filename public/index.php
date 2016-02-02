<?php
require_once '../includes/functions.php';
require_once '../includes/database.php';
require_once '../includes/databaseObject.php';
$photos = Photo::findALL();
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
    <?php foreach ($photos as $photo): ?>
        <div style="float: left; margin-left: 20px;">
            <a href="photo.php?id=<?php echo $photo->id; ?>">
                <img src="<?php echo $photo->imagePath(); ?>" width="200">
            </a>
            <p><?php echo $photo->caption; ?></p>
        </div>
    <?php endforeach; ?>
    </div>
    <div id="footer">Copyright <?php echo date("Y", time()); ?></div>
</body>
</html>