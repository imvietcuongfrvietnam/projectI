<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = $_POST['student_id'];
    $semester_id = $_POST['semester_id'];

    // Truy vấn kết quả học tập
    $sql = "SELECT t.subject_id, s.subject_name, t.score, t.grade, t.status
            FROM transcript t
            JOIN subject s ON t.subject_id = s.subject_id
            WHERE t.student_id = ? AND t.semester_id = ?";
    $params = array($student_id, $semester_id);
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if (sqlsrv_execute($stmt)) {
        echo "<h3>Kết quả học tập của sinh viên MSSV: $student_id, Kỳ học: $semester_id</h3>";
        echo "<table border='1'>
                <tr>
                    <th>Mã môn học</th>
                    <th>Tên môn học</th>
                    <th>Điểm</th>
                    <th>Xếp loại</th>
                    <th>Trạng thái</th>
                </tr>";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['subject_id']}</td>
                    <td>{$row['subject_name']}</td>
                    <td>{$row['score']}</td>
                    <td>{$row['grade']}</td>
                    <td>{$row['status']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Lỗi: " . print_r(sqlsrv_errors(), true) . "</p>";
    }

    sqlsrv_free_stmt($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tra cứu kết quả học tập</title>
    <link rel="stylesheet" href="../src/css/form.css"> <!-- Liên kết file CSS -->

</head>
<body>
<div class="form-container">
<form method="POST" action="">
    <label for="student_id">Nhập MSSV:</label>
    <input type="text" id="student_id" name="student_id" placeholder="Nhập Mã số sinh viên" required>
    <br><br>
    <label for="semester_id">Nhập kỳ học:</label>
    <input type="text" id="semester_id" name="semester_id" placeholder="Nhập kỳ học" required>
    <br><br>
    <button type="submit">Tra cứu</button>
</form>
</div>
<?php include_once "../footer.php"; ?>
</body>
</html>
