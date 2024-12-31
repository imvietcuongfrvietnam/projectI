<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

// Get semester_id from URL
$semester_id = $_GET['semester_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đăng ký - Kỳ học</title>
</head>
<body>
<h2>Quản lý đăng ký cho kỳ học: <?php echo $semester_id; ?></h2>

<div id="registration-management">
    <h3>1. Danh sách sinh viên đăng ký</h3>
    <form method="POST" action="../helper/approve_students.php">
        <table border="1">
            <thead>
            <tr>
                <th>Mã lớp</th>
                <th>Mã sinh viên</th>
                <th>Ngày đăng ký</th>
                <th>Trạng thái</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql_enroll = "SELECT class_id, student_id, registration_date, status FROM enroll 
               WHERE class_id IN (SELECT class_id FROM class WHERE semester_id = ?) 
               AND status = N'Đã đăng ký'";

            $params_enroll = array($semester_id);
            $stmt_enroll = sqlsrv_query($conn, $sql_enroll, $params_enroll);

            while ($row = sqlsrv_fetch_array($stmt_enroll, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['class_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['registration_date']->format('Y-m-d H:i:s')) . "</td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                echo "</tr>";
            }
            sqlsrv_free_stmt($stmt_enroll);
            ?>
            </tbody>
        </table>
        <br>
        <input type="hidden" name="semester_id" value="<?php echo htmlspecialchars($semester_id); ?>">
        <button type="submit" name="approve" value="approve">Duyệt tất cả</button>
    </form>

    <h3>2. Mở lớp học</h3>
    <form method="POST" action="../helper/create_class.php">
        <input type="hidden" name="semester_id" value="<?php echo htmlspecialchars($semester_id); ?>">

        <label for="subject_id">Mã môn học:</label>
        <select name="subject_id" id="subject_id">
            <?php
            $sql_subject = "SELECT subject_id, subject_name FROM subject";
            $stmt_subject = sqlsrv_query($conn, $sql_subject);
            while ($row = sqlsrv_fetch_array($stmt_subject, SQLSRV_FETCH_ASSOC)) {
                echo "<option value='" . htmlspecialchars($row['subject_id']) . "'>" . htmlspecialchars($row['subject_name']) . "</option>";
            }
            sqlsrv_free_stmt($stmt_subject);
            ?>
        </select>
        <br><br>

        <label for="class_id">Mã lớp:</label>
        <input type="text" id="class_id" name="class_id" required>
        <br><br>

        <label for="teacher_id">Giảng viên:</label>
        <select name="teacher_id" id="teacher_id">
            <?php
            $sql_teacher = "SELECT teacher_id, teacher_name FROM teacher";
            $stmt_teacher = sqlsrv_query($conn, $sql_teacher);
            while ($row = sqlsrv_fetch_array($stmt_teacher, SQLSRV_FETCH_ASSOC)) {
                echo "<option value='" . htmlspecialchars($row['teacher_id']) . "'>" . htmlspecialchars($row['teacher_name']) . "</option>";
            }
            sqlsrv_free_stmt($stmt_teacher);
            ?>
        </select>
        <br><br>

        <label for="day_of_week">Ngày học:</label>
        <select name="day_of_week" id="day_of_week">
            <option value="Thứ 2">Thứ 2</option>
            <option value="Thứ 3">Thứ 3</option>
            <option value="Thứ 4">Thứ 4</option>
            <option value="Thứ 5">Thứ 5</option>
            <option value="Thứ 6">Thứ 6</option>
        </select>
        <br><br>

        <label for="time_start">Giờ bắt đầu:</label>
        <input type="time" id="time_start" name="time_start" required>
        <br><br>

        <label for="time_end">Giờ kết thúc:</label>
        <input type="time" id="time_end" name="time_end" required>
        <br><br>

        <label for="location">Địa điểm:</label>
        <input type="text" id="location" name="location" required>
        <br><br>

        <button type="submit">Mở lớp học</button>
    </form>
</div>

<?php include_once "../footer.php"; ?>
</body>
</html>
