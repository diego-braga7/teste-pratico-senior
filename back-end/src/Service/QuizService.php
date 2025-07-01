<?php

namespace Src\Service;

use Src\Entity\Quiz;
use Src\Entity\User;
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
}
