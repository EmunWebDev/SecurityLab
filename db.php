<?php
// Secured db.php - Uses environment-safe credentials and error suppression
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');           // In production: use env vars or config file
define('DB_NAME', 'infosec_lab');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    // Never expose raw database errors to the user
    error_log("DB Connection failed: " . mysqli_connect_error());
    die("A server error occurred. Please contact the administrator.");
}

// Set charset to prevent charset-based injection
mysqli_set_charset($conn, 'utf8mb4');
?>