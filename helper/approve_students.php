<?php
session_start();
include_once "../connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve'])) {
    global $conn;

    // Lấy semester_id từ form ẩn
    $semester_id = $_POST['semester_id'];

    try {
        // Truy vấn cập nhật trạng thái của sinh viên trong enroll
        $sql_update_status = "			UPDATE enroll 
SET [status] = N'Thành công'
WHERE [status] = N'Đã đăng ký' -- Sử dụng 'Đ' thay vì 'Ð'
AND class_id IN (
    SELECT class_id 
    FROM class 
    WHERE semester_id = N'2024.3'
);";

        // Chuẩn bị câu lệnh SQL
        $stmt = sqlsrv_prepare($conn, $sql_update_status, [$semester_id]);

        if (!$stmt) {
            throw new Exception("Lỗi chuẩn bị câu lệnh SQL: " . print_r(sqlsrv_errors(), true));
        }

        // Thực thi câu lệnh SQL
        $result = sqlsrv_execute($stmt);

        if (!$result) {
            throw new Exception("Lỗi thực thi câu lệnh SQL: " . print_r(sqlsrv_errors(), true));
        }

        // Cập nhật thành công
        header("Location: ../admin/quan_ly_dang_ky.php?semester_id=" . $semester_id);
        exit(); // Dừng script sau khi chuyển hướng

    } catch (Exception $e) {
        // Nếu có lỗi trong quá trình xử lý
        echo "Đã xảy ra lỗi: " . $e->getMessage();
    }
} else {
    echo "Không có dữ liệu hợp lệ để xử lý.";
}
?>
