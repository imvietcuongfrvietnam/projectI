<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách lớp học</title>
    <link rel="stylesheet" href="../src/css/app.css">
</head>
<body>
    <?php
    include_once "nav_bar.php"; 
    include_once "../connection.php"; 

    global $conn;

    // Kiểm tra nếu người dùng đã nhập học kỳ
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['semester_id']) && !empty($_POST['semester_id'])) {
        $semester_id = $_POST['semester_id']; // Lấy học kỳ từ form
        $teacher_id = $_SESSION['user_id']; // Lấy ID giảng viên từ session

        // Tạo câu lệnh SQL để lấy danh sách lớp theo giảng viên và học kỳ
        $sql = "SELECT c.class_id, s.subject_name
                FROM class c
                JOIN subject s ON c.subject_id = s.subject_id
                WHERE c.teacher_id = ? AND c.semester_id = ?";
        $params = [$teacher_id, $semester_id];

        // Chuẩn bị và thực thi truy vấn
        $stmt = sqlsrv_prepare($conn, $sql, $params);

        if (sqlsrv_execute($stmt)) {
            // Kiểm tra kết quả
            $row_count = sqlsrv_num_rows($stmt);
            if ($row_count > 0) {
                // Nếu có lớp, hiển thị danh sách lớp
                echo "<h3>Danh sách lớp học của bạn trong học kỳ: $semester_id</h3>";
                echo "<table border='1'>";
                echo "<tr><th>Mã lớp</th><th>Tên môn</th></tr>";

                // Duyệt và hiển thị từng lớp học
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['class_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['subject_name']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                // Nếu không có lớp nào
                echo "Kỳ này bạn không nhận lớp nào cả.";
            }
        } else {
            // Nếu truy vấn không thành công
            echo "Lỗi khi truy vấn dữ liệu.";
        }
    } else {
        // Form nhập học kỳ
        echo '<h3>Chọn học kỳ để xem danh sách lớp học:</h3>';
        echo '<form method="POST" action="">
                <label for="semester_id">Chọn học kỳ: </label>
                <input type="text" name="semester_id" required placeholder="Nhập học kỳ (ví dụ: 2024.1)">
                <button type="submit">Xem lớp học</button>
              </form>';
    }

    // Đóng kết nối CSDL
    sqlsrv_close($conn);
    ?>
    <?php include_once "../footer.php";?>

</body>
</html>
