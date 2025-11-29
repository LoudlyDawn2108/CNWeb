<?php
session_start();

$error = '';
$success = false;

// Xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Thông tin đăng nhập mẫu (trong thực tế nên lưu trong database với mật khẩu được hash)
    $adminUsername = 'admin';
    $adminPassword = 'admin123';

    if ($username === $adminUsername && $password === $adminPassword) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $success = true;
        header('Refresh: 1; URL=admin.php');
    } else {
        $error = 'Tên đăng nhập hoặc mật khẩu không đúng!';
    }
}

// Xử lý đăng xuất
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Quản Trị Hoa</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.3);
            padding: 50px;
            max-width: 450px;
            width: 100%;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h1 {
            color: #667eea;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 1.1em;
        }

        .flower-icon {
            font-size: 4em;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
            font-size: 1em;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.2em;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
        }

        .btn-login:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }

        .login-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-top: 30px;
            border-left: 4px solid #667eea;
        }

        .login-info h3 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .login-info p {
            color: #666;
            margin: 5px 0;
            font-size: 0.95em;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        .back-link a:hover {
            color: #5568d3;
        }

        @media (max-width: 768px) {
            .login-container {
                padding: 30px;
            }

            .login-header h1 {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="flower-icon">🌺</div>
            <h1>Đăng Nhập</h1>
            <p>Hệ thống quản trị hoa</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                ✅ Đăng nhập thành công! Đang chuyển hướng...
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">
                ❌ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">👤 Tên đăng nhập</label>
                <input type="text" id="username" name="username" required
                       placeholder="Nhập tên đăng nhập..."
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">🔒 Mật khẩu</label>
                <input type="password" id="password" name="password" required
                       placeholder="Nhập mật khẩu...">
            </div>

            <button type="submit" class="btn-login">🔐 Đăng Nhập</button>
        </form>

        <div class="login-info">
            <h3>📋 Thông tin đăng nhập thử nghiệm:</h3>
            <p><strong>Tên đăng nhập:</strong> admin</p>
            <p><strong>Mật khẩu:</strong> admin123</p>
        </div>

        <div class="back-link">
            <a href="index.php">⬅️ Quay lại trang chủ</a>
        </div>
    </div>
</body>
</html>
