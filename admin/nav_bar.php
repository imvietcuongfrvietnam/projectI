<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Admin</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .nav_bar {
            background-color: #333;
            padding: 10px 0;
        }

        #nav {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        #logo img {
            width: 100px;
            height: auto;
        }

        #menu_admin {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        #menu_admin li {
            margin-left: 20px;
        }

        #menu_admin a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            padding: 10px;
            transition: background-color 0.3s;
        }

        #menu_admin a:hover {
            background-color: #555;
            border-radius: 5px;
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            #menu_admin {
                flex-direction: column;
                text-align: center;
            }

            #menu_admin li {
                margin-left: 0;
                margin-bottom: 10px;
            }
        }
    </style>
    <link rel="stylesheet" href="../src/css/app.css">
</head>
<body>

    <nav id="navbar">
        <div class="container">
        <ul id="menu_admin">
            <li><a href="./quanlysinhvien.php">Quản lý sinh viên</a></li>
            <li><a href="./quanlygiangvien.php">Quản lý giảng viên</a></li>
            <li><a href="./quanlyhocki.php">Mở học kì</a></li>
            <li><a href="./quanlymonhoc.php">Quản lý môn học</a></li>
            <li><a href="../logout.php">Đăng xuất</a></li>
        </ul>
            </div>
    </nav>

</body>
</html>
