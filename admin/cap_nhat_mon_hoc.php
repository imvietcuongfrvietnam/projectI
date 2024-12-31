<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

$subject_id = "";
$subject_name = "";
$credit = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['search'])) {
        // Tìm kiếm thông tin môn học
        $subject_id = $_POST['subject_id'];

        $sql = "SELECT subject_name, credit FROM subject WHERE subject_id = ?";
        $params = array($subject_id);
        $stmt = sqlsrv_prepare($conn, $sql, $params);

        if (sqlsrv_execute($stmt)) {
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            if ($row) {
                $subject_name = $row['subject_name'];
                $credit = $row['credit'];
            } else {
                echo "<p>Không tìm thấy môn học với mã $subject_id.</p>";
            }
        } else {
            echo "<p>Lỗi: " . print_r(sqlsrv_errors(), true) . "</p>";
        }
        sqlsrv_free_stmt($stmt);
    }

    if (isset($_POST['update'])) {
        // Cập nhật thông tin môn học
        $subject_id = $_POST['subject_id'];
        $subject_name = $_POST['subject_name'];
        $credit = $_POST['credit'];

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
        <input type="text" id="subject_id" name="subject_id" value="<?= htmlspecialchars($subject_id) ?>" placeholder="Nhập mã môn học" required>
        <button type="submit" name="search">Tìm kiếm</button>
        <br><br>
        <label for="subject_name">Tên môn học:</label>
        <input type="text" id="subject_name" name="subject_name" value="<?= htmlspecialchars($subject_name) ?>" placeholder="Nhập tên môn học" required>
        <br><br>
        <label for="credit">Số tín chỉ:</label>
        <input type="number" id="credit" name="credit" value="<?= htmlspecialchars($credit) ?>" placeholder="Nhập số tín chỉ" required>
        <br><br>
        <button type="submit" name="update">Cập nhật môn học</button>
    </form>
</div>
<?php include_once "../footer.php"; ?>
</body>
</html>
