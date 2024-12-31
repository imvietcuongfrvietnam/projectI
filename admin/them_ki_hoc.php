<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $semester_id = $_POST['semester_id'];
    $semester_name = $_POST['semester_name'];
    $begin_date = $_POST['begin_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];

    // Truy vấn thêm kỳ học
    $sql = "INSERT INTO semester (semester_id, semester_name, begin_date, end_date, status) VALUES (?, ?, ?, ?, ?)";
    $params = array($semester_id, $semester_name, $begin_date, $end_date, $status);
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if (sqlsrv_execute($stmt)) {
        echo "<p>Thêm kỳ học thành công!</p>";
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
    <title>Thêm kỳ học</title>
    <link rel="stylesheet" href="../src/css/form.css"> <!-- Liên kết file CSS -->

</head>
<body>
<div class="form-container">

<form method="POST" action="">
    <label for="semester_id">Mã kỳ học:</label>
    <input type="text" id="semester_id" name="semester_id" placeholder="Nhập mã kỳ học" required>
    <br><br>
    <label for="semester_name">Tên kỳ học:</label>
    <input type="text" id="semester_name" name="semester_name" placeholder="Nhập tên kỳ học" required>
    <br><br>
    <label for="begin_date">Ngày bắt đầu:</label>
    <input type="date" id="begin_date" name="begin_date" required>
    <br><br>
    <label for="end_date">Ngày kết thúc:</label>
    <input type="date" id="end_date" name="end_date" required>
    <br><br>
    <label for="status">Trạng thái:</label>
    <input type="text" id="status" name="status" placeholder="Trạng thái kỳ học" required>
    <br><br>
    <button type="submit">Thêm kỳ học</button>
</form>
</div>
<?php include_once "../footer.php"; ?>
</body>
</html>
