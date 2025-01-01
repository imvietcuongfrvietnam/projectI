<?php
global $conn;
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin sinh viên</title>
    <link rel="stylesheet" href="../src/css/app.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
            padding: 20px;
        }

        h2 {
            color: #333;
            font-size: 2em;
            margin-bottom: 30px;
        }

        .student-info {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: left;
        }

        .student-info p {
            font-size: 1.2em;
            line-height: 1.6;
            color: #555;
            margin: 10px 0;
        }

        .student-info p span {
            font-weight: bold;
            color: #333;
        }

        .student-info p:last-child {
            margin-bottom: 0;
        }

        .student-info p::before {
            content: "📚 ";
        }
    </style>
</head>
<body>
<?php
include_once "nav_bar.php";
include_once "../connection.php";

// Lấy student_id từ session hoặc từ query string nếu cần
$student_id = $_SESSION['user_id']; // Hoặc lấy từ URL: $_GET['student_id']

// Truy vấn thông tin sinh viên từ cơ sở dữ liệu
$sql = "SELECT * FROM student WHERE student_id = ?";
$stmt = sqlsrv_prepare($conn, $sql, array($student_id));

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true)); // Xử lý lỗi nếu query không thành công
}

// Thực thi truy vấn
$result = sqlsrv_execute($stmt);
if (!$result) {
    die(print_r(sqlsrv_errors(), true)); // Xử lý lỗi nếu truy vấn không thành công
}

// Lấy kết quả và hiển thị
$student = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if ($student) {
    $mssv = $student['student_id'];
    $name = $student['student_name'];

    // Chuyển đổi ngày sinh từ định dạng SQL datetime thành đối tượng DateTime
    $dob = $student['student_dob'];
    $dob = $dob ? $dob->format('d-m-Y') : 'N/A'; // Kiểm tra và định dạng ngày sinh

    $email = $student['email'];
} else {
    echo "Không tìm thấy thông tin sinh viên.";
}
?>

<main>
    <h2>Thông tin sinh viên</h2>
    <div class="student-info">
        <p><span>Mã số sinh viên:</span> <?= htmlspecialchars($mssv); ?></p>
        <p><span>Họ tên:</span> <?= htmlspecialchars($name); ?></p>
        <p><span>Ngày sinh:</span> <?= htmlspecialchars($dob); ?></p>
        <p><span>Email:</span> <?= htmlspecialchars($email); ?></p>
    </div>
</main>

<?php
// Đóng kết nối sau khi sử dụng
sqlsrv_free_stmt($stmt); // Giải phóng tài nguyên bộ nhớ của truy vấn
sqlsrv_close($conn); // Đóng kết nối CSDL
?>

<?php include_once "../footer.php";?>
</body>
</html>
