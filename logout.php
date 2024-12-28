<?php
// Bắt đầu phiên làm việc
session_start();

// Xóa tất cả các biến trong session
$_SESSION = array();

// Nếu muốn hủy session cookie (nếu có), ta có thể làm như sau
if (ini_get("session.use_cookie")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// Hủy session
session_destroy();

// Chuyển hướng người dùng về trang đăng nhập hoặc trang chủ
header("Location: login.php");
exit();
?>
