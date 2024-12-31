<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

// Kiểm tra nếu có mã môn học trong URL
if (isset($_GET['subject_id'])) {
    $subject_id = $_GET['subject_id'];

    // Truy vấn lấy thông tin môn học
    $sql = "SELECT subject_name, credit FROM subject WHERE subject_id = ?";
    $params = array($subject_id);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("<p>Lỗi: " . print_r(sqlsrv_errors(), true) . "</p>");
    }

    $subject = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    if (!$subject) {
        die("<p>Không tìm thấy môn học với mã $subject_id</p>");
    }

    sqlsrv_free_stmt($stmt);
} else {
    die("<p>Không có mã môn học được truyền.</p>");
}

// Xử lý cập nhật thông tin môn học
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $updated_subject_id = $_POST['subject_id'];
    $subject_name = $_POST['subject_name'];
    $credit = $_POST['credit'];

    // Truy vấn cập nhật thông tin môn học
    $sql = "UPDATE subject SET subject_name = ?, credit = ? WHERE subject_id = ?";
    $params = array($subject_name, $credit, $updated_subject_id);
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if (sqlsrv_execute($stmt)) {
        echo "<p>Cập nhật môn học thành công!</p>";
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
    <title>Cập nhật môn học</title>
    <link rel="stylesheet" href="../src/css/form.css"> <!-- Liên kết file CSS -->
</head>
<body>
<div class="form-container">
    <h2>Cập nhật thông tin môn học</h2>
    <form method="POST" action="">
        <label for="subject_id">Mã môn học:</label>
        <input type="text" id="subject_id" name="subject_id" value="<?php echo htmlspecialchars($subject_id); ?>" readonly required>
        <br><br>

        <label for="subject_name">Tên môn học:</label>
        <input type="text" id="subject_name" name="subject_name" value="<?php echo htmlspecialchars($subject['subject_name']); ?>" required>
        <br><br>

        <label for="credit">Số tín chỉ:</label>
        <input type="number" id="credit" name="credit" value="<?php echo htmlspecialchars($subject['credit']); ?>" required>
        <br><br>

        <button type="submit">Cập nhật môn học</button>
    </form>
</div>
<?php include_once "../footer.php"; ?>
</body>
</html>
