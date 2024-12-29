<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin giảng viên</title>
    <link rel="stylesheet" href="../src/css/app.css">
</head>
<body>
<?php
include_once "nav_bar.php"; // Thêm thanh điều hướng
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
            echo "Mã giảng viên: " . htmlspecialchars($res['teacher_id']) . "<br>";
            echo "Tên giảng viên: " . htmlspecialchars($res['teacher_name']) . "<br>";
            echo "Ngày sinh: " . $res['teacher_dob']->format('Y-m-d') . "<br>";
            echo "Email: " . htmlspecialchars($res['email']) . "<br>";
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

<?php include_once "../footer.php"?>
</body>
</html>
