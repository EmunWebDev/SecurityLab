<?php
// Secured delete_student.php
session_start();
include("db.php");

// 1. Access control – only authenticated users may delete
if (!isset($_SESSION['user']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Only accept POST – prevents CSRF via crafted GET links or image tags
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit();
}

// 3. Integer cast – prevents SQL Injection on the id parameter
$id = (int)($_POST['id'] ?? 0);

if ($id > 0) {
    // 4. Prepared statement
    $stmt = mysqli_prepare($conn, "DELETE FROM students WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header("Location: dashboard.php");
exit();
?>