<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/connection.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

// load file .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// mendapatkan koneksi databse
$conn = getDatabaseConnection();

// mendapatkan jwt & payload
$jwt = $_COOKIE['DD-SESSION'];
$payload = JWT::decode($jwt, new Key($_ENV['SECRET_KEY'], 'HS256'));
                
// update session_id menjadi null
$query = "UPDATE users SET session_id = null WHERE username = '{$payload->username}'";
$result = mysqli_query($conn, $query);

// menghapus value cookie
setcookie('DD-SESSION', '');

// redirect ke login
header('Location: /jwt-php-native/login.php');