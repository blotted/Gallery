<?php
require_once '../includes/functions.php';
require_once '../includes/pagination.php';
require_once '../includes/database.php';
require_once '../includes/databaseObject.php';

$page = !empty($_GET['page']) ? (int) $_GET['page'] : 1;
$per_page = 3;
$total_count = Photo::countAll();
//$photos = Photo::findALL();

$pagination = new Pagination($page, $per_page, $total_count);
$sql = "SELECT * FROM photo ";
$sql .= "LIMIT {$per_page} ";
$sql .= "OFFSET {$pagination->offset()}";
$photos = Photo::findBySql($sql);
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
        
        <div id="pagination" style="clear: both;">
        <?php
        if($pagination->totalPages() >1) {
            
            if($pagination->hasPreviousPage()) {
                echo " <a href=\"index.php?page=";
                echo $pagination->previousPage();
                echo "\">&laquo; Previous</a> ";
            }
            
            for($i=1; $i <= $pagination->totalPages(); $i++) {
                if($i == $page){
                    echo " <span class=\"selected\">{$i}</span> ";
                } else {
                    echo " <a href=\"index.php?page={$i}\">{$i}</a> ";
                }
            }
            
            if($pagination->hasNextPage()) {
                echo " <a href=\"index.php?page=";
                echo $pagination->nextPage();
                echo "\">Next &raquo;</a> ";
            }
            
        }
        ?>
        </div>
    </div>
    <div id="footer">Copyright <?php echo date("Y", time()); ?></div>
</body>
</html>