<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

// Kiểm tra xem có nhận được mã môn học để xóa hay không
if (isset($_GET['subject_id'])) {
    $subject_id = $_GET['subject_id'];

    // Truy vấn xóa môn học
    $sql = "DELETE FROM subject WHERE subject_id = ?";
    $params = array($subject_id);
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if (sqlsrv_execute($stmt)) {
        $message = "Xóa môn học thành công!";
    } else {
        $message = "Lỗi: " . print_r(sqlsrv_errors(), true);
    }

    sqlsrv_free_stmt($stmt);
} else {
    $message = "Không có mã môn học để xóa.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa môn học</title>
    <link rel="stylesheet" href="../src/css/form.css">
    <style>
        .message {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
            color: #333;
        }

        .back-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            width: fit-content;
            transition: background-color 0.3s, transform 0.2s;
        }

        .back-button:hover {
            background-color: #444;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<div class="form-container">
    <div class="message">
        <?php echo $message; ?>
    </div>
    <a href="/quanlymonhoc.php" class="back-button">Quay lại trang quản lý môn học</a>
</div>
</body>
</html>
<?php include_once "../footer.php"; ?>
