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

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $author = trim($_POST['author']);
    $body = trim($_POST['body']);
    
    $new_comment = Comment::make($photo->id, $author, $body);
    if($new_comment && $new_comment->save()) {
        redirect_to("photo.php?id={$photo->id}");
    }else {
        $message = "Error.";
    }
} else {
    $author = '';
    $body = '';
}

$comments = $photo->comments();
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
        
        <div id="comments">
            <?php foreach ($comments as $comment): ?>
            <div class="comment" style="margin-bottom: 2em;">
                <div class="author">
                    <?php echo htmlentities($comment->author); ?> wrote:
                </div>
                <div class="body">
                    <?php echo strip_tags($comment->body, '<strong><em><p>'); ?>
                </div>
                <div class="meta-info" style="font-size: 0.8em;">
                    <?php echo datetime_to_text($comment->created); ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if(empty($comment)) { echo "No Comments."; } ?>
        </div>
        
        <div id="comment-form">
            <h3>New Comment</h3>
            <?php echo output_message($message); ?>
            <form action="photo.php?id=<?php echo $photo->id; ?>" method="post">
                <table>
                    <tr>
                        <td>Your name:</td>
                        <td><input type="text" name="author" value="<?php echo $author; ?>"></td>
                    </tr>
                    <tr>
                        <td>Your comment:</td>
                        <td><textarea name="body" cols="40" rows="8"><?php echo $body; ?></textarea></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type="submit" value="Submit Comment"></td>
                    </tr>
                </table>
            </form>
        </div> 
    </div>
    <div id="footer">Copyright <?php echo date("Y", time()); ?></div>
</body>
</html>