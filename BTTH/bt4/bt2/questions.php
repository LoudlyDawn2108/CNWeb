<?php
/**
 * Bài tập 2 - Hệ thống Trắc nghiệm với Database PDO
 * Model Question và QuestionRepository
 */

require_once __DIR__ . '/../config/database.php';

class Question
{
    public int $id;
    public string $question;
    public array $options = [];
    public array $answers = [];
    public bool $isMultipleChoice = false;

    public function __construct($question = '', $options = [], $answers = [], $isMultipleChoice = false)
    {
        $this->id = 0;
        $this->question = $question;
        $this->options = $options;
        $this->answers = is_array($answers) ? $answers : [$answers];
        $this->isMultipleChoice = $isMultipleChoice;
    }
}

class QuizResult
{
    public int $id;
    public string $studentName;
    public int $totalQuestions;
    public int $correctAnswers;
    public float $score;
    public string $submittedAt;

    public static function fromArray(array $data): QuizResult
    {
        $result = new QuizResult();
        $result->id = $data['id'] ?? 0;
        $result->studentName = $data['student_name'] ?? '';
        $result->totalQuestions = $data['total_questions'] ?? 0;
        $result->correctAnswers = $data['correct_answers'] ?? 0;
        $result->score = $data['score'] ?? 0;
        $result->submittedAt = $data['submitted_at'] ?? '';
        return $result;
    }
}

class QuestionRepository
{
    private $conn;
    private $useDatabase;

    public function __construct()
    {
        try {
            $this->conn = Database::getConnection();
            $this->useDatabase = ($this->conn !== null);
        } catch (Exception $e) {
            $this->useDatabase = false;
            $this->conn = null;
        }
    }

    public function isUsingDatabase(): bool
    {
        return $this->useDatabase;
    }

    /**
     * Lấy tất cả câu hỏi từ database
     */
    public function getAll(): array
    {
        if (!$this->useDatabase) {
            return [];
        }

        try {
            $sql = "SELECT * FROM questions ORDER BY id ASC";
            $stmt = $this->conn->query($sql);
            $results = $stmt->fetchAll();

            $questions = [];
            foreach ($results as $row) {
                $question = new Question();
                $question->id = $row['id'];
                $question->question = $row['question'];
                $question->isMultipleChoice = (bool)$row['is_multiple_choice'];

                // Lấy các options
                $optSql = "SELECT * FROM question_options WHERE question_id = ? ORDER BY option_letter ASC";
                $optStmt = $this->conn->prepare($optSql);
                $optStmt->execute([$row['id']]);
                $options = $optStmt->fetchAll();

                foreach ($options as $opt) {
                    $question->options[] = $opt['option_letter'] . '. ' . $opt['option_text'];
                    if ($opt['is_correct']) {
                        $question->answers[] = $opt['option_letter'];
                    }
                }

                $questions[] = $question;
            }

            return $questions;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Đếm số câu hỏi
     */
    public function count(): int
    {
        if (!$this->useDatabase) {
            return 0;
        }

        try {
            $stmt = $this->conn->query("SELECT COUNT(*) FROM questions");
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Lưu kết quả bài thi
     */
    public function saveResult(string $studentName, int $totalQuestions, int $correctAnswers, float $score): ?int
    {
        if (!$this->useDatabase) {
            return null;
        }

        try {
            $sql = "INSERT INTO quiz_results (student_name, total_questions, correct_answers, score) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$studentName, $totalQuestions, $correctAnswers, $score]);
            return (int)$this->conn->lastInsertId();
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Lấy tất cả kết quả bài thi
     */
    public function getAllResults(): array
    {
        if (!$this->useDatabase) {
            return [];
        }

        try {
            $sql = "SELECT * FROM quiz_results ORDER BY submitted_at DESC";
            $stmt = $this->conn->query($sql);
            $results = $stmt->fetchAll();

            $quizResults = [];
            foreach ($results as $row) {
                $quizResults[] = QuizResult::fromArray($row);
            }

            return $quizResults;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Lấy kết quả theo ID
     */
    public function getResultById(int $id): ?QuizResult
    {
        if (!$this->useDatabase) {
            return null;
        }

        try {
            $sql = "SELECT * FROM quiz_results WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $row = $stmt->fetch();

            if ($row) {
                return QuizResult::fromArray($row);
            }
            return null;
        } catch (PDOException $e) {
            return null;
        }
    }
}

class QuestionManager
{
    private QuestionRepository $repo;

    public function __construct()
    {
        $this->repo = new QuestionRepository();
    }

    public function isUsingDatabase(): bool
    {
        return $this->repo->isUsingDatabase();
    }

    public function getQuestions(): array
    {
        // Thử lấy từ database trước
        if ($this->repo->isUsingDatabase()) {
            $questions = $this->repo->getAll();
            if (!empty($questions)) {
                return $questions;
            }
        }

        // Fallback: Lấy từ session hoặc file
        if (!empty($_SESSION['questions'])) {
            return $_SESSION['questions'];
        }

        $_SESSION['questions'] = $this->loadQuestionFromFile();
        return $_SESSION['questions'];
    }

    private function loadQuestionFromFile(): array
    {
        $filePath = "../Quiz.txt";
        if (!file_exists($filePath)) {
            return [];
        }

        $quiz = fopen($filePath, "r");
        if (!$quiz) {
            return [];
        }

        $quiz_data = fread($quiz, filesize($filePath));
        fclose($quiz);
        $lines = explode("\n", $quiz_data);

        $questions = [];
        $i = 0;

        while ($i < count($lines)) {
            $line = trim($lines[$i]);

            // Skip empty lines
            if (empty($line)) {
                $i++;
                continue;
            }

            $question = $line;
            $options = [];
            $i++;

            while ($i < count($lines)) {
                $line = trim($lines[$i]);

                if (empty($line)) {
                    $i++;
                    continue;
                }

                if (preg_match('/^[A-Z]\./', $line)) {
                    $options[] = $line;
                    $i++;
                } else if (str_starts_with($line, 'ANSWER:')) {
                    $answerPart = trim(substr($line, 7));
                    $isMultipleChoice = str_contains($answerPart, ',');

                    if ($isMultipleChoice) {
                        $answers = array_map('trim', explode(',', $answerPart));
                    } else {
                        $answers = [$answerPart];
                    }

                    $questions[] = new Question($question, $options, $answers, $isMultipleChoice);
                    $i++;
                    break;
                } else {
                    $i++;
                    break;
                }
            }
        }

        return $questions;
    }

    /**
     * Lưu kết quả bài thi vào database
     */
    public function saveQuizResult(string $studentName, int $totalQuestions, int $correctAnswers): ?int
    {
        $score = ($totalQuestions > 0) ? ($correctAnswers / $totalQuestions) * 100 : 0;
        return $this->repo->saveResult($studentName, $totalQuestions, $correctAnswers, $score);
    }

    /**
     * Lấy tất cả kết quả bài thi
     */
    public function getAllResults(): array
    {
        return $this->repo->getAllResults();
    }

    /**
     * Lấy kết quả theo ID
     */
    public function getResultById(int $id): ?QuizResult
    {
        return $this->repo->getResultById($id);
    }
}

