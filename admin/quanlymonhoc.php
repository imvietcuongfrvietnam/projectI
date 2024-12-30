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
    <link rel="stylesheet" href="../src/css/app.css">
</head>
<body>
<div id="menu">
    <ul id="ls">
        <li><a href="./them_mon_hoc.php">Thêm môn học</a></li>
        <li><a href="./xoa_mon_hoc.php">Xóa môn học</a></li>
        <li><a href="./cap_nhat_mon_hoc.php">Cập nhật môn học</a></li>
    </ul>
</div>

<div id="subject-list">
    <h2>Danh sách môn học</h2>
    <table border="1">
        <thead>
        <tr>
            <th>Mã môn học</th>
            <th>Tên môn học</th>
            <th>Số tín chỉ</th>
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
