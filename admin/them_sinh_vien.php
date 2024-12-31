<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $dob = $_POST['student_dob']; // Đúng tên biến student_dob
    $student_id = $_POST['student_id'];

    // Truy vấn thêm sinh viên
    $sql = "INSERT INTO student (student_id, student_name, student_dob, email) VALUES (?, ?, ?, ?)";
    $params = array($student_id, $fullname, $dob, $email);
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if (sqlsrv_execute($stmt)) {
        echo "<p>Thêm sinh viên thành công!</p>";
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
    <title>Thêm sinh viên</title>
    <link rel="stylesheet" href="../src/css/form.css">
</head>
<body>
<div class="form-container">
    <form method="POST" action="">
        <label for="fullname">Họ và Tên:</label>
        <input type="text" id="fullname" name="fullname" placeholder="Nhập họ và tên" required>

        <label for="email">Email cấp:</label>
        <input type="text" id="email" name="email" placeholder="Nhập email cấp cho sinh viên" required>

        <label for="dob">Ngày sinh:</label>
        <input type="date" id="student_dob" name="student_dob" required>

        <label for="student_id">Mã số sinh viên cấp:</label>
        <input type="text" id="student_id" name="student_id" placeholder="Nhập mã sinh viên cấp" required>

        <button type="submit">Thêm sinh viên</button>
    </form>
</div>
</body>
</html>
<?php include_once "../footer.php"; ?>
