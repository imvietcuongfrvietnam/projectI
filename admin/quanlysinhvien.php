<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sinh viên</title>
    <link rel="stylesheet" href="../src/css/app.css"> <!-- Liên kết CSS file -->
    <style>
        .button, .table-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333; /* Màu nền đen */
            color: white; /* Màu chữ trắng */
            text-decoration: none; /* Xóa gạch chân */
            border-radius: 5px; /* Bo tròn góc */
            font-weight: bold; /* Chữ đậm */
            transition: background-color 0.3s, transform 0.2s; /* Hiệu ứng */
            margin: 5px;
        }

        .button:hover, .table-button:hover {
            background-color: #444; /* Màu nền khi hover */
            transform: scale(1.05); /* Tăng kích thước nhẹ khi hover */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f4f4f4;
        }

        /* Căn chỉnh nút Thêm sinh viên ở giữa */
        .add-button-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;
?>

<div id="student-list">
    <h2>Danh sách sinh viên</h2>
    <table>
        <thead>
        <tr>
            <th>MSSV</th>
            <th>Tên sinh viên</th>
            <th>Ngày sinh</th>
            <th>Email</th>
            <th>Chức năng</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT student_id, student_name, student_dob, email FROM student";
        $stmt = sqlsrv_query($conn, $sql);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['student_id'] . "</td>";
            echo "<td>" . $row['student_name'] . "</td>";
            echo "<td>" . $row['student_dob']->format('d-m-Y') . "</td>"; // Format ngày sinh
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>";
            echo "<a href='./cap_nhat_sinh_vien.php?student_id=" . $row['student_id'] . "' class='table-button'>Sửa</a> | ";
            echo "<a href='./xoa_sinh_vien.php?student_id=" . $row['student_id'] . "' class='table-button'>Xóa</a> | ";
            echo "<a href='./kqht_sinh_vien.php?student_id=" . $row['student_id'] . "' class='table-button'>Xem kết quả</a>";
            echo "</td>";
            echo "</tr>";
        }

        sqlsrv_free_stmt($stmt);
        ?>
        </tbody>
    </table>
</div>
<div class="add-button-container">
    <a href="./them_sinh_vien.php" class="button">Thêm sinh viên</a>
</div>
<?php include_once "../footer.php"; ?>
</body>
</html>
