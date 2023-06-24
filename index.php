<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/session.php';

try {
    $session = SessionManager::getCurrentSession();
} catch (Exception $exception) {
    header('Location: /jwt-php-native/login.php');
    exit(0);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
</head>
<body>
    <h1>Selamat datang <?= $session->name ?></h1>
</body>
</html>