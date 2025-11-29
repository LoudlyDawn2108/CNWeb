<?php
/**
 * Quiz Result History Page
 * Displays saved quiz results from database
 */

require_once 'questions.php';

$questionManager = new QuestionManager();
$results = $questionManager->getAllResults();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử bài thi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .history-container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        h1 {
            color: #667eea;
            text-align: center;
            margin-bottom: 30px;
        }

        .result-table {
            width: 100%;
        }

        .result-table th {
            background-color: #667eea;
            color: white;
        }

        .score-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: bold;
        }

        .score-excellent {
            background-color: #28a745;
            color: white;
        }

        .score-good {
            background-color: #17a2b8;
            color: white;
        }

        .score-average {
            background-color: #ffc107;
            color: black;
        }

        .score-poor {
            background-color: #dc3545;
            color: white;
        }

        .empty-message {
            text-align: center;
            padding: 50px;
            color: #6c757d;
        }

        .empty-message i {
            font-size: 4em;
            margin-bottom: 20px;
        }

        .btn-back {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
            color: white;
        }
    </style>
</head>
<body>
<div class="history-container">
    <h1>📊 Lịch Sử Bài Thi</h1>

    <?php if (empty($results)): ?>
        <div class="empty-message">
            <div style="font-size: 4em;">📭</div>
            <h3>Chưa có kết quả nào</h3>
            <p>Hãy làm bài thi để có kết quả hiển thị ở đây.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover result-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên học viên</th>
                        <th>Số câu đúng</th>
                        <th>Tổng số câu</th>
                        <th>Điểm</th>
                        <th>Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $index => $result): ?>
                        <?php
                        $percentage = $result->totalQuestions > 0 
                            ? ($result->score / $result->totalQuestions) * 100 
                            : 0;
                        
                        if ($percentage >= 90) {
                            $badgeClass = 'score-excellent';
                        } elseif ($percentage >= 70) {
                            $badgeClass = 'score-good';
                        } elseif ($percentage >= 50) {
                            $badgeClass = 'score-average';
                        } else {
                            $badgeClass = 'score-poor';
                        }
                        
                        $scoreOutOf10 = $result->totalQuestions > 0 
                            ? number_format(($result->score / $result->totalQuestions) * 10, 2) 
                            : '0.00';
                        ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($result->studentName); ?></td>
                            <td><?php echo $result->score; ?></td>
                            <td><?php echo $result->totalQuestions; ?></td>
                            <td>
                                <span class="score-badge <?php echo $badgeClass; ?>">
                                    <?php echo $scoreOutOf10; ?>/10
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i:s', strtotime($result->completedAt)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3 text-muted text-center">
            <small>Tổng số kết quả: <?php echo count($results); ?></small>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-back btn-lg">
            ← Quay lại làm bài
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
