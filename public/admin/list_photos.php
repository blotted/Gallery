<?php
require_once '../../includes/functions.php';
require_once '../../includes/session.php';
require_once '../../includes/user.php';
require_once '../../includes/photo.php';
require_once '../../includes/config.php';
require_once '../../includes/database.php';

if (!$session->isLoggedIn()) { redirect_to("login.php"); }
$photos = Photo::findALL();
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
      <h2>Photos</h2>
      <?php echo output_message($message); ?>
      <table class="bordered">
          <tr>
              <th>Image</th>
              <th>Filename</th>
              <th>Caption</th>
              <th>Size</th>
              <th>Type</th>
              <th>&nbsp;</th>
          </tr>
    <?php foreach ($photos as $photo): ?> 
          <tr>
              <td><img src="../<?php echo $photo->imagePath(); ?>" width="100"></td>
              <td><?php echo $photo->filename; ?></td>
              <td><?php echo $photo->caption; ?></td>
              <td><?php echo $photo->sizeAsText(); ?></td>
              <td><?php echo $photo->type; ?></td>
              <td><a href="delete_photo.php?id=<?php echo $photo->id; ?>">Delete</a></td>
          </tr>
    <?php endforeach; ?>      
      </table>
      <br>
      <a href="photo_upload.php">Upload a new photo</a><br>
      <a href="index.php">Back to menu</a>
    </div>
    <div id="footer">Copyright <?php echo date("Y", time()); ?></div>
</body>
</html>