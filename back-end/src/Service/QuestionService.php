<?php

namespace Src\Service;

use InvalidArgumentException;
use Src\Entity\Question;
use Src\Entity\Quiz;
use Src\Entity\User;
use Src\LoggerFactory;
use Src\Repository\RepositoryInterface;
use Src\Validator\QuizValidatorInterface;





class QuestionService
{

    public function __construct(private QuizValidatorInterface $validator, private RepositoryInterface $repository, private AlternativeService $alternativeService) {}

    public function save(int $quizId, array $questions){
        $this->validator->validateQuestions($questions);

        $this->processQuestions($quizId, $questions);
    }

    
    private function processQuestions(int $quizId, array $questions): void
    {
        foreach ($questions as $idx => $question) {
            $this->processQuestion( $quizId, $question, $idx);
        }
    }

    
    private function processQuestion(int $quizId, array $question, int $idx): void
    {
        $questionTrim = trim($question['question']);
        $type     = trim($question['type']);
        $options  = array_map('trim', (array) $question['options']);

        if ($this->requiresOptions($type) && count($options) < 2) {
            throw new InvalidArgumentException("A pergunta '{$questionTrim}' exige pelo menos duas alternativas.");
        }
        if($type == 'multiple choice'){
            $type = 'multiple_choice';
        }
        $question = new Question($quizId, $questionTrim, $type, $idx);

        /** @var Question $question */
        $question = $this->repository->save($question);

        $this->alternativeService->save($question->getId(), $options);

        
    }

    private function requiresOptions(string $type): bool
    {
        $type = strtolower($type);
        return in_array($type, ['multiple choice', 'select', 'checkbox', 'radio'], true);
    }
    
    /**
     * Undocumented function
     *
     * @param integer $quizId
     * @return null|Question[]
     */
    public function getQuestionByQuiz(int $quizId): ?array{
        return $this->repository->getByCollumn('quiz_id',$quizId);
    }
}
