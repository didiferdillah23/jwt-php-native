<?php
require_once 'vendor/autoload.php';
require_once __DIR__ . '/../connection.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

class Session
{
    public function __construct(public string $username, public string $name)
    {}
}

class SessionManager
{
    public static function login(string $username, string $password): bool
    {
        // load file .env
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        // mendapatkan koneksi databse
        $conn = getDatabaseConnection();
        
        // query untuk mengecek keberadaan user di database
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            
            $user = mysqli_fetch_assoc($result);

            $session_id = md5(rand());

            // update session_id pada data users yang dengan login
            $queryUpdateSessionId = "UPDATE users SET session_id = '{$session_id}' WHERE id = {$user['id']}";
            mysqli_query($conn, $queryUpdateSessionId);

            // membuat payload untuk JWT
            $payload = [
                "username" => $user["username"],
                "name" => $user["name"],
                "session_id" => $session_id
            ];

            // Membuat JWT dan menyimpannya di cookie
            $jwt = JWT::encode($payload, $_ENV['SECRET_KEY'], 'HS256');
            setcookie("DD-SESSION", $jwt);

            return true;
        } else {
            return false;
        }
    }

    public static function getCurrentSession(): Session
    {
        // load file .env
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        // mendapatkan koneksi databse
        $conn = getDatabaseConnection();
        
        // mendapatkan cookie yang berisi JWT
        if($_COOKIE['DD-SESSION']){
            $jwt = $_COOKIE['DD-SESSION'];

            try {
                // pastikan algoritma yang digunakan sama, dalam hal ini HS256
                $payload = JWT::decode($jwt, new Key($_ENV['SECRET_KEY'], 'HS256'));
                
                // cek session id dan username di database
                $query = "SELECT * FROM users WHERE username = '$payload->username' AND session_id = '$payload->session_id'";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    // return username & name yang ada di JWT, name akan kita tampilkan di halaman index
                    return new Session(username: $payload->username, name: $payload->name);
                } else {
                    throw new Exception("Anda belum login");
                }

            }catch (Exception $exception){
                throw new Exception("Anda belum login");
            }
        }else{
            throw new Exception("Anda belum login");
        }
    }

}