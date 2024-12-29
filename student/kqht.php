<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả học tập</title>
    <link rel="stylesheet" href="../src/css/app.css">
</head>
<body>
    <?php
    include_once "nav_bar.php";
    include_once "../connection.php";
    global $conn;
    ?>

    <h3>Tra cứu kết quả học tập</h3>

    <form method="POST" action="">
        <label for="hocki">Nhập học kỳ (vd: 2023.2): </label>
        <input type="text" id="hocki" name="hocki" required>
        <button type="submit" name="submit">Xem kết quả</button>
    </form>

    <?php
    if (isset($_POST['submit'])) {
        $hocki = $_POST['hocki']; // Nhận học kỳ từ form
        $student_id = $_SESSION['user_id']; // Lấy mã sinh viên từ session

        // Truy vấn kết quả học tập
        $sql = "SELECT t.subject_id, s.subject_name, t.score, t.grade
                FROM transcript t
                JOIN subject s ON t.subject_id = s.subject_id
                WHERE t.semester_id = ? AND t.student_id = ?";
        $params = [$hocki, $student_id];
        $stmt = sqlsrv_prepare($conn, $sql, $params);

        if ($stmt && sqlsrv_execute($stmt)) {
            echo "<h3>Kết quả học tập học kỳ: $hocki</h3>";
            echo "<table border='1'>
                    <tr>
                        <th>Tên môn học</th>
                        <th>Điểm</th>
                        <th>Xếp loại</th>
                    </tr>";

            $has_results = false;
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $has_results = true;
                echo "<tr>
                        <td>" . htmlspecialchars($row['subject_name']) . "</td>
                        <td>" . htmlspecialchars($row['score']) . "</td>
                        <td>" . htmlspecialchars($row['grade']) . "</td>
                      </tr>";
            }

            if (!$has_results) {
                echo "<tr><td colspan='3'>Không có kết quả học tập cho học kỳ này.</td></tr>";
            }

            echo "</table>";
        } else {
            echo "<p>Không thể lấy dữ liệu. Vui lòng kiểm tra thông tin hoặc thử lại sau.</p>";
        }

        sqlsrv_free_stmt($stmt);
    }
    ?>

    <?php sqlsrv_close($conn); ?>
    <?php include_once "../footer.php";?>

</body>
</html>
