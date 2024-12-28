<?php
global $conn;
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin sinh viên</title>
</head>
<body>
    <?php
        include_once "nav_bar.php";
        include_once "../connection.php";

        // Lấy student_id từ session hoặc từ query string nếu cần
        $student_id = $_SESSION['user_id']; // Hoặc lấy từ URL: $_GET['student_id']

        // Truy vấn thông tin sinh viên từ cơ sở dữ liệu
        $sql = "SELECT * FROM student WHERE student_id = ?";
        $stmt = sqlsrv_prepare($conn, $sql, array($student_id));

        if (!$stmt) {
            die(print_r(sqlsrv_errors(), true)); // Xử lý lỗi nếu query không thành công
        }

        // Thực thi truy vấn
        $result = sqlsrv_execute($stmt);
        if (!$result) {
            die(print_r(sqlsrv_errors(), true)); // Xử lý lỗi nếu truy vấn không thành công
        }

        // Lấy kết quả và hiển thị
        $student = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($student) {
            $mssv = $student['student_id'];
            $name = $student['student_name'];
            
            // Chuyển đổi ngày sinh từ định dạng SQL datetime thành đối tượng DateTime
            $dob = $student['student_dob'];
            $dob = $dob ? $dob->format('d-m-Y') : 'N/A'; // Kiểm tra và định dạng ngày sinh
            
            $email = $student['email'];
        } else {
            echo "Không tìm thấy thông tin sinh viên.";
        }
    ?>

    <h2>Thông tin sinh viên</h2>
    <p>Mã số sinh viên: <?= htmlspecialchars($mssv); ?></p>
    <p>Họ tên: <?= htmlspecialchars($name); ?></p>
    <p>Ngày sinh: <?= htmlspecialchars($dob); ?></p>
    <p>Email: <?= htmlspecialchars($email); ?></p>

    <?php
        // Đóng kết nối sau khi sử dụng
        sqlsrv_free_stmt($stmt); // Giải phóng tài nguyên bộ nhớ của truy vấn
        sqlsrv_close($conn); // Đóng kết nối CSDL
    ?>

</body>
</html>
