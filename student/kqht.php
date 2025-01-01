<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả học tập</title>
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
        h3 {
            font-size: 2em;
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

        form input {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            font-size: 1em;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        form input:focus {
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
<?php
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;
?>

<div class="form-container">
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
</div>

<?php sqlsrv_close($conn); ?>
<?php include_once "../footer.php";?>
</body>
</html>
