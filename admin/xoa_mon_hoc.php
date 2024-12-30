<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subject_id = $_POST['subject_id'];

    // Truy vấn xóa môn học
    $sql = "DELETE FROM subject WHERE subject_id = ?";
    $params = array($subject_id);
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if (sqlsrv_execute($stmt)) {
        echo "<p>Xóa môn học thành công!</p>";
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
    <title>Xóa môn học</title>
</head>
<body>
<form method="POST" action="">
    <label for="subject_id">Mã môn học cần xóa:</label>
    <input type="text" id="subject_id" name="subject_id" placeholder="Nhập mã môn học" required>
    <br><br>
    <button type="submit">Xóa môn học</button>
</form>
<?php include_once "../footer.php"; ?>
</body>
</html>
