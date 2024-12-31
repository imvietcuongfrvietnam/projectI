<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

// Khởi tạo biến để chứa dữ liệu kỳ học
$semester_id = "";
$semester_name = "";
$begin_date = "";
$end_date = "";
$status = "";

// Kiểm tra nếu nhận được semester_id từ URL
if (isset($_GET['semester_id'])) {
    $semester_id = $_GET['semester_id'];

    // Truy vấn thông tin kỳ học
    $sql = "SELECT semester_name, begin_date, end_date, status FROM semester WHERE semester_id = ?";
    $params = array($semester_id);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Lấy dữ liệu kỳ học
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $semester_name = $row['semester_name'];
        $begin_date = $row['begin_date']->format('Y-m-d');
        $end_date = $row['end_date']->format('Y-m-d');
        $status = $row['status'];
    }

    sqlsrv_free_stmt($stmt);
}

// Xử lý cập nhật thông tin kỳ học khi form được gửi
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $semester_id = $_POST['semester_id'];
    $semester_name = $_POST['semester_name'];
    $begin_date = $_POST['begin_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];

    // Truy vấn cập nhật kỳ học
    $sql = "UPDATE semester SET semester_name = ?, begin_date = ?, end_date = ?, status = ? WHERE semester_id = ?";
    $params = array($semester_name, $begin_date, $end_date, $status, $semester_id);
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if (sqlsrv_execute($stmt)) {
        echo "<p>Cập nhật kỳ học thành công!</p>";
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
    <title>Cập nhật kỳ học</title>
    <link rel="stylesheet" href="../src/css/form.css"> <!-- Liên kết file CSS -->
</head>
<body>
<div class="form-container">
    <form method="POST" action="">
        <label for="semester_id">Mã kỳ học:</label>
        <input type="text" id="semester_id" name="semester_id" value="<?php echo htmlspecialchars($semester_id); ?>" readonly>
        <br><br>
        <label for="semester_name">Tên kỳ học:</label>
        <input type="text" id="semester_name" name="semester_name" value="<?php echo htmlspecialchars($semester_name); ?>" required>
        <br><br>
        <label for="begin_date">Ngày bắt đầu:</label>
        <input type="date" id="begin_date" name="begin_date" value="<?php echo htmlspecialchars($begin_date); ?>" required>
        <br><br>
        <label for="end_date">Ngày kết thúc:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" required>
        <br><br>
        <label for="status">Trạng thái:</label>
        <select id="status" name="status" required>
            <option value="Mở đăng ký" <?php echo $status === "Mở đăng ký" ? "selected" : ""; ?>>Mở đăng ký</option>
            <option value="Đang diễn ra" <?php echo $status === "Đang diễn ra" ? "selected" : ""; ?>>Đang diễn ra</option>
            <option value="Đã diễn ra" <?php echo $status === "Đã diễn ra" ? "selected" : ""; ?>>Đã diễn ra</option>
        </select>
        <br><br>
        <button type="submit">Cập nhật kỳ học</button>
    </form>
</div>
<?php include_once "../footer.php"; ?>
</body>
</html>
