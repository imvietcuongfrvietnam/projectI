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
    <link rel="stylesheet" href="../src/css/app.css">
</head>
<body>
<?php
include_once "../connection.php";
include_once "nav_bar.php";

// Khởi tạo biến lỗi
$error = null;

// Kiểm tra xem người dùng có gửi form hay không
if (isset($_POST['submit'])) {
    $student_id = $_SESSION['user_id']; // Lấy mã sinh viên từ session
    $classes = $_POST['classes']; // Danh sách lớp được chọn từ form

    try {
        if (!empty($classes)) {
            $registration_date = date('Y-m-d H:i:s'); // Ngày đăng ký hiện tại
            $success_count = 0;
            $error_count = 0;

            foreach ($classes as $class_id) {
                // Kiểm tra xem sinh viên đã đăng ký lớp này chưa
                $check_sql = "SELECT * FROM enroll WHERE student_id = ? AND class_id = ?";
                $check_stmt = sqlsrv_prepare($conn, $check_sql, [$student_id, $class_id]);

                if (!$check_stmt) {
                    throw new Exception("Lỗi chuẩn bị câu lệnh kiểm tra đăng ký: " . print_r(sqlsrv_errors(), true));
                }

                $result = sqlsrv_execute($check_stmt);

                if (!$result) {
                    throw new Exception("Lỗi thực thi câu lệnh kiểm tra đăng ký: " . print_r(sqlsrv_errors(), true));
                }

                if (sqlsrv_fetch_array($check_stmt, SQLSRV_FETCH_ASSOC)) {
                    // Nếu đã đăng ký lớp, tăng biến đếm lỗi
                    $error_count++;
                } else {
                    // Nếu chưa, thực hiện chèn vào bảng `enroll`
                    $insert_sql = "INSERT INTO enroll (class_id, student_id, registration_date, status)
                                   VALUES (?, ?, ?, N'Đã đăng ký')";
                    $insert_stmt = sqlsrv_prepare($conn, $insert_sql, [$class_id, $student_id, $registration_date]);

                    if (!$insert_stmt) {
                        throw new Exception("Lỗi chuẩn bị câu lệnh chèn đăng ký: " . print_r(sqlsrv_errors(), true));
                    }

                    $insert_result = sqlsrv_execute($insert_stmt);

                    if ($insert_result) {
                        $success_count++;
                    } else {
                        $error_count++;
                        // Ghi lỗi từ SQL nếu có
                        $error = "Lỗi thực thi câu lệnh chèn đăng ký: " . print_r(sqlsrv_errors(), true);
                        throw new Exception($error);
                    }
                }

                sqlsrv_free_stmt($check_stmt);
            }

            // Hiển thị thông báo kết quả
            echo "<h3>Kết quả đăng ký lớp:</h3>";
            echo "<p>Đăng ký thành công: $success_count lớp.</p>";
            echo "<p>Lớp đã đăng ký trước đó hoặc lỗi: $error_count lớp.</p>";
            if ($error != null) {
                echo "<p>Nguyên nhân lỗi: $error</p>";
            }
        } else {
            echo "<p>Vui lòng chọn ít nhất một lớp để đăng ký.</p>";
        }
    } catch (Exception $e) {
        // Bắt lỗi và hiển thị thông báo lỗi
        echo "<p>Đã xảy ra lỗi trong quá trình đăng ký lớp:</p>";
        echo "<p>Lỗi: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>Không có thông tin đăng ký được gửi.</p>";
}

// Đóng kết nối CSDL
sqlsrv_close($conn);
?>
</body>
</html>
