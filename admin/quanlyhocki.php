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
</head>
<body>
<div id="menu">
    <ul id="ls">
        <li><a href="./them_ki_hoc.php">Thêm kỳ học</a></li>
        <li><a href="./xoa_ki_hoc.php">Xóa kỳ học</a></li>
        <li><a href="./cap_nhat_ki_hoc.php">Cập nhật kỳ học</a>
        </li>
    </ul>
</div>

<div id="semester-list">
    <h2>Danh sách kỳ học</h2>
    <table border="1">
        <thead>
        <tr>
            <th>Mã kỳ học</th>
            <th>Tên kỳ học</th>
            <th>Ngày bắt đầu</th>
            <th>Ngày kết thúc</th>
            <th>Trạng thái</th>
            <th>Quản lý đăng ký</th>
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
            echo "<td>" . $row['begin_date']->format('Y-m-d') . "</td>";
            echo "<td>" . $row['end_date']->format('Y-m-d') . "</td>";
            echo "<td>" . $row['status'] . "</td>";

            // Add button for managing registration if status is "Mở đăng ký"
            if ($row['status'] == 'Mở đăng ký') {
                echo "<td><a href='./quan_ly_dang_ky.php?semester_id=" . $row['semester_id'] . "'>Quản lý đăng ký</a></td>";
            } else {
                echo "<td></td>";
            }

            echo "</tr>";
        }

        sqlsrv_free_stmt($stmt);
        ?>
        </tbody>
    </table>
</div>

<?php include_once "../footer.php"; ?>
</body>
</html>
