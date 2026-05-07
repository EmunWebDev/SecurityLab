<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');           
define('DB_NAME', 'infosec_lab');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    error_log("DB Connection failed: " . mysqli_connect_error());
    die("A server error occurred. Please contact the administrator.");
}

mysqli_set_charset($conn, 'utf8mb4');
?>
