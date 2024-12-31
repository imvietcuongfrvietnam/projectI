<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subject_id = $_POST['subject_id'];
    $subject_name = $_POST['subject_name'];
    $credit = $_POST['credit'];

    // Truy vấn cập nhật môn học
    $sql = "UPDATE subject SET subject_name = ?, credit = ? WHERE subject_id = ?";
    $params = array($subject_name, $credit, $subject_id);
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
<form method="POST" action="">
    <label for="subject_id">Mã môn học:</label>
    <input type="text" id="subject_id" name="subject_id" placeholder="Nhập mã môn học" required>
    <br><br>
    <label for="subject_name">Tên môn học:</label>
    <input type="text" id="subject_name" name="subject_name" placeholder="Nhập tên môn học" required>
    <br><br>
    <label for="credit">Số tín chỉ:</label>
    <input type="number" id="credit" name="credit" placeholder="Nhập số tín chỉ" required>
    <br><br>
    <button type="submit">Cập nhật môn học</button>
</form>
</div>
<?php include_once "../footer.php"; ?>
</body>
</html>
