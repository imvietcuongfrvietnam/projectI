<?php
global $conn;
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xử lý đăng ký lớp</title>
</head>
<body>
    <?php
    include_once "../connection.php";
    include_once "nav_bar.php";

    // Kiểm tra xem người dùng có gửi form hay không
    if (isset($_POST['submit'])) {
        $student_id = $_SESSION['user_id']; // Lấy mã sinh viên từ session
        $classes = $_POST['classes']; // Danh sách lớp được chọn từ form

        if (!empty($classes)) {
            $registration_date = date('Y-m-d H:i:s'); // Ngày đăng ký hiện tại
            $success_count = 0;
            $error_count = 0;

            foreach ($classes as $class_id) {
                // Kiểm tra xem sinh viên đã đăng ký lớp này chưa
                $check_sql = "SELECT * FROM enroll WHERE student_id = ? AND class_id = ?";
                $check_stmt = sqlsrv_prepare($conn, $check_sql, [$student_id, $class_id]);
                sqlsrv_execute($check_stmt);

                if (sqlsrv_fetch_array($check_stmt, SQLSRV_FETCH_ASSOC)) {
                    // Nếu đã đăng ký lớp, tăng biến đếm lỗi
                    $error_count++;
                } else {
                    // Nếu chưa, thực hiện chèn vào bảng `enroll`
                    $insert_sql = "INSERT INTO enroll (class_id, student_id, registration_date, status)
                                   VALUES (?, ?, ?, 'Đã đăng ký')";
                    $insert_stmt = sqlsrv_prepare($conn, $insert_sql, [$class_id, $student_id, $registration_date]);

                    if (sqlsrv_execute($insert_stmt)) {
                        $success_count++;
                    } else {
                        $error_count++;
                    }
                }

                sqlsrv_free_stmt($check_stmt);
            }

            // Hiển thị thông báo kết quả
            echo "<h3>Kết quả đăng ký lớp:</h3>";
            echo "<p>Đăng ký thành công: $success_count lớp.</p>";
            echo "<p>Lớp đã đăng ký trước đó hoặc lỗi: $error_count lớp.</p>";
        } else {
            echo "<p>Vui lòng chọn ít nhất một lớp để đăng ký.</p>";
        }
    } else {
        echo "<p>Không có thông tin đăng ký được gửi.</p>";
    }

    // Đóng kết nối CSDL
    sqlsrv_close($conn);
    ?>
</body>
</html>
