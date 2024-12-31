<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

// Kiểm tra nếu có MSSV trong URL
if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    // Truy vấn lấy thông tin sinh viên
    $sql = "SELECT student_id, student_name, email, student_dob FROM student WHERE student_id = ?";
    $params = array($student_id);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("<p>Lỗi: " . print_r(sqlsrv_errors(), true) . "</p>");
    }

    $student = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    if (!$student) {
        die("<p>Không tìm thấy sinh viên với MSSV $student_id</p>");
    }

    sqlsrv_free_stmt($stmt);
} else {
    die("<p>Không có MSSV được truyền.</p>");
}

// Xử lý cập nhật thông tin sinh viên
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $updated_student_id = $_POST['student_id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];

    // Truy vấn cập nhật
    $sql = "UPDATE student SET student_id = ?, student_name = ?, email = ?, student_dob = ? WHERE student_id = ?";
    $params = array($updated_student_id, $fullname, $email, $dob, $student_id);
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if (sqlsrv_execute($stmt)) {
        echo "<p>Cập nhật thông tin sinh viên thành công!</p>";
    } else {
        echo "<p>Lỗi: " . print_r(sqlsrv_errors(), true) . "</p>";
    }

    sqlsrv_free_stmt($stmt);

    // Reload dữ liệu mới sau khi cập nhật
    header("Location: cap_nhat_sinh_vien.php?student_id=$updated_student_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật thông tin sinh viên</title>
    <link rel="stylesheet" href="../src/css/form.css">
</head>
<body>
<div class="form-container">
    <h2>Cập nhật thông tin sinh viên</h2>
    <form method="POST" action="">
        <label for="student_id">MSSV:</label>
        <input type="text" id="student_id" name="student_id" value="<?php echo htmlspecialchars($student['student_id']); ?>" required>
        <br><br>

        <label for="fullname">Họ và tên:</label>
        <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($student['student_name']); ?>" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
        <br><br>

        <label for="dob">Ngày sinh:</label>
        <input type="date" id="dob" name="dob" value="<?php echo $student['student_dob']->format('Y-m-d'); ?>" required>
        <br><br>

        <button type="submit">Cập nhật</button>
    </form>
</div>
<?php include_once "../footer.php"; ?>
</body>
</html>
