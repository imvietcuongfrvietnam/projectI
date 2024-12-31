<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";  // Kết nối cơ sở dữ liệu
global $conn;

$teacher_id = $_SESSION['user_id']; // Lấy thông tin giảng viên từ session

// Xử lý khi giảng viên chọn kỳ học
if (isset($_POST['semester_id'])) {
    $semester_id = $_POST['semester_id'];

    // Truy vấn các lớp học mà giảng viên dạy trong kỳ học đã chọn
    $sql_classes = "SELECT class_id FROM class WHERE teacher_id = ? AND semester_id = ?";
    $params = array($teacher_id, $semester_id);
    $stmt_classes = sqlsrv_query($conn, $sql_classes, $params);

    if ($stmt_classes === false) {
        die(print_r(sqlsrv_errors(), true));
    }
}

// Xử lý khi giảng viên chọn lớp học để cập nhật điểm
if (isset($_POST['class_id'])) {
    $class_id = $_POST['class_id'];

    // Truy vấn sinh viên đã đăng ký lớp học này
    $sql_students = "SELECT e.student_id, s.student_name FROM enroll e
                     JOIN student s ON e.student_id = s.student_id
                     WHERE e.class_id = ?";
    $params_students = array($class_id);
    $stmt_students = sqlsrv_query($conn, $sql_students, $params_students);

    if ($stmt_students === false) {
        die(print_r(sqlsrv_errors(), true));
    }
}

// Hàm tính grade và status từ điểm số
function calculateGradeAndStatus($score) {
    if ($score >= 9.5) {
        return ['A+', 'Đạt'];
    } elseif ($score >= 8.5) {
        return ['A', 'Đạt'];
    } elseif ($score >= 8) {
        return ['B+', 'Đạt'];
    } elseif ($score >= 7) {
        return ['B', 'Đạt'];
    } elseif ($score >= 6.5) {
        return ['C+', 'Đạt'];
    } elseif ($score >= 6) {
        return ['C', 'Đạt'];
    } elseif ($score >= 5) {
        return ['D+', 'Đạt'];
    } elseif ($score >= 4.5) {
        return ['D', 'Đạt'];
    } else {
        return ['F', 'Không Đạt'];
    }
}

// Cập nhật điểm cho sinh viên
if (isset($_POST['submit_grades'])) {
    $student_id = $_POST['student_id'];
    $score = $_POST['score'];
    $class_id = $_POST['class_id'];
    $semester_id = $_POST['semester_id'];
    $subject_id = $_POST['subject_id'];

    // Tính grade và status từ điểm số
    list($grade, $status) = calculateGradeAndStatus($score);

    // Cập nhật điểm và xếp loại vào bảng transcript
    $sql_update_transcript = "INSERT INTO transcript (student_id, class_id, semester_id, subject_id, score, grade, status) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $params_update = array($student_id, $class_id, $semester_id, $subject_id, $score, $grade, $status);
    $stmt_update = sqlsrv_query($conn, $sql_update_transcript, $params_update);

    if ($stmt_update === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    echo "Điểm đã được cập nhật thành công!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật điểm cho sinh viên</title>
</head>
<body>
<h2>Cập nhật điểm cho sinh viên</h2>

<!-- Form chọn kỳ học -->
<form method="POST" action="">
    <label for="semester_id">Chọn kỳ học:</label>
    <select name="semester_id" id="semester_id" required>
        <?php
        // Truy vấn tất cả kỳ học có trong cơ sở dữ liệu
        $sql = "SELECT semester_id, semester_name FROM semester";
        $stmt = sqlsrv_query($conn, $sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<option value='" . $row['semester_id'] . "'>" . $row['semester_name'] . "</option>";
        }
        ?>
    </select>
    <button type="submit">Lựa chọn kỳ học</button>
</form>

<!-- Form chọn lớp học -->
<?php if (isset($stmt_classes)): ?>
    <form method="POST" action="">
        <label for="class_id">Chọn lớp học:</label>
        <select name="class_id" id="class_id" required>
            <?php
            while ($row_class = sqlsrv_fetch_array($stmt_classes, SQLSRV_FETCH_ASSOC)) {
                echo "<option value='" . $row_class['class_id'] . "'>" . $row_class['class_name'] . "</option>";
            }
            ?>
        </select>
        <button type="submit">Lựa chọn lớp học</button>
    </form>
<?php endif; ?>

<!-- Form nhập điểm cho sinh viên -->
<?php if (isset($stmt_students)): ?>
    <form method="POST" action="">
        <table>
            <tr>
                <th>Mã Sinh Viên</th>
                <th>Tên Sinh Viên</th>
                <th>Điểm</th>
            </tr>
            <?php
            while ($row_student = sqlsrv_fetch_array($stmt_students, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>
                        <td>" . $row_student['student_id'] . "</td>
                        <td>" . $row_student['student_name'] . "</td>
                        <td>
                            <input type='hidden' name='student_id[]' value='" . $row_student['student_id'] . "'>
                            <input type='number' step='0.1' name='score[]' required>
                        </td>
                      </tr>";
            }
            ?>
        </table>
        <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
        <input type="hidden" name="semester_id" value="<?php echo $semester_id; ?>">
        <input type="hidden" name="subject_id" value="Mã môn học"> <!-- Thêm mã môn học thích hợp -->
        <button type="submit" name="submit_grades">Cập nhật điểm</button>
    </form>
<?php endif; ?>
</body>
<?php include_once "../footer.php"; ?>
</html>
