<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?include_once "../header.php"?></title>
</head>
<body>
    <?php include_once "nav_bar.php"?>
    <form action="AdminController.php?action=add" method = "POST">
           <!-- Nhập họ tên -->
    <label for="fullname">Họ và Tên:</label>
    <input type="text" id="fullname" name="fullname" placeholder="Nhập họ và tên" required> 
    <br><br>
    <label for="email">Email cấp: </label>
    <input type="text" id="email" placeholder="Nhập email cấp cho sinh viên" required>
    <br><br>
    <label for="dob">Ngày sinh: </label>
    <input type="text" id="dob" placeholder="Nhập ngày sinh sinh viên" required>
    <br><br>
    <label for="student_id">Mã số sinh viên cấp: </label>
    <input type="text" id="student_id" placeholder = "Nhập mã sinh viên cấp"required>
    <br><br>

    </form>
</body>
</html>