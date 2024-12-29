<footer>
    <style>
        /* Footer Container */
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .row {
            display: flex;  /* Sử dụng flexbox để các cột hiển thị ngang */
            justify-content: space-between; /* Phân chia các cột đều nhau */
            flex-wrap: wrap;  /* Cho phép các phần tử xuống dòng khi không đủ chỗ */
        }

        .footer-col {
            flex: 1;  /* Mỗi cột chiếm 1 phần bằng nhau */
            margin: 0 15px;
            min-width: 200px; /* Đảm bảo các cột không quá hẹp */
        }

        .footer-col h3 {
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: bold;
        }

        /* Căn lề trái cho tất cả các đoạn văn bản và liên kết */
        .footer-col p, .footer-col ul {
            font-size: 14px;
            text-align: left;  /* Căn trái */
        }

        .footer-col ul {
            list-style-type: none;
            padding: 0;
        }

        .footer-col ul li {
            margin-bottom: 10px;
        }

        .footer-col ul li a {
            color: #f7c08a;
            text-decoration: none;
        }

        .footer-col ul li a:hover {
            text-decoration: underline;
        }

        /* Đảm bảo rằng email có màu khác để dễ nhìn */
        .footer-col a {
            color: #f7c08a;
        }

        .footer-col a:hover {
            text-decoration: underline;
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
    </style>

    <div class="footer-container">
        <div class="row">
            <!-- Liên hệ -->
            <div class="footer-col">
                <h3>Liên hệ</h3>
                <p>Phòng Quản lý Đào tạo</p>
                <p>Địa chỉ: Tòa nhà A, Đại học XYZ</p>
                <p>Điện thoại: +84 123 456 789</p>
                <p>Email: <a href="mailto:ql_daotao@xyz.edu.vn">ql_daotao@xyz.edu.vn</a></p>
            </div>
            <!-- Hỗ trợ -->
            <div class="footer-col">
                <h3>Hỗ trợ</h3>
                <ul>
                    <li><a href="/student-guide">Hướng dẫn sử dụng</a></li>
                    <li><a href="/faq">Câu hỏi thường gặp</a></li>
                </ul>
            </div>
            <!-- Chính sách -->
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
