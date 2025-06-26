<?php

namespace Src\Validator;

use InvalidArgumentException;
use Src\LoggerFactory;

class QuizValidator implements QuizValidatorInterface
{
    public function validateTitle(string $title): void
    {
        $title = trim($title);
        if ($title === '') {
            throw new InvalidArgumentException('Título do quiz não pode ser vazio.');
        }
    }

    public function validateQuestions(array $questions): void
    {
        if (empty($questions)) {
            throw new InvalidArgumentException('Deve existir ao menos uma pergunta.');
        }

        foreach ($questions as $idx => $question) {
            if (!isset($question['question'], $question['type'], $question['options'])
                || !is_string($question['question'])
                || !is_string($question['type'])
                || !is_array($question['options'])
            ) {
                throw new InvalidArgumentException("Formato inválido na pergunta no índice {$idx}.");
            }
        }
    }
}