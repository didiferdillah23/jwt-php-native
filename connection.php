<?php

function getDatabaseConnection()
{
    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'belajar_php_jwt';

    $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

    if (!$conn) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    return $conn;
}
