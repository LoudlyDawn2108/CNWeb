<?php
require_once 'questions.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$questionManager = new QuestionManager();
$questions = $questionManager->getQuestions();
$submitted = true;
$score = 0;
foreach ($questions as $index => $question) {
    if ($question->isMultipleChoice) {

        $userAnswers = $_POST["question_$index"] ?? [];
        if (!is_array($userAnswers)) {
            $userAnswers = [$userAnswers];
        }

        sort($userAnswers);
        $correctAnswers = $question->answers;
        sort($correctAnswers);

        if ($userAnswers === $correctAnswers) {
            $score++;
        }
    } else {
        $userAnswer = $_POST["question_$index"] ?? '';
        if ($userAnswer === $question->answers[0]) {
            $score++;
        }
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kết Quả Bài Thi</title>
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

        .result-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
        }

        .result-score {
            font-size: 3em;
            font-weight: bold;
            margin: 20px 0;
        }

        .correct-answer {
            color: #28a745;
            font-weight: bold;
        }

        .wrong-answer {
            color: #dc3545;
            font-weight: bold;
        }

        .answer-review {
            background-color: #fff3cd;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
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
    <div class="result-card">
        <h2>🎉 Kết Quả Bài Thi</h2>
        <div class="result-score">
            <?php echo $score; ?> / <?php echo count($questions); ?>
        </div>
        <p style="font-size: 1.2em;">
            Điểm số: <?php echo number_format(($score / count($questions)) * 10, 2); ?>/10
        </p>
    </div>

    <h3 class="mb-4">📋 Xem lại đáp án</h3>
<?php foreach ($questions as $index => $question): ?>
    <?php
    if ($question->isMultipleChoice) {
        $userAnswers = $_POST["question_$index"] ?? [];
        if (!is_array($userAnswers)) {
            $userAnswers = [$userAnswers];
        }
        sort($userAnswers);
        $correctAnswers = $question->answers;
        sort($correctAnswers);
        $isCorrect = $userAnswers === $correctAnswers;
    } else {
        $userAnswer = $_POST["question_$index"] ?? '';
        $isCorrect = $userAnswer === $question->answers[0];
    }
    ?>
    <div class="question-block">
        <div class="question-text">
            Câu <?php echo $index + 1; ?>: <?php echo htmlspecialchars($question->question); ?>
            <?php if ($question->isMultipleChoice): ?>
                <span class="badge bg-info">Chọn nhiều đáp án</span>
            <?php endif; ?>
        </div>
        <?php foreach ($question->options as $optIndex => $option): ?>
            <?php
            $optionLetter = substr($option, 0, 1); // Get A, B, C, D, E from "A. TextView"
            if ($question->isMultipleChoice) {
                $isUserAnswer = in_array($optionLetter, $userAnswers ?? []);
            } else {
                $isUserAnswer = ($userAnswer ?? '') === $optionLetter;
            }
            $isCorrectAnswer = in_array($optionLetter, $question->answers);
            ?>
            <div class="option">
                <?php if ($isCorrectAnswer): ?>
                    <span class="correct-answer">✓ <?php echo htmlspecialchars($option); ?></span>
                <?php elseif ($isUserAnswer && !$isCorrect): ?>
                    <span class="wrong-answer">✗ <?php echo htmlspecialchars($option); ?></span>
                <?php else: ?>
                    <?php echo htmlspecialchars($option); ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <?php if (!$isCorrect): ?>
            <div class="answer-review">
                <strong>Đáp án đúng:</strong> <?php echo implode(', ', $question->answers); ?>
                <?php if ($question->isMultipleChoice): ?>
                    <?php if (!empty($userAnswers)): ?>
                        | <strong>Bạn chọn:</strong> <?php echo implode(', ', $userAnswers); ?>
                    <?php else: ?>
                        | <strong>Bạn chưa chọn đáp án</strong>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if (!empty($userAnswer)): ?>
                        | <strong>Bạn chọn:</strong> <?php echo $userAnswer; ?>
                    <?php else: ?>
                        | <strong>Bạn chưa chọn đáp án</strong>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

    <div class="text-center">
        <a href="index.php" class="btn btn-primary btn-lg">🔄 Làm lại bài thi</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
