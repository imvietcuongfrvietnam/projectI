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
    <style>
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

        /* Header Styling */
        h2 {
            font-size: 2.5em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        /* Container Styling */
        .form-container {
            max-width: 1000px;
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
            margin-bottom: 30px;
        }

        form label {
            font-weight: bold;
            font-size: 1.1em;
            color: #333;
        }

        form select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            font-size: 1em;
            background-color: #f9f9f9;
        }

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

        /* Success/Error Message Styling */
        p {
            font-weight: bold;
            color: #d9534f; /* Red for error */
        }

        p.success {
            color: green;
        }

        p.no-result {
            color: #f0ad4e;
        }
    </style>
</head>
<body>
<?php include_once "nav_bar.php"; ?>

<div class="form-container">
    <h2>Danh sách lớp học của bạn</h2>

    <!-- Form chọn học kỳ -->
    <form method="POST" action="">
        <label for="semester_id">Chọn học kỳ:</label>
        <select name="semester_id" id="semester_id">
            <option value="">Chọn học kỳ</option>
            <?php
            // Truy vấn để lấy danh sách semester_id
            $sql = "SELECT semester_id, semester_name FROM semester";
            $stmt = sqlsrv_query($conn, $sql);

            if ($stmt) {
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $selected = isset($semester_id) && $semester_id == $row['semester_id'] ? "selected" : "";
                    echo "<option value='" . $row['semester_id'] . "' $selected>" . $row['semester_name'] . "</option>";
                }
                sqlsrv_free_stmt($stmt);
            } else {
                echo "<option value=''>Không có học kỳ</option>";
            }
            ?>
        </select>
        <button type="submit">Lọc lớp học</button>
    </form>

    <?php
    // Nếu đã chọn học kỳ, tiếp tục thực hiện truy vấn lọc các lớp học đã đăng ký của sinh viên trong học kỳ đó
    if ($semester_id) {
        $sql = "SELECT c.class_id, s.subject_name, t.teacher_name, sch.day_of_week, sch.time_start, sch.time_end 
                    FROM enroll e
                    JOIN class c ON e.class_id = c.class_id
                    JOIN subject s ON c.subject_id = s.subject_id
                    JOIN teacher t ON c.teacher_id = t.teacher_id
                    JOIN schedule sch ON c.class_id = sch.class_id
                    WHERE e.student_id = ? 
                    AND e.status = 'Thành công'
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
                                <th>Giờ bắt đầu</th>
                                <th>Giờ kết thúc</th>
                            </tr>";

                // Hiển thị các lớp đã đăng ký
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>
                                <td>" . $row['class_id'] . "</td>
                                <td>" . $row['subject_name'] . "</td>
                                <td>" . $row['teacher_name'] . "</td>
                                <td>" . $row['day_of_week'] . "</td>
                                <td>" . $row['time_start']->format('H:i') . "</td> <!-- Hiển thị giờ bắt đầu --> 
                                <td>" . $row['time_end']->format('H:i') . "</td> <!-- Hiển thị giờ kết thúc -->
                            </tr>";
                }

                echo "</table>";
            } else {
                echo "<p>Không có lớp học nào trong học kỳ này.</p>";
            }
        } else {
            echo "<p>Có lỗi xảy ra khi truy vấn dữ liệu!</p>";
        }

        sqlsrv_free_stmt($stmt);
    }
    ?>
</div>

<?php sqlsrv_close($conn); ?>
<?php include_once "../footer.php"; ?>

</body>
</html>
