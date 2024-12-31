<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch dạy</title>
    <link rel="stylesheet" href="../src/css/app.css">
</head>
<body>
<main>
<?php


// Form nhập học kỳ
echo '<form action="" method="POST">';
echo 'Nhập học kì: <input type="text" name="hocki" required>';
echo '<button type="submit">Tìm lịch dạy</button>';
echo '</form>';

if (isset($_POST['hocki'])) {
    // Lấy giá trị học kỳ từ form
    $hocki = $_POST['hocki'];

    // Truy vấn lấy lịch dạy theo học kỳ và giảng viên
    $sql = "
        SELECT c.class_id, c.subject_id, s.subject_name, s.credit
        FROM schedule sc
        JOIN class c ON sc.class_id = c.class_id
        JOIN subject s ON c.subject_id = s.subject_id
        WHERE c.semester_id = ? 
        AND c.teacher_id = ?";

    // Tạo mảng tham số để chuẩn bị truy vấn
    $param = [$hocki, $_SESSION['user_id']];

    // Chuẩn bị và thực thi truy vấn
    $stmt = sqlsrv_prepare($conn, $sql, $param);
    if ($stmt && sqlsrv_execute($stmt)) {
        // Kiểm tra nếu có kết quả trả về
        $rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($rows) {
            // Hiển thị lịch dạy nếu có lớp
            echo '<table border="1">';
            echo '<tr><th>Mã lớp</th><th>Mã môn học</th><th>Tên môn học</th><th>Số tín chỉ</th></tr>';

            do {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($rows['class_id']) . '</td>';
                echo '<td>' . htmlspecialchars($rows['subject_id']) . '</td>';
                echo '<td>' . htmlspecialchars($rows['subject_name']) . '</td>';
                echo '<td>' . htmlspecialchars($rows['credit']) . '</td>';
                echo '</tr>';
            } while ($rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC));

            echo '</table>';
        } else {
            echo 'Không có lớp dạy nào trong học kỳ này.';
        }
    } else {
        echo 'Lỗi khi thực hiện truy vấn.';
    }

    // Giải phóng tài nguyên truy vấn
    sqlsrv_free_stmt($stmt);
}

?>
</main>
</body>
<?php include_once "../footer.php";?>
</html>
