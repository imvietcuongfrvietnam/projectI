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
include_once "nav_bar.php";
include_once "../connection.php";

// Khởi tạo biến lỗi
$error = null;

// Kiểm tra xem người dùng có gửi form hay không
if (isset($_POST['class_id'])) {
    $student_id = $_SESSION['user_id']; // Lấy mã sinh viên từ session
    $class_id = $_POST['class_id']; // Lớp học được chọn từ form

    try {
        // Kiểm tra xem sinh viên đã đăng ký lớp này chưa
        $check_class_sql = "SELECT * FROM enroll WHERE student_id = ? AND class_id = ?";
        $check_class_stmt = sqlsrv_prepare($conn, $check_class_sql, [$student_id, $class_id]);

        if (!$check_class_stmt || !sqlsrv_execute($check_class_stmt)) {
            throw new Exception("Lỗi khi kiểm tra lớp đăng ký: " . print_r(sqlsrv_errors(), true));
        }

        if (sqlsrv_fetch_array($check_class_stmt, SQLSRV_FETCH_ASSOC)) {
            // Nếu đã đăng ký lớp này, báo lỗi
            echo "<p>Lỗi: Bạn đã đăng ký lớp này rồi.</p>";
        } else {
            // Kiểm tra môn học của lớp này
            $class_sql = "
                SELECT s.subject_id 
                FROM class c 
                JOIN subject s ON c.subject_id = s.subject_id 
                WHERE c.class_id = ?
            ";
            $class_stmt = sqlsrv_prepare($conn, $class_sql, [$class_id]);

            if (!$class_stmt || !sqlsrv_execute($class_stmt)) {
                throw new Exception("Lỗi khi kiểm tra môn học của lớp: " . print_r(sqlsrv_errors(), true));
            }

            $class_row = sqlsrv_fetch_array($class_stmt, SQLSRV_FETCH_ASSOC);
            $subject_id = $class_row['subject_id'];

            // Kiểm tra xem sinh viên đã đăng ký môn học này chưa
            $check_subject_sql = "
                SELECT c.subject_id 
                FROM class c 
                JOIN enroll e ON c.class_id = e.class_id 
                WHERE e.student_id = ? AND c.subject_id = ? AND c.semester_id = (
                    SELECT semester_id FROM class WHERE class_id = ?
                )
            ";
            $check_subject_stmt = sqlsrv_prepare($conn, $check_subject_sql, [$student_id, $subject_id, $class_id]);

            if (!$check_subject_stmt || !sqlsrv_execute($check_subject_stmt)) {
                throw new Exception("Lỗi khi kiểm tra môn học đã đăng ký: " . print_r(sqlsrv_errors(), true));
            }

            if (sqlsrv_fetch_array($check_subject_stmt, SQLSRV_FETCH_ASSOC)) {
                // Nếu đã đăng ký môn học này, báo lỗi
                echo "<p>Lỗi: Bạn đã đăng ký môn học này rồi.</p>";
            } else {
                // Nếu chưa đăng ký lớp và môn, thực hiện đăng ký
                $registration_date = date('Y-m-d H:i:s');
                $insert_sql = "
                    INSERT INTO enroll (class_id, student_id, registration_date, status) 
                    VALUES (?, ?, ?, N'Đã đăng ký')
                ";
                $insert_stmt = sqlsrv_prepare($conn, $insert_sql, [$class_id, $student_id, $registration_date]);

                if (!$insert_stmt || !sqlsrv_execute($insert_stmt)) {
                    throw new Exception("Lỗi khi thực hiện đăng ký lớp học: " . print_r(sqlsrv_errors(), true));
                }

                echo "<p>Đăng ký lớp học thành công!</p>";
            }
        }

        // Giải phóng tài nguyên
        if (isset($check_class_stmt)) sqlsrv_free_stmt($check_class_stmt);
        if (isset($check_subject_stmt)) sqlsrv_free_stmt($check_subject_stmt);
        if (isset($class_stmt)) sqlsrv_free_stmt($class_stmt);

    } catch (Exception $e) {
        // Bắt lỗi và hiển thị thông báo
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
