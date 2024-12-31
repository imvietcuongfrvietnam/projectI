<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sinh viên</title>
    <link rel="stylesheet" href="../src/css/app.css">
</head>
<body>
<?php
include_once "nav_bar.php";
include_once "../connection.php";
global $conn;


?>
<div id="menu">
    <ul id="ls">
        <li><a href="./them_sinh_vien.php">Thêm sinh viên</a></li>
        <li><a href="./xoa_sinh_vien.php">Xóa sinh viên</a></li>
        <li><a href="./cap_nhat_sinh_vien.php">Cập nhật thông tin sinh viên</a></li>
        <li><a href="./kqht_sinh_vien.php">Tra cứu kết quả học tập sinh viên</a></li>
    </ul>
</div>
<div id="student-list">
    <h2>Danh sách sinh viên</h2>
    <table border="1">
        <thead>
        <tr>
            <th>MSSV</th>
            <th>Tên sinh viên</th>
            <th>Ngày sinh</th>
            <th>Email</th>
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
            echo "<td>" . $row['student_dob']->format('Y-m-d') . "</td>"; // Format ngày sinh
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
