<?php

namespace Src\Validator;

use InvalidArgumentException;
use Src\Entity\Question;
use Src\LoggerFactory;
use Src\Service\QuizService;

/**
 * Implementação do validador de lead.
 */
class LeadValidator implements LeadValidatorInterface
{
    public function __construct(private QuizService $quizService) {}

    public function validateQuizExists(string $quizId): void
    {
        if (!$this->quizService->QuizExist($quizId)) {
            throw new InvalidArgumentException("Quiz com id '{$quizId}' não encontrado.", 404);
        }
    }

    public function validateEmailFormat(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("E-mail '{$email}' com formato inválido.", 422);
        }
    }

    public function validateAnswers(string $quizId, array $answers): void
    {
        /** @var Question[] $question */
        $questions = $this->quizService->getQuestionService()->getQuestionByQuiz($quizId);

        if (count($answers) !== count($questions)) {
            throw new InvalidArgumentException('Número de respostas não corresponde ao número de perguntas.', 422);
        }

        foreach ($questions as $question) {
            if (!in_array($question->getId(), array_column($answers, 'questionId'))) {
                throw new InvalidArgumentException("Resposta para a pergunta {$question->getId()} não fornecida.", 422);
            }


            $answer = current(array_filter(array_map(function ($answer) use ($question) {
                if ($answer['questionId'] == $question->getId()) {
                    return $answer;
                }
                return null;
            }, $answers)));

            if (!isset($answer['alternativeId']) && !isset($answer['answerText'])) {
                throw new InvalidArgumentException("Resposta inválida.", 422);
            }

        }
    }
}
