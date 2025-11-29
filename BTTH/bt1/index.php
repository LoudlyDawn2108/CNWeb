<?php
// Trang hiển thị danh sách hoa cho người dùng khách
include 'flowers.php';
$flowers = getAllFlowers();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Các Loài Hoa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #dc3545 0%, #5568d3 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
            padding: 30px 0;
        }

        header h1 {
            font-size: 3em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        header p {
            font-size: 1.2em;
            opacity: 0.9;
        }

        .admin-link {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 25px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: transform 0.3s;
        }

        .admin-link:hover {
            transform: scale(1.05);
        }

        .flowers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .flower-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .flower-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        .flower-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .flower-content {
            padding: 25px;
        }

        .flower-name {
            font-size: 1.8em;
            color: #667eea;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .flower-description {
            color: #555;
            line-height: 1.6;
            text-align: justify;
        }

        footer {
            text-align: center;
            color: white;
            padding: 20px 0;
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            .flowers-grid {
                grid-template-columns: 1fr;
            }

            header h1 {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🌸 Vườn Hoa Xinh Đẹp 🌸</h1>
            <p>Khám phá vẻ đẹp của các loài hoa tuyệt vời</p>
            <a href="login.php" class="admin-link">🔐 Quản Trị Viên</a>
        </header>

        <div class="flowers-grid">
            <?php foreach ($flowers as $flower): ?>
                <article class="flower-card">
                    <img src="<?php echo htmlspecialchars($flower['image']); ?>"
                         alt="<?php echo htmlspecialchars($flower['name']); ?>"
                         class="flower-image">
                    <div class="flower-content">
                        <h2 class="flower-name"><?php echo htmlspecialchars($flower['name']); ?></h2>
                        <p class="flower-description"><?php echo htmlspecialchars($flower['description']); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <footer>
            <p>&copy; 2025 Vườn Hoa Xinh Đẹp. Tất cả các quyền được bảo lưu.</p>
        </footer>
    </div>
</body>
</html>

