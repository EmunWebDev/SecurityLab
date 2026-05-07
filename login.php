<?php
session_start();
session_regenerate_id(true);

include("db.php");

$error = "";

if (isset($_POST['login'])) {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, username, password FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user']    = htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8');
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['ip']      = $_SERVER['REMOTE_ADDR'];
                $_SESSION['ua']      = $_SERVER['HTTP_USER_AGENT'];

                header("Location: dashboard.php");
                exit();
            }
        }

        $error = "Invalid username or password.";
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Admin Login</h2>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <input type="text"     name="username" placeholder="Username" required maxlength="100"><br>
        <input type="password" name="password" placeholder="Password" required maxlength="255"><br>
        <button name="login">Login</button>
    </form>
</div>
</body>
</html>