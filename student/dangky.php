<?php global $conn;
session_start(); ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký lớp</title>
</head>
<body>
    <?php 
        include_once "nav_bar.php";
        include_once "../connection.php";

        $student_id = $_SESSION["user_id"]; // Mã sinh viên từ session

        // Lấy học kỳ đang mở đăng ký
        $sql = "SELECT * FROM semester WHERE status = N'Mở đăng ký'";
        $stmt = sqlsrv_query($conn, $sql);

        if (!$stmt) {
            die(print_r(sqlsrv_errors(), true)); // Nếu truy vấn không thành công, in lỗi
        }

        $semester = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($semester) {
            $semester_id = $semester['semester_id'];
            $semester_name = $semester['semester_name'];

            echo "<h3>Học kỳ mở đăng ký: $semester_name</h3>";

            // Lấy danh sách lớp học của học kỳ đang mở
            $sql_classes = "SELECT c.class_id, s.subject_name
                            FROM class c 
                            JOIN subject s ON c.subject_id = s.subject_id
                            WHERE c.semester_id = ?";
            $stmt_classes = sqlsrv_prepare($conn, $sql_classes, array($semester_id));

            if (sqlsrv_execute($stmt_classes)) {
                echo "<form method='POST' action='register_class.php'>";
                echo "<table>";
                echo "<tr><th>Mã lớp</th><th>Tên môn học</th><th>Chọn đăng ký</th></tr>";

                while ($row = sqlsrv_fetch_array($stmt_classes, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['class_id'] . "</td>";
                    echo "<td>" . $row['subject_name'] . "</td>";
                    echo "<td><input type='checkbox' name='classes[]' value='" . $row['class_id'] . "'></td>";
                    echo "</tr>";
                }

                echo "</table>";
                echo "<button type='submit' name='submit'>Đăng ký lớp</button>";
                echo "</form>";
            } else {
                echo "Không có lớp học nào trong học kỳ này.";
            }
        } else {
            echo "Hiện tại không có học kỳ nào mở đăng ký.";
        }

        sqlsrv_free_stmt($stmt);
        sqlsrv_free_stmt($stmt_classes);
        sqlsrv_close($conn);
    ?>
</body>
</html>
