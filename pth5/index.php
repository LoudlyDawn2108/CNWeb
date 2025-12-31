<?php
require_once 'models/SinhVienModel.php';
// Tệp Controller là "não" của ứng dụng

// === THIẾT LẬP KẾT NỐI PDO ===
$host = '127.0.0.1';
$dbname = 'cse485_web';
$username = 'root';
$password = '';
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
// === KẾT THÚC KẾT NỐI PDO ===
// === LOGIC CỦA CONTROLLER ===
if (isset($_POST['ten_sinh_vien'])) {

    $ten = $_POST['ten_sinh_vien'];
    $email = $_POST['email'];
    addSinhVien($pdo, $ten, $email);
    header('Location: index.php');
    exit;
}

$danh_sach_sv = getAllSinhVien($pdo);

include 'views/sinhvien_view.php';