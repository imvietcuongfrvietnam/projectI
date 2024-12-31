<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = $_POST['student_id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];

    // Truy vấn cập nhật thông tin sinh viên
    $sql = "UPDATE student SET student_name = ?, email = ?, student_dob = ? WHERE student_id = ?";
    $params = array($fullname, $email, $dob, $student_id);
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if (sqlsrv_execute($stmt)) {
        echo "<p>Cập nhật thông tin sinh viên thành công!</p>";
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
    <title>Cập nhật thông tin sinh viên</title>
    <link rel="stylesheet" href="../src/css/form.css"> <!-- Liên kết file CSS -->

</head>
<body>
<div class="form-container">
<form method="POST" action="">
    <label for="student_id">Nhập MSSV:</label>
    <input type="text" id="student_id" name="student_id" placeholder="Nhập Mã số sinh viên" required>
    <br><br>
    <label for="fullname">Họ và Tên:</label>
    <input type="text" id="fullname" name="fullname" placeholder="Nhập họ và tên" required>
    <br><br>
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" placeholder="Nhập email" required>
    <br><br>
    <label for="dob">Ngày sinh:</label>
    <input type="date" id="dob" name="dob" required>
    <br><br>
    <button type="submit">Cập nhật</button>
</form>
</div>
<?php include_once "../footer.php"; ?>
</body>
</html>
