<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        main {
            flex: 1;
        }

        footer {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .footer-col {
            flex: 1;
            margin: 0 15px;
            min-width: 200px;
        }

        .footer-col h3 {
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: bold;
        }

        .footer-col ul {
            list-style-type: none;
            padding: 0;
        }

        .footer-col ul li {
            margin-bottom: 8px;
        }

        .footer-col ul li a {
            color: #f7c08a;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-col ul li a:hover {
            text-decoration: underline;
            color: #ffc870;
        }

        hr {
            border: 1px solid #444;
            margin-top: 20px;
        }

        footer p {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .row {
                flex-direction: column;
                align-items: center;
            }

            .footer-col {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
<main>
</main>
<footer>
    <div class="footer-container">
        <div class="row">
            <div class="footer-col">
                <h3>Liên hệ</h3>
                <p>Phòng Quản lý Đào tạo</p>
                <p>Địa chỉ: Tòa nhà A, Đại học XYZ</p>
                <p>Điện thoại: +84 123 456 789</p>
                <p>Email: <a href="mailto:ql_daotao@xyz.edu.vn">ql_daotao@xyz.edu.vn</a></p>
            </div>
            <div class="footer-col">
                <h3>Hỗ trợ</h3>
                <ul>
                    <li><a href="/student-guide">Hướng dẫn sử dụng</a></li>
                    <li><a href="/faq">Câu hỏi thường gặp</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Chính sách</h3>
                <ul>
                    <li><a href="/privacy-policy">Chính sách bảo mật</a></li>
                </ul>
            </div>
        </div>
        <hr>
        <p>© 2024 Hệ thống Quản lý Đào tạo - Đại học XYZ. Mọi quyền được bảo lưu.</p>
    </div>
</footer>
</html>
