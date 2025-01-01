<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php"; // Kết nối cơ sở dữ liệu
global $conn;

$teacher_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Lấy thông tin giảng viên từ session

if (!$teacher_id) {
    die("Giảng viên chưa đăng nhập!");
}

// Truy vấn các kỳ học có trạng thái "Đang diễn ra"
$sql_semesters = "SELECT semester_id, semester_name FROM semester WHERE status = N'Đang diễn ra'";
$stmt_semesters = sqlsrv_query($conn, $sql_semesters);

if ($stmt_semesters === false) {
    die(print_r(sqlsrv_errors(), true));
}

$semester_id = $class_id = null;
$stmt_classes = $stmt_students = null;

if (isset($_POST['semester_id'])) {
    $semester_id = $_POST['semester_id'];
    $sql_classes = "SELECT class_id FROM class WHERE teacher_id = ? AND semester_id = ?";
    $params_classes = array($teacher_id, $semester_id);
    $stmt_classes = sqlsrv_query($conn, $sql_classes, $params_classes);

    if ($stmt_classes === false) {
        die(print_r(sqlsrv_errors(), true));
    }
}

if (isset($_POST['class_id'])) {
    $class_id = $_POST['class_id'];
    $sql_students = "SELECT student_id, score, grade, status FROM transcript WHERE class_id = ?";
    $params_students = array($class_id);
    $stmt_students = sqlsrv_query($conn, $sql_students, $params_students);

    if ($stmt_students === false) {
        die(print_r(sqlsrv_errors(), true));
    }
}

function calculateGradeAndStatus($score) {
    if ($score >= 9.5) return ['A+', 'Đạt'];
    if ($score >= 8.5) return ['A', 'Đạt'];
    if ($score >= 8) return ['B+', 'Đạt'];
    if ($score >= 7) return ['B', 'Đạt'];
    if ($score >= 6.5) return ['C+', 'Đạt'];
    if ($score >= 6) return ['C', 'Đạt'];
    if ($score >= 5) return ['D+', 'Đạt'];
    if ($score >= 4.5) return ['D', 'Đạt'];
    return ['F', 'Không Đạt'];
}

if (isset($_POST['submit_grades'])) {
    $student_ids = isset($_POST['student_id']) ? $_POST['student_id'] : [];
    $scores = isset($_POST['score']) ? $_POST['score'] : [];
    $class_id = $_POST['class_id'];

    foreach ($student_ids as $index => $student_id) {
        $score = $scores[$index];
        list($grade, $status) = calculateGradeAndStatus($score);

        $sql_update = "UPDATE transcript SET score = ?, grade = ?, status = ? WHERE student_id = ? AND class_id = ?";
        $params_update = array($score, $grade, $status, $student_id, $class_id);
        $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);

        if ($stmt_update === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }
    echo "<div class='success'>Điểm đã được cập nhật thành công!</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật điểm cho sinh viên</title>
    <style >
            /* General Styles */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                line-height: 1.6em;
                background-color: #f9f9f9;
                color: #333;
                padding-top: 70px; /* Khoảng trống cho navbar */
            }

            /* Container Styling */
            .containerr {
                max-width: 1200px;
                margin: 0 auto;
                padding: 20px;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            /* Form Styling */
            form {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }
            form {
                margin-bottom: 20px; /* Thêm khoảng cách giữa các form */
            }

            form button {
                display: block; /* Hiển thị nút trên dòng riêng */
                margin-top: 10px; /* Thêm khoảng cách với các phần tử phía trên */
            }

            form label {
                font-weight: bold;
                margin-bottom: 5px;
                font-size: 1.1em;
                color: #333;
            }

            form input, form select, form textarea {
                padding: 12px;
                border: 1px solid #ddd;
                border-radius: 5px;
                width: 100%;
                font-size: 1em;
                background-color: #f9f9f9;
                transition: border-color 0.3s ease;
            }

            form input:focus, form select:focus {
                border-color: #4CAF50;
                outline: none;
            }

            /* Button Styling */
            form button {
                padding: 12px;
                background: #333;
                color: #fff;
                font-weight: bold;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background 0.3s ease, color 0.3s;
            }

            form button:hover {
                background: #444;
                color: #f7c08a;
            }

            /* Table Styling */
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            table th, table td {
                text-align: center;
                padding: 10px;
                border: 1px solid #ddd;
            }

            table th {
                background: #f3f3f3;
                font-weight: bold;
            }

            table tr:nth-child(even) {
                background: #f9f9f9;
            }

            table tr:hover {
                background: #f0f0f0;
            }

            /* Success Message */
            .success {
                color: green;
                font-weight: bold;
                margin-top: 20px;
            }

    </style>

</head>
<body>
<div class="containerr">
    <h2>Cập nhật điểm cho sinh viên</h2>
    <form method="POST" action="" class="form">
        <label for="semester_id">Chọn kỳ học:</label>
        <select name="semester_id" id="semester_id" required>
            <?php while ($row = sqlsrv_fetch_array($stmt_semesters, SQLSRV_FETCH_ASSOC)): ?>
                <option value="<?= $row['semester_id'] ?>"><?= $row['semester_name'] ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Lựa chọn kỳ học</button>
    </form>

    <?php if (isset($stmt_classes)): ?>
        <form method="POST" action="" class="form">
            <label for="class_id">Chọn lớp học:</label>
            <select name="class_id" id="class_id" required>
                <?php while ($row_class = sqlsrv_fetch_array($stmt_classes, SQLSRV_FETCH_ASSOC)): ?>
                    <option value="<?= $row_class['class_id'] ?>">Lớp <?= $row_class['class_id'] ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Lựa chọn lớp học</button>
        </form>
    <?php endif; ?>

    <?php if (isset($stmt_students)): ?>
        <form method="POST" action="" class="form">
            <table class="table">
                <tr>
                    <th>Mã Sinh Viên</th>
                    <th>Điểm</th>
                </tr>
                <?php while ($row_student = sqlsrv_fetch_array($stmt_students, SQLSRV_FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $row_student['student_id'] ?></td>
                        <td>
                            <input type="hidden" name="student_id[]" value="<?= $row_student['student_id'] ?>">
                            <input type="number" step="0.1" name="score[]" value="<?= $row_student['score'] ?>" required>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <input type="hidden" name="class_id" value="<?= $class_id ?>">
            <button type="submit" name="submit_grades">Cập nhật điểm</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
