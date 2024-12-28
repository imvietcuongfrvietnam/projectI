<?php
global $serverName, $connection;
session_start();

if (isset($_POST["submit"])) {
    require_once("../connection.php");

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kết nối CSDL
    $conn = sqlsrv_connect($serverName, $connection);
    if (!$conn) {
        die("Database connection failed: " . print_r(sqlsrv_errors(), true));
    }

    // Sử dụng prepared statement để tránh SQL Injection
    $sql = "SELECT * FROM [user] WHERE username = ?";
    $stmt = sqlsrv_prepare($conn, $sql, [$username]);

    if (!$stmt || !sqlsrv_execute($stmt)) {
        echo "Error executing SQL statement.";
        exit();
    }

    // Lấy thông tin người dùng
    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    // Debugging: In ra thông tin người dùng
    echo "<pre>";
    print_r($user);  // In thông tin người dùng lấy từ cơ sở dữ liệu
    echo "</pre>";

    // Kiểm tra nếu người dùng tồn tại và mật khẩu đúng
    if ($user) {
        // So sánh mật khẩu
        if ($password==$user['password']) {
            // Lưu thông tin vào session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];

            // Chuyển hướng tới trang role-specific
            $role = $user['role'];
            header("Location: ../$role/index.php");
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }
}
?>
