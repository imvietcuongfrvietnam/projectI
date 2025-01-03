<?php
session_start();
include_once "../connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve'])) {
    global $conn;

    // Lấy semester_id từ form ẩn
    $semester_id = $_POST['semester_id'];

    try {
        // Cập nhật trạng thái trong bảng enroll
        $sql_update_status = "
            UPDATE enroll 
            SET status = N'Thành công'
            WHERE status = N'Đã đăng ký'
            AND class_id IN (
                SELECT class_id 
                FROM class 
                WHERE semester_id = ?
            );
        ";

        $stmt = sqlsrv_prepare($conn, $sql_update_status, [$semester_id]);
        if (!$stmt) {
            throw new Exception("Lỗi chuẩn bị câu lệnh SQL: " . print_r(sqlsrv_errors(), true));
        }

        $result = sqlsrv_execute($stmt);
        if (!$result) {
            throw new Exception("Lỗi thực thi câu lệnh SQL: " . print_r(sqlsrv_errors(), true));
        }

        // Lấy danh sách sinh viên và lớp để thêm vào transcript
        $sql_get_classes = "
            SELECT e.student_id, e.class_id, c.subject_id
            FROM enroll e
            JOIN class c ON e.class_id = c.class_id
            WHERE c.semester_id = ? AND e.[status] = N'Thành công'
        ";

        $stmt_classes = sqlsrv_prepare($conn, $sql_get_classes, [$semester_id]);
        if (!$stmt_classes) {
            throw new Exception("Lỗi chuẩn bị câu lệnh lấy dữ liệu:1 " . print_r(sqlsrv_errors(), true));
        }

        $result_classes = sqlsrv_execute($stmt_classes);
        if (!$result_classes) {
            throw new Exception("Lỗi thực thi câu lệnh lấy dữ liệu: " . print_r(sqlsrv_errors(), true));
        }

        // Thêm dữ liệu vào bảng transcript
        $added_count = 0;
        while ($row = sqlsrv_fetch_array($stmt_classes, SQLSRV_FETCH_ASSOC)) {
            $insert_transcript_sql = "
                INSERT INTO transcript (student_id, class_id, semester_id, subject_id, score, grade, status)
                VALUES (?, ?, ?, ?, NULL, NULL, NULL)
            ";

            $insert_stmt = sqlsrv_prepare($conn, $insert_transcript_sql, [
                $row['student_id'],
                $row['class_id'],
                $semester_id,
                $row['subject_id']
            ]);

            if (!$insert_stmt) {
                throw new Exception("Lỗi chuẩn bị câu lệnh chèn vào transcript: " . print_r(sqlsrv_errors(), true));
            }

            $insert_result = sqlsrv_execute($insert_stmt);
            if ($insert_result) {
                $added_count++;
            }
        }

        // Hiển thị số bản ghi đã thêm
        if ($added_count > 0) {
            echo "Đã thêm $added_count bản ghi vào bảng transcript.";
        } else {
            echo "Không có bản ghi nào được thêm vào bảng transcript.";
        }

        // Chuyển hướng sau khi thành công
        header("Location: ../admin/quan_ly_dang_ky.php?semester_id=" . $semester_id);
        exit();

    } catch (Exception $e) {
        echo "Đã xảy ra lỗi: " . $e->getMessage();
    }
} else {
    echo "Không có dữ liệu hợp lệ để xử lý.";
}
?>
