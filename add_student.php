<?php
session_start();
include("db.php");

if (!isset($_SESSION['user']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$errors = [];
$courses = [];

$cstmt = mysqli_prepare($conn, "SELECT id, course_name FROM courses ORDER BY course_name ASC");
mysqli_stmt_execute($cstmt);
$cresult = mysqli_stmt_get_result($cstmt);
while ($c = mysqli_fetch_assoc($cresult)) {
    $courses[] = $c;
}

if (isset($_POST['add'])) {

    $student_id = trim($_POST['student_id'] ?? '');
    $fullname   = trim($_POST['fullname']   ?? '');
    $email      = trim($_POST['email']      ?? '');
    $course_id  = (int)($_POST['course_id'] ?? 0);

    if (empty($student_id))                          $errors[] = "Student ID is required.";
    if (empty($fullname))                            $errors[] = "Full Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))  $errors[] = "A valid email is required.";
    if ($course_id <= 0)                             $errors[] = "Please select a course.";

    if (empty($errors)) {
        $chk = mysqli_prepare($conn, "SELECT id FROM students WHERE student_id = ?");
        mysqli_stmt_bind_param($chk, "s", $student_id);
        mysqli_stmt_execute($chk);
        mysqli_stmt_store_result($chk);
        if (mysqli_stmt_num_rows($chk) > 0) {
            $errors[] = "Student ID already exists.";
        }
        mysqli_stmt_close($chk);
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO students (student_id, fullname, email, course_id) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssi", $student_id, $fullname, $email, $course_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>Add Student</h2>

<?php if (!empty($errors)): ?>
    <ul class="error">
        <?php foreach ($errors as $e): ?>
            <li><?php echo htmlspecialchars($e, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST">
    Student ID:
    <input type="text" name="student_id" maxlength="50"
           value="<?php echo htmlspecialchars($_POST['student_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required><br>

    Full Name:
    <input type="text" name="fullname" maxlength="100"
           value="<?php echo htmlspecialchars($_POST['fullname'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required><br>

    Email:
    <input type="email" name="email" maxlength="100"
           value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required><br>

    Course:
    <select name="course_id" required>
        <option value="">-- Select Course --</option>
        <?php foreach ($courses as $c): ?>
            <option value="<?php echo (int)$c['id']; ?>"
                <?php echo ((int)($_POST['course_id'] ?? 0) === (int)$c['id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($c['course_name'], ENT_QUOTES, 'UTF-8'); ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <button name="add">Add Student</button>
    <a href="dashboard.php">Cancel</a>
</form>
</div>
</body>
</html>
