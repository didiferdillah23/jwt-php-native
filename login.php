<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/session.php';

$msg = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (SessionManager::login($_POST['username'], $_POST['password'])) {
        header('Location: /jwt-php-native/index.php');
        exit(0);
    } else {
        $msg = "Gagal Login";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LOGIN</title>
    </head>
<body>

<?php if ($msg) { ?>
    <i><?= $msg ?> </i>
<?php } ?>

    <h1>Login</h1>
    <form action="/jwt-php-native/login.php" method="post">
        <label>Username :</label>
        <br>
        <input type="text" name="username">
        <br>
        <label>Password :</label>
        <br>
        <input type="password" name="password">
        <br>
        <br>
        <button type="submit">Login</button>
    </form>

    </body>
</html>
