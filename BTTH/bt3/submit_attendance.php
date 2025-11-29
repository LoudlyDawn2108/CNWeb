<?php
session_start();
require_once 'attendence.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Get submitted data
$attendanceUsernames = $_POST['attendance'] ?? [];
$attendanceDate = $_POST['attendance_date'] ?? date('Y-m-d');

// Validate date
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $attendanceDate)) {
    die('Invalid date format');
}

// Get all records
$attendenceManager = new AttendenceManager();
$allRecords = $attendenceManager->getRecords();

// Process attendance
$presentStudents = [];
$absentStudents = [];

foreach ($allRecords as $record) {
    if (in_array($record->username, $attendanceUsernames)) {
        $presentStudents[] = $record;
    } else {
        $absentStudents[] = $record;
    }
}

// Store in session for display
$_SESSION['attendance_submission'] = [
    'date' => $attendanceDate,
    'present' => $presentStudents,
    'absent' => $absentStudents,
    'timestamp' => time()
];

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả điểm danh</title>
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1rem;
            opacity: 0.9;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            border-left: 5px solid #28a745;
        }

        .success-message h2 {
            margin-bottom: 10px;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
        }

        .stat-number.present {
            color: #28a745;
        }

        .stat-number.absent {
            color: #dc3545;
        }

        .stat-number.total {
            color: #667eea;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .content {
            padding: 20px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }

        .section-title.present {
            color: #28a745;
        }

        .section-title.absent {
            color: #dc3545;
        }

        .student-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .student-card {
            padding: 15px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            background: #f8f9fa;
        }

        .student-card.present {
            border-color: #28a745;
            background: #d4edda;
        }

        .student-card.absent {
            border-color: #dc3545;
            background: #f8d7da;
        }

        .student-name {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .student-info {
            font-size: 0.9rem;
            color: #6c757d;
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

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        @media (max-width: 768px) {
            .stats {
                flex-direction: column;
                gap: 15px;
            }

            .student-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Điểm danh thành công!</h1>
            <p>Ngày: <?php echo date('d/m/Y', strtotime($attendanceDate)); ?></p>
        </div>

        <div class="success-message">
            <h2>🎉 Đã lưu điểm danh thành công!</h2>
            <p>Dữ liệu điểm danh đã được ghi nhận. Trong môi trường thực tế, dữ liệu này sẽ được lưu vào CSDL.</p>
        </div>

        <div class="stats">
            <div class="stat-item">
                <div class="stat-number total"><?php echo count($allRecords); ?></div>
                <div class="stat-label">Tổng sinh viên</div>
            </div>
            <div class="stat-item">
                <div class="stat-number present"><?php echo count($presentStudents); ?></div>
                <div class="stat-label">Có mặt</div>
            </div>
            <div class="stat-item">
                <div class="stat-number absent"><?php echo count($absentStudents); ?></div>
                <div class="stat-label">Vắng mặt</div>
            </div>
            <div class="stat-item">
                <div class="stat-number present">
                    <?php echo count($allRecords) > 0 ? round((count($presentStudents) / count($allRecords)) * 100, 1) : 0; ?>%
                </div>
                <div class="stat-label">Tỷ lệ có mặt</div>
            </div>
        </div>

        <div class="content">
            <?php if (count($presentStudents) > 0): ?>
                <div class="section">
                    <h2 class="section-title present">✅ Sinh viên có mặt (<?php echo count($presentStudents); ?>)</h2>
                    <div class="student-grid">
                        <?php foreach ($presentStudents as $student): ?>
                            <div class="student-card present">
                                <div class="student-name">
                                    <?php echo htmlspecialchars($student->lastname . ' ' . $student->firstname); ?>
                                </div>
                                <div class="student-info">
                                    <strong>MSSV:</strong> <?php echo htmlspecialchars($student->username); ?><br>
                                    <strong>Lớp:</strong> <?php echo htmlspecialchars($student->city); ?><br>
                                    <strong>Email:</strong> <?php echo htmlspecialchars($student->email); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (count($absentStudents) > 0): ?>
                <div class="section">
                    <h2 class="section-title absent">❌ Sinh viên vắng mặt (<?php echo count($absentStudents); ?>)</h2>
                    <div class="student-grid">
                        <?php foreach ($absentStudents as $student): ?>
                            <div class="student-card absent">
                                <div class="student-name">
                                    <?php echo htmlspecialchars($student->lastname . ' ' . $student->firstname); ?>
                                </div>
                                <div class="student-info">
                                    <strong>MSSV:</strong> <?php echo htmlspecialchars($student->username); ?><br>
                                    <strong>Lớp:</strong> <?php echo htmlspecialchars($student->city); ?><br>
                                    <strong>Email:</strong> <?php echo htmlspecialchars($student->email); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="actions">
            <a href="index.php" class="btn btn-primary">← Quay lại trang chủ</a>
            <a href="view_history.php" class="btn btn-success">📊 Xem lịch sử điểm danh</a>
        </div>
    </div>
</body>
</html>

