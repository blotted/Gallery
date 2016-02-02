<?php
require_once '../../includes/functions.php';
require_once '../../includes/session.php';
require_once '../../includes/user.php';
require_once '../../includes/photo.php';
require_once '../../includes/config.php';
require_once '../../includes/database.php';



if (!$session->isLoggedIn()) { redirect_to("login.php"); }
$max_file_size = 1048576;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $photo = new Photo();
    $photo->caption = $_POST['caption'];
    $photo->attachFile($_FILES['file_upload']);
    if($photo->save()) {
        $session->message("Photo uploaded successfully.");
        redirect_to('list_photos.php');
    } else {
        $message = join("<br>", $photo->errors);
    }
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
        <h1>Admin Area</h1>
    </div>
    <div id="main">
      <h2>Photo Upload</h2>
      <a href="list_photos.php">Back to photo list</a><br>
      <a href="index.php">Back to menu</a>
      <?php echo output_message($message); ?>
      <form action="photo_upload.php" enctype="multipart/form-data" method="post">
          <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size; ?>">
	<p><input type="file" name="file_upload"></p>
	<p>Caption: <input type="text" name="caption" value=""></p>
	<input type="submit" value="Upload">
    </form>
    </div>
    <div id="footer">Copyright <?php echo date("Y", time()); ?></div>
</body>
</html>