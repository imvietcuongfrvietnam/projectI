<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

// Kiểm tra nếu có mã giảng viên trong URL
if (isset($_GET['teacher_id'])) {
    $teacher_id = $_GET['teacher_id'];

    // Truy vấn lấy thông tin giảng viên
    $sql = "SELECT teacher_id, teacher_name, email, teacher_dob FROM teacher WHERE teacher_id = ?";
    $params = array($teacher_id);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("<p>Lỗi: " . print_r(sqlsrv_errors(), true) . "</p>");
    }

    $teacher = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    if (!$teacher) {
        die("<p>Không tìm thấy giảng viên với mã $teacher_id</p>");
    }

    sqlsrv_free_stmt($stmt);
} else {
    die("<p>Không có mã giảng viên được truyền.</p>");
}

// Xử lý cập nhật thông tin giảng viên
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $updated_teacher_id = $_POST['teacher_id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $dob = $_POST['teacher_dob'];

    // Truy vấn cập nhật
    $sql = "UPDATE teacher SET teacher_id = ?, teacher_name = ?, email = ?, teacher_dob = ? WHERE teacher_id = ?";
    $params = array($updated_teacher_id, $fullname, $email, $dob, $teacher_id);
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if (sqlsrv_execute($stmt)) {
        echo "<p>Cập nhật thông tin giảng viên thành công!</p>";
    } else {
        echo "<p>Lỗi: " . print_r(sqlsrv_errors(), true) . "</p>";
    }

    sqlsrv_free_stmt($stmt);

    // Reload dữ liệu mới sau khi cập nhật
    header("Location: cap_nhat_giang_vien.php?teacher_id=$updated_teacher_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật thông tin giảng viên</title>
    <link rel="stylesheet" href="../src/css/form.css">
</head>
<body>
<div class="form-container">
    <h2>Cập nhật thông tin giảng viên</h2>
    <form method="POST" action="">
        <label for="teacher_id">Mã giảng viên:</label>
        <input type="text" id="teacher_id" name="teacher_id" value="<?php echo htmlspecialchars($teacher['teacher_id']); ?>" required>
        <br><br>

        <label for="fullname">Họ và tên:</label>
        <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($teacher['teacher_name']); ?>" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($teacher['email']); ?>" required>
        <br><br>

        <label for="teacher_dob">Ngày sinh:</label>
        <input type="date" id="teacher_dob" name="teacher_dob" value="<?php echo $teacher['teacher_dob']->format('Y-m-d'); ?>" required>
        <br><br>

        <button type="submit">Cập nhật</button>
    </form>
</div>
<?php include_once "../footer.php"; ?>
</body>
</html>
