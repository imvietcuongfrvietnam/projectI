<?php
$serverName = "LAPTOP-6EEOSOCP\\SQLEXPRESS"; // Dùng dấu "\\" thay vì "\"
$database = "QLHT";
$uid = "sa";
$pass = "123456789";

// Cấu hình kết nối
$connection = [
    "Database" => $database,
    "UID"      => $uid,
    "PWD"      => $pass,
    "CharacterSet" => "UTF-8" // Đảm bảo hiển thị đúng ký tự
];

// Kết nối SQL Server
$conn = sqlsrv_connect($serverName, $connection);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

