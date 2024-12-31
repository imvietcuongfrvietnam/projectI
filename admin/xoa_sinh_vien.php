<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = $_POST['student_id'];

    // Truy vấn xóa sinh viên
    $sql = "DELETE FROM student WHERE student_id = ?";
    $params = array($student_id);
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if (sqlsrv_execute($stmt)) {
        echo "<p>Xóa sinh viên thành công!</p>";
    } else {
        echo "<p>Lỗi: " . print_r(sqlsrv_errors(), true) . "</p>";
    }

    sqlsrv_free_stmt($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa sinh viên</title>
    <link rel="stylesheet" href="../src/css/form.css"> <!-- Liên kết file CSS -->
</head>
<body>
<div class="form-container">
    <form method="POST" action="">
        <label for="student_id">Nhập MSSV cần xóa:</label>
        <input type="text" id="student_id" name="student_id" placeholder="Nhập Mã số sinh viên" required>

        <button type="submit">Xóa sinh viên</button>
    </form>
</div>
<?php include_once "../footer.php"; ?>
</body>
</html>
