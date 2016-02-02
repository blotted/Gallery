<?php
require_once '../../includes/functions.php';
require_once '../../includes/session.php';
require_once '../../includes/database.php';
require_once '../../includes/databaseObject.php';
require_once '../../includes/user.php';

if($session->isLoggedIn()) {
    redirect_to("index.php");
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim(strip_tags($_POST['username']));
    $password = trim(strip_tags($_POST['password']));
    
   $found_user = User::authenticate($username, $password);
    
    if ($found_user) {
        $session->login($found_user);
        log_action('Login', "{$found_user->username} logged in.");
        redirect_to("index.php");
    } else {
        $message = "Username/password incorrect.";
    }
} else {
    $username = "";
    $password = "";
    $message = "";
}
?>

<!DOCTYPE html>
<html lang="RU">
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
            <h2>Staff Login</h2>
            <?php echo output_message($message); ?>

            <form action="login.php" method="post">
                <table>
                    <tr>
                        <td>Username:</td>
                        <td>
                            <input type="text" name="username" maxlength="30" value="<?php echo htmlentities($username); ?>">
                        </td>
                    </tr>
                        <tr>
                            <td>Password:</td>
                            <td>
                                <input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="submit" value="Login">
                            </td>
                        </tr>
                </table>
            </form>
        </div>
        <div id="footer">
            Copyright <?php echo date("Y", time()); ?>
        </div>
    </body>
</html>

<?php if(isset($database)) { $database->closeConnection(); }?>