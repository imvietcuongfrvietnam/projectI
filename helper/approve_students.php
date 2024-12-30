<?php
session_start();
include_once "../connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve_students'])) {
    global $conn;

    // Lấy semester_id từ form ẩn
    $semester_id = $_POST['semester_id'];

    // Truy vấn danh sách các sinh viên "Đã đăng ký" trong kỳ học
    $sql_update_status = "UPDATE enroll 
                          SET status = N'Thành công'
                          WHERE class_id IN (
                              SELECT class_id 
                              FROM class 
                              WHERE semester_id = ?
                          ) AND status = N'Ðã đăng ký'";

    $params = [$semester_id];
    $stmt = sqlsrv_query($conn, $sql_update_status, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    echo "../admin/quan_ly_dang_ky.php?semester_id=" . $semester_id;

    echo "Tất cả sinh viên đã được duyệt thành công!";
    // Chuyển hướng về trang quản lý
    header("Location: ../admin/quan_ly_dang_ky.php?semester_id=" . $semester_id);
    exit();
}
?>
