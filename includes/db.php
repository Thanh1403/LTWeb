<?php
$host = 'localhost';        // hoặc 127.0.0.1
$dbname = 'futuregear';     // tên database bạn đã import
$username = 'root';         // mặc định của XAMPP
$password = '';             // mật khẩu trống nếu chưa thay đổi

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Thiết lập chế độ lỗi để hiện lỗi chi tiết khi có lỗi xảy ra
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Kết nối thành công!";
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
?>