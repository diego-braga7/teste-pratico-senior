<?php

namespace Src\Validator;

use InvalidArgumentException;

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

        foreach ($questions as $idx => $q) {
            if (!isset($q['question'], $q['type'], $q['options'])
                || !is_string($q['question'])
                || !is_string($q['type'])
                || !is_array($q['options'])
            ) {
                throw new InvalidArgumentException("Formato inválido na pergunta no índice {$idx}.");
            }
        }
    }
}