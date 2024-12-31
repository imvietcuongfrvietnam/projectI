<?php
session_start();
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý môn học</title>
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

        /* Căn chỉnh nút Thêm môn học ở dưới */
        .add-button-container {
            text-align: center;
            margin-top: 20px;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
        }

        .action-buttons a {
            margin: 0 5px;
        }
    </style>
</head>
<body>

<div id="subject-list">
    <h2>Danh sách môn học</h2>
    <table>
        <thead>
        <tr>
            <th>Mã môn học</th>
            <th>Tên môn học</th>
            <th>Số tín chỉ</th>
            <th>Chức năng</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT subject_id, subject_name, credit FROM subject";
        $stmt = sqlsrv_query($conn, $sql);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['subject_id'] . "</td>";
            echo "<td>" . $row['subject_name'] . "</td>";
            echo "<td>" . $row['credit'] . "</td>";
            echo "<td class='action-buttons'>";
            echo "<a href='./cap_nhat_mon_hoc.php?subject_id=" . $row['subject_id'] . "' class='button'>Cập nhật</a>";
            echo "<a href='./xoa_mon_hoc.php?subject_id=" . $row['subject_id'] . "' class='button'>Xóa</a>";
            echo "</td>";
            echo "</tr>";
        }

        sqlsrv_free_stmt($stmt);
        ?>
        </tbody>
    </table>
</div>

<div class="add-button-container">
    <a href="./them_mon_hoc.php" class="button">Thêm môn học</a>
</div>

<?php include_once "../footer.php"; ?>
</body>
</html>
