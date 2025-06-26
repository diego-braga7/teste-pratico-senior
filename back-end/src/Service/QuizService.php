<?php
namespace Src\Service;

use InvalidArgumentException;
use Src\Validator\QuizValidatorInterface;

/**
 * Implementação padrão do validador de quiz.
 */


/**
 * Serviço de negócios para quizzes.
 */
class QuizService
{
    private array $quizzes = [];
    private QuizValidatorInterface $validator;

    public function __construct(QuizValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Cria um novo quiz em memória.
     *
     * @param string $title
     * @param array<int, array{question: string, type: string, options: array<string>}> $questions
     * @return array{id: string, title: string, questions: array}
     */
    public function createQuiz(string $title, array $questions): array
    {
        $this->validator->validateTitle($title);
        $this->validator->validateQuestions($questions);

        $id = $this->generateId();
        $processed = $this->processQuestions($questions);

        $quiz = [
            'id'        => $id,
            'title'     => trim($title),
            'questions' => $processed,
        ];

        $this->quizzes[$id] = $quiz;
        return $quiz;
    }

    /**
     * Gera um ID único para quiz.
     */
    private function generateId(): string
    {
        return uniqid('quiz_', true);
    }

    /**
     * Processa todas as perguntas, delegando a cada pergunta o parse.
     */
    private function processQuestions(array $questions): array
    {
        $result = [];
        foreach ($questions as $idx => $question) {
            $result[] = $this->processQuestion($question, $idx);
        }
        return $result;
    }

    /**
     * Processa e valida uma única pergunta.
     */
    private function processQuestion(array $question, int $idx): array
    {
        $questionTrim = trim($question['question']);
        $type     = trim($question['type']);
        $options  = array_map('trim',(array) $question['options']);

        if ($this->requiresOptions($type) && count($options) < 2) {
            throw new InvalidArgumentException("Pergunta '{$questionTrim}' exige pelo menos duas alternativas.");
        }

        return [
            'question' => $questionTrim,
            'type'     => $type,
            'options'  => $options,
        ];
    }

    /**
     * Verifica se o tipo de pergunta exige alternativas.
     */
    private function requiresOptions(string $type): bool
    {
        $c = strtolower($type);
        return in_array($c, ['multiple choice', 'múltipla escolha', 'checkbox', 'radio'], true);
    }

    /**
     * Retorna todos os quizzes cadastrados.
     */
    public function getAll(): array
    {
        return array_values($this->quizzes);
    }

    /**
     * Recupera um quiz pelo ID.
     */
    public function getQuiz(string $id): ?array
    {
        return $this->quizzes[$id] ?? null;
    }
}
