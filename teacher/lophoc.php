<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch dạy</title>
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

        /* Container Styling */
        .form-container {
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
            margin-bottom: 30px;
        }

        form label {
            font-weight: bold;
            font-size: 1.1em;
            color: #333;
        }

        form input, form select {
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
<div class="form-container">
    <main>
        <?php
        // Form nhập học kỳ
        echo '<form action="" method="POST">';
        echo 'Nhập học kì: <input type="text" name="hocki" required>';
        echo '<button type="submit">Tìm lịch dạy</button>';
        echo '</form>';

        if (isset($_POST['hocki'])) {
            // Lấy giá trị học kỳ từ form
            $hocki = $_POST['hocki'];

            // Truy vấn lấy lịch dạy theo học kỳ và giảng viên
            $sql = "
        SELECT c.class_id, c.subject_id, s.subject_name, s.credit, 
               sc.day_of_week, sc.time_start, sc.time_end, sc.location
        FROM schedule sc
        JOIN class c ON sc.class_id = c.class_id
        JOIN subject s ON c.subject_id = s.subject_id
        WHERE c.semester_id = ? 
        AND c.teacher_id = ?";

            // Tạo mảng tham số để chuẩn bị truy vấn
            $param = [$hocki, $_SESSION['user_id']];

            // Chuẩn bị và thực thi truy vấn
            $stmt = sqlsrv_prepare($conn, $sql, $param);
            if ($stmt && sqlsrv_execute($stmt)) {
                // Kiểm tra nếu có kết quả trả về
                $rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
                if ($rows) {
                    // Hiển thị lịch dạy nếu có lớp
                    echo '<table border="1">';
                    echo '<tr>
                        <th>Mã lớp</th>
                        <th>Mã môn học</th>
                        <th>Tên môn học</th>
                        <th>Số tín chỉ</th>
                        <th>Ngày học</th>
                        <th>Thời gian bắt đầu</th>
                        <th>Thời gian kết thúc</th>
                        <th>Địa điểm</th>
                      </tr>';

                    do {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($rows['class_id']) . '</td>';
                        echo '<td>' . htmlspecialchars($rows['subject_id']) . '</td>';
                        echo '<td>' . htmlspecialchars($rows['subject_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($rows['credit']) . '</td>';
                        echo '<td>' . htmlspecialchars($rows['day_of_week']) . '</td>';
                        echo '<td>' . htmlspecialchars($rows['time_start']->format('H:i:s')) . '</td>';
                        echo '<td>' . htmlspecialchars($rows['time_end']->format('H:i:s')) . '</td>';
                        echo '<td>' . htmlspecialchars($rows['location']) . '</td>';
                        echo '</tr>';
                    } while ($rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC));

                    echo '</table>';
                } else {
                    echo '<p class="no-result">Không có lớp dạy nào trong học kỳ này.</p>';
                }
            } else {
                echo '<p>Lỗi khi thực hiện truy vấn.</p>';
            }

            // Giải phóng tài nguyên truy vấn
            sqlsrv_free_stmt($stmt);
        }
        ?>
    </main>
</div>

<?php include_once "../footer.php";?>
</body>
</html>
