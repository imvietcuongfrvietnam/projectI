<?php
session_start();
include_once "../connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $conn;

    // Nhận dữ liệu từ biểu mẫu
    $subject_id = $_POST["subject_id"];
    $semester_id = $_POST["semester_id"];
    $class_id = $_POST["class_id"];
    $teacher_id = $_POST["teacher_id"];
    $day_of_week = $_POST["day_of_week"];
    $time_start = $_POST["time_start"];
    $time_end = $_POST["time_end"];
    $location = $_POST["location"];

    // Kiểm tra dữ liệu nhập
    if (empty($subject_id) || empty($semester_id) || empty($class_id) || empty($teacher_id) || empty($day_of_week) || empty($time_start) || empty($time_end) || empty($location)) {
        echo "Vui lòng điền đầy đủ thông tin.";
        exit();
    }
    $check_sql = "SELECT COUNT(*) AS count FROM class WHERE class_id = ?";
    $params = [$class_id];
    $stmt_check = sqlsrv_query($conn, $check_sql, $params);
    $row = sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC);

    if ($row['count'] > 0) {
        echo "Class ID đã tồn tại. Vui lòng chọn mã khác.";
        header("Location: ../admin/quan_ly_dang_ky.php?semester_id=" . $semester_id);
    }

    // Thêm lớp học vào bảng class
    $sql_class = "INSERT INTO class (subject_id, semester_id, class_id, teacher_id) 
                  VALUES (?, ?, ?, ?)";
    $params_class = [$subject_id, $semester_id, $class_id, $teacher_id];
    $stmt_class = sqlsrv_query($conn, $sql_class, $params_class);

    if ($stmt_class === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Thêm lịch học vào bảng schedule
    $sql_schedule = "INSERT INTO schedule (class_id, day_of_week, time_start, time_end, location) 
                     VALUES (?, ?, ?, ?, ?)";
    $params_schedule = [$class_id, $day_of_week, $time_start, $time_end, $location];
    $stmt_schedule = sqlsrv_query($conn, $sql_schedule, $params_schedule);

    if ($stmt_schedule === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    echo "Lớp học đã được thêm thành công!";
    // Chuyển hướng về trang quản lý
    header("Location: ../admin/quan_ly_dang_ky.php?semester_id=" . $semester_id);
    exit();
}
?>
