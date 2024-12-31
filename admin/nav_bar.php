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

        /* Đặt navbar cố định ở đầu trang */
        #navbar {
            position: fixed; /* Đảm bảo navbar luôn cố định */
            top: 0;
            left: 0;
            width: 100%;
            background-color: #333; /* Màu nền cho navbar */
            z-index: 1000; /* Đảm bảo navbar luôn nằm trên các phần tử khác */
            padding: 10px 0; /* Thêm padding cho navbar */
        }

        #menu_admin {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center; /* Canh giữa các mục trong navbar */
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

        /* Thêm padding cho body để tránh navbar che mất nội dung */
        body {
            padding-top: 30px; /* Thêm khoảng cách phía trên body để không bị che bởi navbar */
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
