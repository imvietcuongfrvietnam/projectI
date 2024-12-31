<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $dob = $_POST['teacher_dob'];
    $teacher_id = $_POST['teacher_id'];

    // Truy vấn thêm giảng viên
    $sql = "INSERT INTO teacher (teacher_id, teacher_name, teacher_dob, email) VALUES (?, ?, ?, ?)";
    $params = array($teacher_id, $fullname, $dob, $email);
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if (sqlsrv_execute($stmt)) {
        echo "<p>Thêm giảng viên thành công!</p>";
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
    <title>Thêm giảng viên</title>
</head>
<body>
<form method="POST" action="">
    <label for="fullname">Họ và Tên:</label>
    <input type="text" id="fullname" name="fullname" placeholder="Nhập họ và tên" required>
    <br><br>
    <label for="email">Email cấp: </label>
    <input type="text" id="email" name="email" placeholder="Nhập email cấp cho giảng viên" required>
    <br><br>
    <label for="dob">Ngày sinh: </label>
    <input type="date" id="teacher_dob" name="teacher_dob" placeholder="Nhập ngày sinh giảng viên" required>
    <br><br>
    <label for="teacher_id">Mã giảng viên: </label>
    <input type="text" id="teacher_id" name="teacher_id" placeholder="Nhập mã giảng viên" required>
    <br><br>
    <button type="submit">Thêm giảng viên</button>
</form>
<?php include_once "../footer.php"; ?>
</body>
</html>
