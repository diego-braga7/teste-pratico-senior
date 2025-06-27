<?php

namespace Src\Validator;

use InvalidArgumentException;
use Src\Service\QuizService;

/**
 * Implementação do validador de lead.
 */
class LeadValidator implements LeadValidatorInterface
{
    public function __construct(private QuizService $quizService) {}

    public function validateQuizExists(string $quizId): void
    {
        if ($this->quizService->getQuiz($quizId) === null) {
            throw new InvalidArgumentException("Quiz com id '{$quizId}' não encontrado.");
        }
    }

    public function validateEmailFormat(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("E-mail '{$email}' com formato inválido.");
        }
    }

    public function validateAnswers(string $quizId, array $answers): void
    {
        $quiz = $this->quizService->getQuiz($quizId);
        
        $questions = $quiz['questions'];

        if (count($answers) !== count($questions)) {
            throw new InvalidArgumentException('Número de respostas não corresponde ao número de perguntas.');
        }

        foreach ($questions as $idx => $q) {
            if (!array_key_exists($idx, $answers)) {
                throw new InvalidArgumentException("Resposta para a pergunta {$idx} não fornecida.");
            }

            $answer = $answers[$idx];
            $type   = strtolower($q['type']);
            $options= $q['options'];

            if (in_array($type, ['múltipla escolha', 'multiple choice', 'checkbox', 'radio'], true)) {
                if (!is_string($answer) || !in_array($answer, $options, true)) {
                    throw new InvalidArgumentException("Resposta inválida para a pergunta '{$q['question']}'.");
                }
            } else {
                if (!is_string($answer) || trim($answer) === '') {
                    throw new InvalidArgumentException("Resposta de texto para a pergunta '{$q['question']}' não pode ser vazia.");
                }
            }
        }
    }
}
