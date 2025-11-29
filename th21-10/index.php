<?php
// Bước 1: Gọi file data.php chứa mảng dữ liệu (giả lập CSDL)
require 'data.php';

// Bước 2: Nhận thông báo thành công tạo mới qua phương thức GET (nếu có)
$success = $_GET['success'] ?? "";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Quản lý Đồ án</title>
    <!-- Chèn CSS nếu cần, ví dụ Bootstrap hay style.css -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
    <div>Quản lý Đồ án Tốt nghiệp</div>
    <div>
        <a href="index.php">Dashboard</a>
        <a href="create.php" class="btn btn-primary">+ Thêm đồ án</a>
    </div>
</div>

<div class="container">
    <h1>Dashboard</h1>
    <p>Dữ liệu trong ví dụ này đang được lưu cố định trong một mảng PHP (chưa dùng CSDL).</p>

    <!-- Bước 3: Hiển thị thông báo nếu có tham số ?success=created -->
    <?php if ($success == "created"): ?>
        <div style="padding: 10px; background:#d1e7dd; color:#0f5132; border-radius:4px; margin-bottom:16px;">
            Giả lập: Thêm đồ án mới thành công! (thông báo thông qua tham số GET trong URL).
        </div>
    <?php endif; ?>

    <!-- Bước 4: Hiển thị bảng dữ liệu -->
    <table class="table" style="width:100%; border-collapse:collapse;">
        <thead>
        <tr>
            <th>#</th>
            <th>Tên đề tài</th>
            <th>Sinh viên</th>
            <th>GV hướng dẫn</th>
            <th>Năm học</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($do_an_list)): ?>
            <?php foreach ($do_an_list as $do_an): ?>
                <tr>
                    <td><?php echo htmlspecialchars($do_an['id']); ?></td>
                    <td><?php echo htmlspecialchars($do_an['ten_de_tai']); ?></td>
                    <td>
                        <?php echo htmlspecialchars($do_an['ten_sinh_vien']); ?><br>
                        <small>MSSV: <?php echo htmlspecialchars($do_an['mssv']); ?></small>
                    </td>
                    <td><?php echo htmlspecialchars($do_an['giang_vien_hd']); ?></td>
                    <td><?php echo htmlspecialchars($do_an['nam_hoc']); ?></td>
                    <td><?php echo htmlspecialchars($do_an['trang_thai']); ?></td>
                    <td><?php echo htmlspecialchars($do_an['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Trường hợp mảng rỗng -->
            <tr>
                <td colspan="7">Chưa có đồ án nào trong mảng.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
