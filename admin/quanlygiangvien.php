<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý giảng viên</title>
    <link rel="stylesheet" href="../src/css/form.css"> <!-- Liên kết file CSS -->
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

        /* Căn chỉnh nút Thêm giảng viên ở giữa */
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

<div id="teacher-list">
    <h2>Danh sách giảng viên</h2>
    <table>
        <thead>
        <tr>
            <th>Mã giảng viên</th>
            <th>Tên giảng viên</th>
            <th>Ngày sinh</th>
            <th>Email</th>
            <th>Chức năng</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT teacher_id, teacher_name, teacher_dob, email FROM teacher";
        $stmt = sqlsrv_query($conn, $sql);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['teacher_id'] . "</td>";
            echo "<td>" . $row['teacher_name'] . "</td>";
            echo "<td>" . $row['teacher_dob']->format('d-m-Y') . "</td>"; // Format ngày sinh
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>";
            echo "<a href='./cap_nhat_giang_vien.php?teacher_id=" . $row['teacher_id'] . "' class='table-button'>Cập nhật</a> | ";
            echo "<a href='./xoa_giang_vien.php?teacher_id=" . $row['teacher_id'] . "' class='table-button'>Xóa</a>";
            echo "</td>";
            echo "</tr>";
        }

        sqlsrv_free_stmt($stmt);
        ?>
        </tbody>
    </table>
</div>

<div class="add-button-container">
    <a href="./them_giang_vien.php" class="button">Thêm giảng viên</a>
</div>

<?php include_once "../footer.php"; ?>
</body>
</html>
