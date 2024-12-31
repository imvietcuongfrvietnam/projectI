<?php
session_start(); // Sử dụng session để lưu thông báo lỗi (nếu có)

// Lấy thông báo lỗi (nếu tồn tại)
$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
unset($_SESSION['error']); // Xóa thông báo sau khi hiển thị
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập hệ thống</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.7em;
        }

        .form-container {
            margin: 30px auto;
            max-width: 400px;
            padding: 20px;
        }

        .form-wrap {
            background: #fff;
            padding: 15px 25px;
            color: #333;
        }

        .form-wrap h2, .form-wrap p {
            text-align: center;
        }

        .form-wrap .form-group {
            margin-top: 15px;
        }

        .form-wrap .form-group label {
            display: block;
            color: #666;
        }

        .form-wrap .form-group input {
            width: 100%;
            padding: 10px 10px;
            border: #ddd 1px solid;
            border-radius: 5px;
        }

        .form-wrap button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background: #333;
            color: #fff;
        }

        .form-wrap button:hover {
            cursor: pointer;
            background: #444;
            color: #f7c08a;
        }

        .form-wrap .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <div class="form-wrap">
        <h2>Đăng nhập hệ thống</h2>

        <!-- Hiển thị thông báo lỗi -->
        <?php if ($error): ?>
            <div class="error">
                Unable to login. Check your username and password.
            </div>
        <?php endif; ?>

        <!-- Form đăng nhập -->
        <form id="loginForm" method="POST" action="helper/process_login.php">
            <div class="form-group">
                <label for="username">Username: </label>
                <input id="username" name="username" type="text" required/><br/>
            </div>
            <div class="form-group">
                <label for="password">Password: </label>
                <input id="password" name="password" type="password" required/><br/>
            </div>
            <button type="submit" name="submit" value="Login">Đăng nhập</button>

        </form>
    </div>
</div>
</body>
</html>

