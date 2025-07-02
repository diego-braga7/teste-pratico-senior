<?php

namespace Src\Service;

use InvalidArgumentException;
use Monolog\Logger;
use Src\Entity\Quiz;
use Src\Entity\User;
use Src\LoggerFactory;
use Src\Repository\RepositoryInterface;
use Src\Validator\QuizValidatorInterface;





class QuizService
{

    public function __construct(private QuizValidatorInterface $validator, private RepositoryInterface $quizRepository, private QuestionService $questionService) {}

    /**
     *
     * @param string $title
     * @param array<int, array{question: string, type: string, options: array<string>}> $questions
     * @return Quiz
     */
    public function createQuiz(User $user, string $title, array $questions): Quiz
    {
        $this->validator->validateTitle($title);
        $this->validator->validateQuestions($questions);

        $quiz = $this->populateQuiz($user, trim($title));

        /** @var Quiz $quiz */
        $quiz = $this->quizRepository->save($quiz);

        $this->questionService->save($quiz->getId(), $questions);

        return $quiz;
    }

    private function populateQuiz(User $user, string $title): Quiz
    {
        return new Quiz(
            $title,
            $user->getId()
        );
    }

    public function QuizExist(int $quizId): bool
    {
        return !($this->quizRepository->getById($quizId) == null);
    }

    public function getQuestionService(): QuestionService
    {
        return $this->questionService;
    }

    public function getAll()
    {
        $rows = $this->quizRepository->query(
            "SELECT * FROM quizzes AS q
            INNER JOIN questions AS qu ON q.id = qu.quiz_id
            LEFT JOIN alternatives AS a ON qu.id = a.question_id
            "
        );

        if (empty($rows)) {
            throw new InvalidArgumentException("Quiz não encontrado.", 404);
        }
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[(int)$row['quiz_id']][] = $row;
        }

        $result = array_map(function (array $quizRows) {
            return $this->formataRetorno($quizRows);
        }, $grouped);

        return $result;
    }

    public function getCompleteQuiz(int $quizId)
    {
        $rows = $this->quizRepository->query(
            "SELECT * FROM quizzes AS q
            INNER JOIN questions AS qu ON q.id = qu.quiz_id
            LEFT JOIN alternatives AS a ON qu.id = a.question_id
            WHERE q.id = {$quizId}"
        );

        if (empty($rows)) {
            throw new InvalidArgumentException("Quiz não encontrado.", 404);
        }
        return $this->formataRetorno($rows);
    }

    private function formataRetorno(array $rows)
    {
        LoggerFactory::getLogger()->warning('ok', $rows);
        $result = [
            'title'     => $rows[0]['title'],
            'questions' => [],
        ];
        $map = [];

        foreach ($rows as $row) {
            $qid = (int) $row['question_id'];

            if (! isset($map[$qid])) {
                $map[$qid] = count($result['questions']);
                $result['questions'][] = [
                    'question' => $row['question_text'],
                    'type'     => str_replace('_', ' ', $row['response_type']),
                    'options'  => [],
                ];
            }

            if (! empty($row['option_text'])) {
                $idx = $map[$qid];
                $result['questions'][$idx]['options'][] = $row['option_text'];
            }
        }

        return $result;
    }
}
