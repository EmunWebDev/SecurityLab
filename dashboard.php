<?php
session_start();
include("db.php");

if (!isset($_SESSION['user']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (
    isset($_SESSION['ip']) && $_SESSION['ip'] !== $_SERVER['REMOTE_ADDR'] ||
    isset($_SESSION['ua']) && $_SESSION['ua'] !== $_SERVER['HTTP_USER_AGENT']
) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$stmt = mysqli_prepare($conn, "SELECT s.id, s.student_id, s.fullname, s.email, c.course_name 
                                FROM students s
                                JOIN courses c ON s.course_id = c.id
                                ORDER BY s.id ASC");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8'); ?></h2>

<a href="add_student.php">Add Student</a> |
<a href="logout.php">Logout</a>

<h3>Student List</h3>

<table border="1">
<tr>
    <th>ID</th>
    <th>Student ID</th>
    <th>Full Name</th>
    <th>Email</th>
    <th>Course</th>
    <th>Action</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)): ?>
<tr>
    <td><?php echo htmlspecialchars($row['id'],         ENT_QUOTES, 'UTF-8'); ?></td>
    <td><?php echo htmlspecialchars($row['student_id'], ENT_QUOTES, 'UTF-8'); ?></td>
    <td><?php echo htmlspecialchars($row['fullname'],   ENT_QUOTES, 'UTF-8'); ?></td>
    <td><?php echo htmlspecialchars($row['email'],      ENT_QUOTES, 'UTF-8'); ?></td>
    <td><?php echo htmlspecialchars($row['course_name'],ENT_QUOTES, 'UTF-8'); ?></td>
    <td>
        <form method="POST" action="delete_student.php"
              onsubmit="return confirm('Delete this student?');">
            <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
            <button type="submit">Delete</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>

</table>
</body>
</html>
