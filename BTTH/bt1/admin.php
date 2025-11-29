<?php
session_start();

include 'flowers.php';

// Xử lý các thao tác CRUD thông qua session để giữ dữ liệu
if (!isset($_SESSION['flowers'])) {
    $_SESSION['flowers'] = getAllFlowers();
}

$flowers = $_SESSION['flowers'];

// Xử lý xóa hoa
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    foreach ($_SESSION['flowers'] as $key => $flower) {
        if ($flower['id'] == $id) {
            unset($_SESSION['flowers'][$key]);
            $_SESSION['flowers'] = array_values($_SESSION['flowers']);
            break;
        }
    }
    header('Location: admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị - Danh Sách Hoa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            padding: 30px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }

        header h1 {
            color: #667eea;
            font-size: 2.5em;
        }

        .header-actions {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-warning {
            background: #ffc107;
            color: #000;
        }

        .btn-warning:hover {
            background: #e0a800;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-sm {
            padding: 8px 15px;
            font-size: 0.9em;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #667eea;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9em;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .flower-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .description-cell {
            max-width: 400px;
            line-height: 1.5;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #dc3545 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .stat-card h3 {
            font-size: 2em;
            margin-bottom: 5px;
        }

        .stat-card p {
            opacity: 0.9;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 20px;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🌺 Quản Trị Hoa</h1>
            <div class="header-actions">
                <a href="add.php" class="btn btn-success">➕ Thêm Hoa Mới</a>
                <a href="index.php" class="btn btn-secondary">👁️ Xem Trang Khách</a>
            </div>
        </header>

        <div class="stats">
            <div class="stat-card">
                <h3><?php echo count($flowers); ?></h3>
                <p>Tổng Số Loài Hoa</p>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tên Hoa</th>
                        <th>Mô Tả</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($flowers)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px;">
                                <p style="color: #999; font-size: 1.2em;">Chưa có loài hoa nào. Hãy thêm hoa mới!</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($flowers as $flower): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($flower['id']); ?></td>
                                <td>
                                    <img src="<?php echo htmlspecialchars($flower['image']); ?>"
                                         alt="<?php echo htmlspecialchars($flower['name']); ?>"
                                         class="flower-img">
                                </td>
                                <td><strong><?php echo htmlspecialchars($flower['name']); ?></strong></td>
                                <td class="description-cell"><?php echo htmlspecialchars($flower['description']); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="edit.php?id=<?php echo $flower['id']; ?>"
                                           class="btn btn-warning btn-sm">✏️ Sửa</a>
                                        <a href="admin.php?action=delete&id=<?php echo $flower['id']; ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa hoa này?')">🗑️ Xóa</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

