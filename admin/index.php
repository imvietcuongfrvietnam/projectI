<?php session_start();?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang quản lý</title>
    <link rel="stylesheet" href="../src/css/app.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
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

        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #333;
        }

        .content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            margin-top: 20px;
        }

        .content p {
            font-size: 1.2em;
            line-height: 1.6;
            color: #555;
        }

        .content ul {
            list-style-type: none;
            padding: 0;
            margin: 20px 0;
        }

        .content ul li {
            font-size: 1.2em;
            padding: 5px 0;
            color: #555;
        }

        .content ul li::before {
            content: "✔️";
            margin-right: 10px;
        }

    </style>
</head>
<body>
<?php
include_once "./nav_bar.php";?>

<main>
    <h1>Chào mừng đến với trang quản lý</h1>
    <div class="content">
        <p>Các chức năng khả dụng hiện tại:</p>
        <ul>
            <li>Quản lý sinh viên</li>
            <li>Quản lý giảng viên</li>
            <li>Quản lý học kỳ</li>
            <li>Quản lý môn học</li>
        </ul>
    </div>
</main>

<?php include_once "../footer.php"; ?>
</body>
</html>
