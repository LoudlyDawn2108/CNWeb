<?php
class Question
{
    public string $question;
    public array $options = [];
    public array $answers = [];
    public bool $isMultipleChoice = false;

    public function __construct($question, $options, $answers, $isMultipleChoice = false)
    {
        $this->question = $question;
        $this->options = $options;
        $this->answers = is_array($answers) ? $answers : [$answers];
        $this->isMultipleChoice = $isMultipleChoice;
    }
}

class QuestionManager
{
    public function getQuestions(): array
    {
        if (!empty($_SESSION['questions'])) {
            return $_SESSION['questions'];
        }

        $_SESSION['questions'] = $this->loadQuestionFromFile();
        return $_SESSION['questions'];
    }

    private function loadQuestionFromFile(): array
    {
        $quiz = fopen("../Quiz.txt", "r") or die("Unable to open file!");
        $quiz_data = fread($quiz, filesize("../Quiz.txt"));
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
                    $answerPart = trim(substr($line, 8));

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
                    // Unknown line, skip
                    $i++;
                    break;
                }
            }
        }
        return $questions;
    }
}

