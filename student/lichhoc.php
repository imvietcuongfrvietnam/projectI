<?php
global $conn;
session_start();
include_once "../connection.php";

// Lấy user_id từ session
$student_id = $_SESSION['user_id'];

// Kiểm tra nếu có yêu cầu chọn học kỳ từ người dùng
$semester_id = isset($_POST['semester_id']) ? $_POST['semester_id'] : null;

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lớp học của sinh viên</title>
    <link rel="stylesheet" href="../src/css/app.css">
</head>
<body>
<?php include_once "nav_bar.php"; ?>

<h2>Danh sách lớp học của bạn</h2>

<!-- Form chọn học kỳ -->
<form method="POST" action="">
    <label for="semester_id">Chọn học kỳ:</label>
    <select name="semester_id" id="semester_id">
        <option value="">Chọn học kỳ</option>
        <option value="2023.2">2023.2</option>
        <option value="2024.1">2024.1</option>
    </select>

    <?php
        // Lấy danh sách các học kỳ từ bảng semester
        $sql = "SELECT * FROM semester WHERE status = 'Mở đăng ký'";
        $stmt = sqlsrv_query($conn, $sql);

        if (sqlsrv_has_rows($stmt)) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                echo "<option value='" . $row['semester_id'] . "'>" . $row['semester_name'] . "</option>";
            }
        }
        ?>
    </select>
    <button type="submit">Lọc lớp học</button>
</form>

<?php
// Nếu đã chọn học kỳ, tiếp tục thực hiện truy vấn lọc các lớp học đã đăng ký của sinh viên trong học kỳ đó
if ($semester_id) {
    $sql = "SELECT c.class_id, s.subject_name, t.teacher_name, sch.day_of_week, sch.time_slot 
                FROM enroll e
                JOIN class c ON e.class_id = c.class_id
                JOIN subject s ON c.subject_id = s.subject_id
                JOIN teacher t ON c.teacher_id = t.teacher_id
                JOIN schedule sch ON c.class_id = sch.class_id
                WHERE e.student_id = ? 
                AND e.status = 'Đăng ký thành công'
                AND c.semester_id = ?"; // Lọc theo học kỳ

    // Chuẩn bị câu truy vấn với prepared statement
    $stmt = sqlsrv_prepare($conn, $sql, array($student_id, $semester_id));

    // Kiểm tra và thực thi câu truy vấn
    if (sqlsrv_execute($stmt)) {
        if (sqlsrv_has_rows($stmt)) {
            echo "<table border='1'>
                        <tr>
                            <th>Mã lớp</th>
                            <th>Tên môn học</th>
                            <th>Giảng viên</th>
                            <th>Ngày học</th>
                            <th>Giờ học</th>
                        </tr>";

            // Hiển thị các lớp đã đăng ký
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>
                            <td>" . $row['class_id'] . "</td>
                            <td>" . $row['subject_name'] . "</td>
                            <td>" . $row['teacher_name'] . "</td>
                            <td>" . $row['day_of_week'] . "</td>
                            <td>" . $row['time_slot'] . "</td>
                        </tr>";
            }

            echo "</table>";
        } else {
            echo "Bạn chưa đăng ký lớp học nào trong học kỳ này!";
        }
    } else {
        echo "Có lỗi xảy ra khi truy vấn dữ liệu!";
    }

    sqlsrv_free_stmt($stmt);
}

sqlsrv_close($conn);
?>
<?php include_once "../footer.php";?>

</body>
</html>
