<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $teacher_id = $_POST['teacher_id'];

    // Truy vấn xóa giảng viên
    $sql = "DELETE FROM teacher WHERE teacher_id = ?";
    $params = array($teacher_id);
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if (sqlsrv_execute($stmt)) {
        echo "<p>Xóa giảng viên thành công!</p>";
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
    <title>Xóa giảng viên</title>
    <link rel="stylesheet" href="../src/css/form.css"> <!-- Liên kết file CSS -->

</head>
<body>
<div class="form-container">
<form method="POST" action="">
    <label for="teacher_id">Nhập mã giảng viên cần xóa:</label>
    <input type="text" id="teacher_id" name="teacher_id" placeholder="Nhập mã giảng viên" required>
    <br><br>
    <button type="submit">Xóa giảng viên</button>
</form>
<?php include_once "../footer.php"; ?>
</div>
</body>
</html>
