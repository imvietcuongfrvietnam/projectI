<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $semester_id = $_POST['semester_id'];

    // Truy vấn xóa kỳ học
    $sql = "DELETE FROM semester WHERE semester_id = ?";
    $params = array($semester_id);
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if (sqlsrv_execute($stmt)) {
        echo "<p>Xóa kỳ học thành công!</p>";
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
    <title>Xóa kỳ học</title>
</head>
<body>
<form method="POST" action="">
    <label for="semester_id">Nhập mã kỳ học cần xóa:</label>
    <input type="text" id="semester_id" name="semester_id" placeholder="Nhập mã kỳ học" required>
    <br><br>
    <button type="submit">Xóa kỳ học</button>
</form>
<?php include_once "../footer.php"; ?>
</body>
</html>
