<?php
class User {
    public string $username; // Kiểu dữ liệu là string
    public string $password;
    public string $role;

    // Hàm khởi tạo (constructor)
    public function __construct(string $username, string $password, string $role) {
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }
}
