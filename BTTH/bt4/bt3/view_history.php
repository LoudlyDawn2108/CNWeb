<?php
session_start();
require_once 'attendence.php';

// Get attendance submission from session
$submission = $_SESSION['attendance_submission'] ?? null;

// Lấy lịch sử từ database nếu có
$attendenceManager = new AttendenceManager();
$useDatabase = $attendenceManager->isUsingDatabase();
$dbHistory = $attendenceManager->getHistory();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử điểm danh</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .content {
            padding: 20px;
            min-height: 300px;
        }

        .info-message {
            background: #d1ecf1;
            color: #0c5460;
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            border-left: 5px solid #17a2b8;
        }

        .history-item {
            background: #f8f9fa;
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            border-left: 5px solid #28a745;
        }

        .history-item h3 {
            color: #28a745;
            margin-bottom: 10px;
        }

        .history-stats {
            display: flex;
            gap: 20px;
            margin-top: 15px;
        }

        .stat {
            flex: 1;
            padding: 15px;
            background: white;
            border-radius: 8px;
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .actions {
            display: flex;
            gap: 15px;
            padding: 20px;
            justify-content: center;
            background: #f8f9fa;
            border-top: 2px solid #e9ecef;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        @media (max-width: 768px) {
            .history-stats {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Lịch sử điểm danh</h1>
            <p>Xem lại các buổi điểm danh đã thực hiện</p>
        </div>

        <div class="content">
            <?php if ($useDatabase && !empty($dbHistory)): ?>
                <!-- Hiển thị lịch sử từ Database -->
                <div class="history-item" style="border-left-color: #007bff;">
                    <h3>📊 Lịch sử điểm danh từ Database</h3>
                    <p style="color: #28a745; margin-bottom: 15px;">💾 Nguồn dữ liệu: SQL Server</p>
                    
                    <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th style="padding: 10px; border: 1px solid #dee2e6; text-align: left;">Ngày</th>
                                <th style="padding: 10px; border: 1px solid #dee2e6; text-align: left;">Username</th>
                                <th style="padding: 10px; border: 1px solid #dee2e6; text-align: left;">Họ Tên</th>
                                <th style="padding: 10px; border: 1px solid #dee2e6; text-align: center;">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dbHistory as $item): ?>
                                <tr>
                                    <td style="padding: 10px; border: 1px solid #dee2e6;">
                                        <?php echo date('d/m/Y', strtotime($item['attendance_date'])); ?>
                                    </td>
                                    <td style="padding: 10px; border: 1px solid #dee2e6;">
                                        <?php echo htmlspecialchars($item['username']); ?>
                                    </td>
                                    <td style="padding: 10px; border: 1px solid #dee2e6;">
                                        <?php echo htmlspecialchars($item['lastname'] . ' ' . $item['firstname']); ?>
                                    </td>
                                    <td style="padding: 10px; border: 1px solid #dee2e6; text-align: center;">
                                        <?php if ($item['is_present']): ?>
                                            <span style="color: #28a745; font-weight: bold;">✅ Có mặt</span>
                                        <?php else: ?>
                                            <span style="color: #dc3545; font-weight: bold;">❌ Vắng</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif ($submission): ?>
                <div class="history-item">
                    <h3>Điểm danh gần nhất</h3>
                    <p><strong>Ngày:</strong> <?php echo date('d/m/Y', strtotime($submission['date'])); ?></p>
                    <p><strong>Thời gian ghi nhận:</strong> <?php echo date('d/m/Y H:i:s', $submission['timestamp']); ?></p>

                    <div class="history-stats">
                        <div class="stat">
                            <div class="stat-value" style="color: #28a745;">
                                <?php echo count($submission['present']); ?>
                            </div>
                            <div class="stat-label">Có mặt</div>
                        </div>
                        <div class="stat">
                            <div class="stat-value" style="color: #dc3545;">
                                <?php echo count($submission['absent']); ?>
                            </div>
                            <div class="stat-label">Vắng mặt</div>
                        </div>
                        <div class="stat">
                            <div class="stat-value" style="color: #667eea;">
                                <?php
                                $total = count($submission['present']) + count($submission['absent']);
                                echo $total > 0 ? round((count($submission['present']) / $total) * 100, 1) . '%' : '0%';
                                ?>
                            </div>
                            <div class="stat-label">Tỷ lệ có mặt</div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="info-message">
                    <h2>ℹ️ Chưa có lịch sử điểm danh</h2>
                    <p>Hiện tại chưa có dữ liệu điểm danh nào được lưu. Vui lòng thực hiện điểm danh để xem lịch sử.</p>
                    <p style="margin-top: 10px;"><strong>Lưu ý:</strong> Trong môi trường thực tế, dữ liệu sẽ được lưu vào CSDL và có thể xem lại bất cứ lúc nào.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="actions">
            <a href="index.php" class="btn btn-primary">← Quay lại trang chủ</a>
        </div>
    </div>
</body>
</html>

