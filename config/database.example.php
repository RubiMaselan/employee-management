<?php
// Copy this file to config/database.php and fill in your own credentials.
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'employee_management';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
