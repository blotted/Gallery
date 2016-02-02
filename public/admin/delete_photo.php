<?php
require_once '../../includes/functions.php';
require_once '../../includes/session.php';
require_once '../../includes/user.php';
require_once '../../includes/photo.php';
require_once '../../includes/config.php';
require_once '../../includes/database.php';

if(!$session->isLoggedIn()) { redirect_to("login.php"); }

if(empty($_GET['id'])) {
    $session->message('No photo id was provided.');
    redirect_to('index.php');
}

$photo = Photo::findById($_GET['id']);

if($photo && $photo->destroy()) {
    $session->message("The photo {$photo->filename} was deleted.");
    redirect_to('list_photos.php');
} else {
    $session->message('The photo could not be deleted.');
    redirect_to('list_photos.php');
}

if(isset($database)) { $database->closeConnection; }