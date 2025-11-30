<?php
session_start();
$host = 'localhost';
$port = '1434';
$dbname = 'cse485_web';
$username = 'sa';
$password = 'MyStrong!Passw0rd';
$dsn = "sqlsrv:Server=$host,$port;Database=$dbname;TrustServerCertificate=yes;LoginTimeout=30";
try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
    ]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ten_sinh_vien'])) {
    $ten = $_POST['ten_sinh_vien'];
    $email = $_POST['email'];

    $sql = "INSERT INTO sinhvien (ten_sinh_vien, email) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ten, $email]);

    header('Location: chapter4.php');
    exit;
}

$sql_select = "SELECT * FROM sinhvien ORDER BY ngay_tao DESC";
$stmt_select = $pdo->query($sql_select);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>PHT Chương 4 - Website hướng dữ liệu</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h2>Thêm Sinh Viên Mới (Chủ đề 4.3)</h2>
<form action="chapter4.php" method="POST">
    <label>Tên sinh viên: <input type="text" name="ten_sinh_vien" required></label>
    <label>Email: <input type="email" name="email" required></label>
    <button type="submit">Thêm</button>
</form>
<h2>Danh Sách Sinh Viên (Chủ đề 4.2)</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Tên Sinh Viên</th>
        <th>Email</th>
        <th>Ngày Tạo</th>
    </tr>
    <?php
    while ($row = $stmt_select->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['ten_sinh_vien']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['ngay_tao']) . "</td>";
        echo "</tr>";
    }
    ?>
</table>
</body>
</html>