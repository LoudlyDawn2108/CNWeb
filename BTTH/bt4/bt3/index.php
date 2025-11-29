<?php
require_once 'attendence.php';
session_start();

$attendenceManager = new AttendenceManager();
$uploadResult = null;

// Xử lý upload file CSV
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $uploadResult = $attendenceManager->importFromUploadedFile($_FILES['csv_file']);
    if ($uploadResult['success']) {
        // Clear session để load lại dữ liệu mới
        unset($_SESSION['attendance_records']);
    }
}

$records = $attendenceManager->getRecords();
$useDatabase = $attendenceManager->isUsingDatabase();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách điểm danh 65HTTT</title>
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

        .header p {
            font-size: 1rem;
            opacity: 0.9;
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
            color: #667eea;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .table-container {
            overflow-x: auto;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        tbody tr:nth-child(even):hover {
            background: #e9ecef;
        }

        td {
            padding: 12px 15px;
            font-size: 0.9rem;
        }

        .no-data {
            text-align: center;
            padding: 50px;
            color: #6c757d;
            font-style: italic;
        }

        .index-col {
            text-align: center;
            font-weight: bold;
            color: #667eea;
        }

        .checkbox-col {
            text-align: center;
        }

        .checkbox-col input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .form-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: #f8f9fa;
            border-top: 2px solid #e9ecef;
        }

        .select-controls {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .date-input {
            padding: 10px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
        }

        .date-input:focus {
            outline: none;
            border-color: #667eea;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.5rem;
            }

            .stats {
                flex-direction: column;
                gap: 15px;
            }

            table {
                font-size: 0.8rem;
            }

            th, td {
                padding: 8px;
            }

            .form-controls {
                flex-direction: column;
                gap: 15px;
            }

            .select-controls {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Danh sách điểm danh</h1>
            <p>Lớp 65HTTT - Môn CSE485 - Công nghệ Web</p>
        </div>

        <div class="stats">
            <div class="stat-item">
                <div class="stat-number"><?php echo count($records); ?></div>
                <div class="stat-label">Tổng sinh viên</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo count(array_unique(array_map(function($r) { return $r->city; }, $records))); ?></div>
                <div class="stat-label">Số lớp</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo date('d/m/Y'); ?></div>
                <div class="stat-label">Ngày xem</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" style="font-size: 1rem; color: <?php echo $useDatabase ? '#28a745' : '#ffc107'; ?>;">
                    <?php echo $useDatabase ? '✅ Database' : '📁 Session'; ?>
                </div>
                <div class="stat-label">Nguồn dữ liệu</div>
            </div>
        </div>

        <!-- Upload CSV Section -->
        <div style="padding: 20px; background: #e9ecef; border-bottom: 2px solid #dee2e6;">
            <h4 style="margin-bottom: 15px; color: #495057;">📁 Upload File CSV Điểm Danh</h4>
            
            <?php if ($uploadResult): ?>
                <div style="padding: 10px; border-radius: 5px; margin-bottom: 15px;
                            background: <?php echo $uploadResult['success'] ? '#d4edda' : '#f8d7da'; ?>;
                            color: <?php echo $uploadResult['success'] ? '#155724' : '#721c24'; ?>;">
                    <?php echo htmlspecialchars($uploadResult['message']); ?>
                    <?php if ($uploadResult['success']): ?>
                        (<?php echo $uploadResult['count']; ?> sinh viên)
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <input type="file" name="csv_file" accept=".csv" required 
                       style="flex: 1; min-width: 200px; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                <button type="submit" class="btn btn-primary">
                    📤 Import CSV
                </button>
                <a href="view_history.php" class="btn btn-secondary" style="text-decoration: none;">
                    📊 Xem lịch sử
                </a>
            </form>
            <small style="display: block; margin-top: 10px; color: #6c757d;">
                📌 File CSV phải có các cột: username, password, lastname, firstname, city, email, course1
            </small>
        </div>

        <form method="POST" action="submit_attendance.php">
            <div class="table-container">
                <?php if (count($records) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" title="Chọn tất cả">
                                </th>
                                <th>STT</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Họ</th>
                                <th>Tên</th>
                                <th>Lớp</th>
                                <th>Email</th>
                                <th>Môn học</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($records as $index => $record): ?>
                                <tr>
                                    <td class="checkbox-col">
                                        <input type="checkbox"
                                               name="attendance[]"
                                               value="<?php echo $record->id; ?>"
                                               class="attendance-checkbox"
                                               <?php echo (isset($record->isPresent) && $record->isPresent) ? 'checked' : ''; ?>>
                                    </td>
                                    <td class="index-col"><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($record->username); ?></td>
                                    <td><?php echo htmlspecialchars($record->password); ?></td>
                                    <td><?php echo htmlspecialchars($record->lastname); ?></td>
                                    <td><?php echo htmlspecialchars($record->firstname); ?></td>
                                    <td><?php echo htmlspecialchars($record->city); ?></td>
                                    <td><?php echo htmlspecialchars($record->email); ?></td>
                                    <td><?php echo htmlspecialchars($record->course1); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data">
                        <p>Không có dữ liệu điểm danh</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-controls">
                <div class="select-controls">
                    <button type="button" class="btn btn-secondary" id="selectAllBtn">Chọn tất cả</button>
                    <button type="button" class="btn btn-secondary" id="deselectAllBtn">Bỏ chọn tất cả</button>
                </div>
                <div class="select-controls">
                    <input type="date"
                           name="attendance_date"
                           class="date-input"
                           value="<?php echo date('Y-m-d'); ?>"
                           required>
                    <button type="submit" class="btn btn-primary">📝 Lưu điểm danh</button>
                </div>
            </div>
        </form>

        <script>
            // Select all functionality
            const selectAllCheckbox = document.getElementById('selectAll');
            const selectAllBtn = document.getElementById('selectAllBtn');
            const deselectAllBtn = document.getElementById('deselectAllBtn');
            const attendanceCheckboxes = document.querySelectorAll('.attendance-checkbox');

            selectAllCheckbox.addEventListener('change', function() {
                attendanceCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            selectAllBtn.addEventListener('click', function() {
                attendanceCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                selectAllCheckbox.checked = true;
            });

            deselectAllBtn.addEventListener('click', function() {
                attendanceCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                selectAllCheckbox.checked = false;
            });

            // Update select all checkbox based on individual checkboxes
            attendanceCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(attendanceCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                });
            });
        </script>
    </div>
</body>
</html>
