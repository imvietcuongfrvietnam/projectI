<?php
global $conn;
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký lớp</title>
    <link rel="stylesheet" href="../src/css/app.css">
    <style>
        /* Định dạng chung cho các nút */
        .button, .table-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333; /* Màu nền đen */
            color: white; /* Màu chữ trắng */
            text-decoration: none; /* Xóa gạch chân */
            border-radius: 5px; /* Bo tròn góc */
            font-weight: bold; /* Chữ đậm */
            transition: background-color 0.3s, transform 0.2s; /* Hiệu ứng */
            margin: 5px;
        }

        .button:hover, .table-button:hover {
            background-color: #444; /* Màu nền khi hover */
            transform: scale(1.05); /* Tăng kích thước nhẹ khi hover */
        }

        /* Cái này áp dụng cho button trong bảng */
        table button {
            padding: 8px 15px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
            cursor: pointer;
        }

        table button:hover {
            background-color: #444;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<?php
include_once "nav_bar.php";
include_once "../connection.php";

$student_id = $_SESSION["user_id"]; // Mã sinh viên từ session

// Lấy học kỳ đang mở đăng ký
$sql = "SELECT * FROM semester WHERE status = N'Mở đăng ký'";
$stmt = sqlsrv_query($conn, $sql);

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true)); // Nếu truy vấn không thành công, in lỗi
}

$semester = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if ($semester) {
    $semester_id = $semester['semester_id'];
    $semester_name = $semester['semester_name'];

    echo "<h3>Học kỳ mở đăng ký: $semester_name</h3>";

    // Lấy danh sách lớp học của học kỳ đang mở
    $sql_classes = "SELECT c.class_id, s.subject_name
                    FROM class c 
                    JOIN subject s ON c.subject_id = s.subject_id
                    WHERE c.semester_id = ?";
    $stmt_classes = sqlsrv_prepare($conn, $sql_classes, array($semester_id));

    if (sqlsrv_execute($stmt_classes)) {
        echo "<form method='POST' action='./register_class.php'>"; // Chuyển đến xử lý đăng ký
        echo "<table>";
        echo "<tr><th>Mã lớp</th><th>Tên môn học</th><th>Đăng ký</th></tr>";

        while ($row = sqlsrv_fetch_array($stmt_classes, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['class_id'] . "</td>";
            echo "<td>" . $row['subject_name'] . "</td>";
            echo "<td><button type='submit' name='class_id' value='" . $row['class_id'] . "'>Đăng ký</button></td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "</form>";
    } else {
        echo "Không có lớp học nào trong học kỳ này.";
    }
} else {
    echo "Hiện tại không có học kỳ nào mở đăng ký.";
}

// Kiểm tra trước khi giải phóng câu lệnh
if (isset($stmt)) {
    sqlsrv_free_stmt($stmt);
}
if (isset($stmt_classes) && $stmt_classes !== false) {
    sqlsrv_free_stmt($stmt_classes);
}
sqlsrv_close($conn);
?>

<?php include_once "../footer.php";?>
</body>
</html>
