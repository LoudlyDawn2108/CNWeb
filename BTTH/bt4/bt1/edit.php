<?php
session_start();
include 'flowers.php';

// Khởi tạo repository
$repo = new FlowerRepository();
$useDatabase = $repo->isUsingDatabase();

$success = false;
$error = '';
$flower = null;

// Lấy ID từ URL
if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$id = intval($_GET['id']);

// Tìm hoa cần sửa
$flower = getFlowerById($id);

if (!$flower) {
    header('Location: admin.php');
    exit;
}

// Xử lý cập nhật hoa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($name) || empty($description)) {
        $error = 'Vui lòng điền đầy đủ thông tin!';
    } else {
        $imagePath = $flower['image']; // Giữ ảnh cũ mặc định

        // Xử lý upload ảnh mới nếu có
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = '../images/';
            
            // Tạo thư mục nếu chưa tồn tại
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = uniqid() . '.' . $fileExtension;
                $uploadPath = $uploadDir . $newFileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $imagePath = $uploadPath;
                } else {
                    $error = 'Lỗi khi upload ảnh!';
                }
            } else {
                $error = 'Chỉ chấp nhận file ảnh JPG, JPEG, PNG, GIF!';
            }
        }

        if (empty($error)) {
            if ($useDatabase) {
                // Cập nhật vào database
                $result = updateFlower($id, $name, $description, $imagePath);
                if ($result) {
                    $success = true;
                    $flower = getFlowerById($id); // Refresh dữ liệu
                } else {
                    $error = 'Lỗi khi cập nhật database!';
                }
            } else {
                // Fallback: Cập nhật vào session
                if (!isset($_SESSION['flowers'])) {
                    $_SESSION['flowers'] = getAllFlowers();
                }
                
                foreach ($_SESSION['flowers'] as $key => $f) {
                    if ($f['id'] == $id) {
                        $_SESSION['flowers'][$key]['name'] = $name;
                        $_SESSION['flowers'][$key]['description'] = $description;
                        $_SESSION['flowers'][$key]['image'] = $imagePath;
                        $flower = $_SESSION['flowers'][$key];
                        break;
                    }
                }
                $success = true;
            }

            if ($success) {
                // Chuyển hướng về trang admin sau 2 giây
                header('Refresh: 2; URL=admin.php');
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Thông Tin Hoa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 700px;
            width: 100%;
        }

        h1 {
            color: #667eea;
            margin-bottom: 30px;
            text-align: center;
            font-size: 2.5em;
        }

        .current-image {
            text-align: center;
            margin-bottom: 30px;
        }

        .current-image img {
            max-width: 200px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .current-image p {
            margin-top: 10px;
            color: #666;
            font-size: 0.9em;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
            font-size: 1.1em;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            font-family: inherit;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        textarea {
            resize: vertical;
            min-height: 150px;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .btn-warning {
            background: #ffc107;
            color: #000;
        }

        .btn-warning:hover {
            background: #e0a800;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }

        .file-info {
            margin-top: 8px;
            font-size: 0.9em;
            color: #666;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 2em;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Sửa Thông Tin Hoa</h1>

        <?php if ($success): ?>
            <div class="alert alert-success">
                ✅ Cập nhật thông tin thành công! Đang chuyển về trang quản trị...
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">
                ❌ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="current-image">
            <img src="<?php echo htmlspecialchars($flower['image']); ?>"
                 alt="<?php echo htmlspecialchars($flower['name']); ?>">
            <p>Ảnh hiện tại</p>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Tên Hoa *</label>
                <input type="text" id="name" name="name" required
                       placeholder="Nhập tên loài hoa..."
                       value="<?php echo htmlspecialchars($flower['name']); ?>">
            </div>

            <div class="form-group">
                <label for="description">Mô Tả *</label>
                <textarea id="description" name="description" required
                          placeholder="Nhập mô tả về loài hoa..."><?php echo htmlspecialchars($flower['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="image">Thay Đổi Ảnh (tùy chọn)</label>
                <input type="file" id="image" name="image" accept="image/*">
                <div class="file-info">📁 Chấp nhận: JPG, JPEG, PNG, GIF. Để trống nếu không muốn thay đổi ảnh.</div>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-warning">💾 Cập Nhật</button>
                <a href="admin.php" class="btn btn-secondary">❌ Hủy</a>
            </div>
        </form>
    </div>
</body>
</html>

