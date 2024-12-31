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

</head>
<body>
<?php
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;
?>
<div id="menu">
    <ul id="ls">
        <li><a href="./them_giang_vien.php">Thêm giảng viên</a></li>
        <li><a href="./xoa_giang_vien.php">Xóa giảng viên</a></li>
        <li><a href="./cap_nhat_giang_vien.php">Cập nhật thông tin giảng viên</a></li>
    </ul>
</div>
<div id="teacher-list">
    <h2>Danh sách giảng viên</h2>
    <table border="1">
        <thead>
        <tr>
            <th>Mã giảng viên</th>
            <th>Tên giảng viên</th>
            <th>Ngày sinh</th>
            <th>Email</th>
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
            echo "<td>" . $row['teacher_dob']->format('Y-m-d') . "</td>"; // Format ngày sinh
            echo "<td>" . $row['email'] . "</td>";
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
