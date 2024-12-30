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
    <h3>1. Đăng ký sinh viên</h3>
    <!-- Add student registration form or list of registered students -->
    <form method="POST" action="dang_ky_sinh_vien.php">
        <label for="student_id">Mã sinh viên:</label>
        <select name="student_id" id="student_id">
            <?php
            // Populate student options
            $sql = "SELECT student_id, student_name FROM student";
            $stmt = sqlsrv_query($conn, $sql);
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                echo "<option value='" . $row['student_id'] . "'>" . $row['student_name'] . "</option>";
            }
            sqlsrv_free_stmt($stmt);
            ?>
        </select>
        <br><br>
        <label for="class_id">Mã lớp:</label>
        <select name="class_id" id="class_id">
            <?php
            // Populate class options for the selected semester
            $sql_class = "SELECT class_id, subject_id FROM class WHERE semester_id = ?";
            $params_class = array($semester_id);
            $stmt_class = sqlsrv_query($conn, $sql_class, $params_class);
            while ($row = sqlsrv_fetch_array($stmt_class, SQLSRV_FETCH_ASSOC)) {
                echo "<option value='" . $row['class_id'] . "'>Mã lớp: " . $row['class_id'] . " - Môn học: " . $row['subject_id'] . "</option>";
            }
            sqlsrv_free_stmt($stmt_class);
            ?>
        </select>
        <br><br>
        <button type="submit">Đăng ký sinh viên</button>
    </form>

    <h3>2. Đăng ký giảng viên</h3>
    <form method="POST" action="dang_ky_giang_vien.php">
        <label for="teacher_id">Mã giảng viên:</label>
        <select name="teacher_id" id="teacher_id">
            <?php
            // Populate teacher options
            $sql_teacher = "SELECT teacher_id, teacher_name FROM teacher";
            $stmt_teacher = sqlsrv_query($conn, $sql_teacher);
            while ($row = sqlsrv_fetch_array($stmt_teacher, SQLSRV_FETCH_ASSOC)) {
                echo "<option value='" . $row['teacher_id'] . "'>" . $row['teacher_name'] . "</option>";
            }
            sqlsrv_free_stmt($stmt_teacher);
            ?>
        </select>
        <br><br>
        <label for="class_id_teacher">Mã lớp:</label>
        <select name="class_id_teacher" id="class_id_teacher">
            <?php
            // Populate class options for the selected semester
            $stmt_class_teacher = sqlsrv_query($conn, $sql_class, $params_class);
            while ($row = sqlsrv_fetch_array($stmt_class_teacher, SQLSRV_FETCH_ASSOC)) {
                echo "<option value='" . $row['class_id'] . "'>Mã lớp: " . $row['class_id'] . " - Môn học: " . $row['subject_id'] . "</option>";
            }
            sqlsrv_free_stmt($stmt_class_teacher);
            ?>
        </select>
        <br><br>
        <button type="submit">Đăng ký giảng viên</button>
    </form>

    <h3>3. Mở lớp học</h3>
    <form method="POST" action="../helper/create_class.php">
        <label for="subject_id">Mã môn học:</label>
        <select name="subject_id" id="subject_id">
            <?php
            // Populate subject options from subject table
            $sql_subject = "SELECT subject_id, subject_name FROM subject";
            $stmt_subject = sqlsrv_query($conn, $sql_subject);
            while ($row = sqlsrv_fetch_array($stmt_subject, SQLSRV_FETCH_ASSOC)) {
                echo "<option value='" . $row['subject_id'] . "'>" . $row['subject_name'] . "</option>";
            }
            sqlsrv_free_stmt($stmt_subject);
            ?>
        </select>
        <br><br>
        <label for="teacher_id">Giảng viên:</label>
        <select name="teacher_id" id="teacher_id_class">
            <?php
            // Populate teacher options
            $stmt_teacher_class = sqlsrv_query($conn, $sql_teacher);
            while ($row = sqlsrv_fetch_array($stmt_teacher_class, SQLSRV_FETCH_ASSOC)) {
                echo "<option value='" . $row['teacher_id'] . "'>" . $row['teacher_name'] . "</option>";
            }
            sqlsrv_free_stmt($stmt_teacher_class);
            ?>
        </select>
        <br><br>
        <label for="class_date">Ngày học:</label>
        <input type="date" id="class_date" name="class_date" required>
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
