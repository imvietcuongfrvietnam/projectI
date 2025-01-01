<?php
session_start();
include_once "nav_bar.php"; // Thêm thanh điều hướng
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin giảng viên</title>
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

        .teacher-info {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: left;
        }

        .teacher-info p {
            font-size: 1.2em;
            line-height: 1.6;
            color: #555;
            margin: 10px 0;
        }

        .teacher-info p span {
            font-weight: bold;
            color: #333;
        }

        .teacher-info p:last-child {
            margin-bottom: 0;
        }

        .teacher-info p::before {
            content: "👨‍🏫 ";
        }
    </style>
</head>
<body>
<main>
    <?php

    include_once "../connection.php"; // Kết nối CSDL
    global $conn;

    // Kiểm tra xem user_id có tồn tại trong session hay không (giảng viên đã đăng nhập)
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Lấy user_id từ session (có thể là teacher_id của giảng viên)

        // Tạo câu lệnh SQL và chuẩn bị truy vấn
        $sql = "SELECT * FROM teacher WHERE teacher_id = ?";
        $stmt = sqlsrv_prepare($conn, $sql, [$user_id]);

        // Kiểm tra và thực thi truy vấn
        if ($stmt && sqlsrv_execute($stmt)) {
            // Lấy kết quả truy vấn
            $res = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            if ($res) {
                // Hiển thị thông tin giảng viên
                echo "<h2>Thông tin giảng viên</h2>";
                echo "<div class='teacher-info'>";
                echo "<p><span>Mã giảng viên:</span> " . htmlspecialchars($res['teacher_id']) . "</p>";
                echo "<p><span>Tên giảng viên:</span> " . htmlspecialchars($res['teacher_name']) . "</p>";
                echo "<p><span>Ngày sinh:</span> " . $res['teacher_dob']->format('d-m-Y') . "</p>";
                echo "<p><span>Email:</span> " . htmlspecialchars($res['email']) . "</p>";
                echo "</div>";
            } else {
                // Không tìm thấy giảng viên
                echo "Không tìm thấy giảng viên với ID: " . htmlspecialchars($user_id);
            }
        } else {
            // Lỗi thực thi truy vấn
            echo "Lỗi truy vấn. Vui lòng thử lại.";
        }
    } else {
        // Trường hợp giảng viên chưa đăng nhập
        echo "Vui lòng đăng nhập để xem thông tin.";
    }

    sqlsrv_close($conn); // Đóng kết nối CSDL
    ?>
</main>

<?php include_once "../footer.php"?>
</body>
</html>
