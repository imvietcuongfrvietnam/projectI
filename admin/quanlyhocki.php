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
    <title>Quản lý kỳ học</title>
    <link rel="stylesheet" href="../src/css/app.css">
    <style>

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        .action-buttons a {
            display: inline-block;
            margin: 5px;
            padding: 5px 10px;
            color: white;
            background-color: #333;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .action-buttons a:hover {
            background-color: #444;
            color: #f7c08a;
        }

        .add-semester {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #333;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .add-semester:hover {
            background-color: #444;
            color: #f7c08a;
        }
    </style>
</head>
<body>
<div id="semester-list">
    <h2>Danh sách kỳ học</h2>
    <table>
        <thead>
        <tr>
            <th>Mã kỳ học</th>
            <th>Tên kỳ học</th>
            <th>Ngày bắt đầu</th>
            <th>Ngày kết thúc</th>
            <th>Trạng thái</th>
            <th>Chức năng</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT semester_id, semester_name, begin_date, end_date, status FROM semester";
        $stmt = sqlsrv_query($conn, $sql);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['semester_id'] . "</td>";
            echo "<td>" . $row['semester_name'] . "</td>";
            echo "<td>" . $row['begin_date']->format('d-m-Y') . "</td>";
            echo "<td>" . $row['end_date']->format('d-m-Y') . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td class='action-buttons'>";
            echo "<a href='./cap_nhat_ki_hoc.php?semester_id=" . $row['semester_id'] . "'>Sửa</a>";

            if ($row['status'] == 'Mở đăng ký') {
                echo "<a href='./quan_ly_dang_ky.php?semester_id=" . $row['semester_id'] . "'>Quản lý đăng ký</a>";
            }

            echo "</td>";
            echo "</tr>";
        }

        sqlsrv_free_stmt($stmt);
        ?>
        </tbody>
    </table>
</div>

<a class="add-semester" href="./them_ki_hoc.php">Thêm kỳ học</a>

<?php include_once "../footer.php"; ?>
</body>
</html>
