<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;

// Lấy MSSV từ session hoặc URL (giả sử MSSV là unique và đã được lưu trong session)
$student_id = $_SESSION['student_id'] ?? $_GET['student_id'] ?? null;

if (!$student_id) {
    echo "<p>Không tìm thấy thông tin sinh viên. Vui lòng đăng nhập lại.</p>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
    <style>
        .form-container {
            margin-bottom: 20px;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            width: 150px;
            margin: 0 auto;
        }
        .back-link:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
<div class="form-container">
    <form method="POST" action="">
        <label for="semester_id">Chọn kỳ học:</label>
        <select id="semester_id" name="semester_id" required>
            <option value="">-- Chọn kỳ học --</option>
            <?php
            // Lấy danh sách các kỳ học có dữ liệu
            $sql = "SELECT DISTINCT semester_id FROM transcript WHERE student_id = ?";
            $params = array($student_id);
            $stmt = sqlsrv_prepare($conn, $sql, $params);

            if (sqlsrv_execute($stmt)) {
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<option value='{$row['semester_id']}'>{$row['semester_id']}</option>";
                }
            }
            sqlsrv_free_stmt($stmt);
            ?>
        </select>
        <br><br>
        <button type="submit">Tra cứu</button>
    </form>
</div>


<?php include_once "../footer.php"; ?>
</body>
</html>
