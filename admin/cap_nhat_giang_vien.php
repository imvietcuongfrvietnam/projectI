<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $teacher_id = $_POST['teacher_id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $dob = $_POST['teacher_dob'];

    // Truy vấn cập nhật thông tin giảng viên
    $sql = "UPDATE teacher SET teacher_name = ?, email = ?, teacher_dob = ? WHERE teacher_id = ?";
    $params = array($fullname, $email, $dob, $teacher_id);
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if (sqlsrv_execute($stmt)) {
        echo "<p>Cập nhật thông tin giảng viên thành công!</p>";
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
    <title>Cập nhật thông tin giảng viên</title>
</head>
<body>
<form method="POST" action="">
    <label for="teacher_id">Nhập Mã Giảng Viên:</label>
    <input type="text" id="teacher_id" name="teacher_id" placeholder="Nhập mã giảng viên" required>
    <br><br>
    <label for="fullname">Họ và Tên:</label>
    <input type="text" id="fullname" name="fullname" placeholder="Nhập họ và tên" required>
    <br><br>
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" placeholder="Nhập email" required>
    <br><br>
    <label for="teacher_dob">Ngày sinh:</label>
    <input type="date" id="teacher_dob" name="teacher_dob" required>
    <br><br>
    <button type="submit">Cập nhật</button>
</form>
<?php include_once "../footer.php"; ?>
</body>
</html>
