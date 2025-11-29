<?php
require_once 'questions.php';
session_start();


$questionManager = new QuestionManager();
$questions = $questionManager->getQuestions();
$_SESSION['questions'] = $questions;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài Thi Trắc Nghiệm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px 0;
        }

        .quiz-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .question-block {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }

        .question-text {
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 1.1em;
            color: #333;
        }

        .option {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.2s;
        }

        .option:hover {
            background-color: #e9ecef;
        }

        .option input[type="radio"],
        .option input[type="checkbox"] {
            margin-right: 10px;
        }

        .badge {
            font-size: 0.8em;
            margin-left: 10px;
        }

        .submit-btn {
            margin-top: 20px;
            padding: 12px 40px;
            font-size: 1.1em;
        }

        h1 {
            color: #007bff;
            margin-bottom: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="quiz-container">
    <h1>Bài Thi Trắc Nghiệm Android</h1>

    <form method="POST" action="result.php">
        <?php foreach ($questions as $index => $question): ?>
            <div class="question-block">
                <div class="question-text">
                    Câu <?php echo $index + 1; ?>: <?php echo htmlspecialchars($question->question); ?>
                    <?php if ($question->isMultipleChoice): ?>
                        <span class="badge bg-info">Chọn nhiều đáp án</span>
                    <?php endif; ?>
                </div>
                <?php foreach ($question->options as $optIndex => $option): ?>
                    <?php
                    $optionLetter = substr($option, 0, 1);
                    ?>
                    <div class="option">
                        <label>
                            <?php if ($question->isMultipleChoice): ?>
                                <input type="checkbox"
                                       name="question_<?php echo $index; ?>[]"
                                       value="<?php echo $optionLetter; ?>">
                            <?php else: ?>
                                <input type="radio"
                                       name="question_<?php echo $index; ?>"
                                       value="<?php echo $optionLetter; ?>"
                                       required>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($option); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <div class="text-center">
            <button type="submit" class="btn btn-success btn-lg submit-btn">
                Nộp bài
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
