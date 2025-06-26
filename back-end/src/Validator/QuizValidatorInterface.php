<?php

namespace Src\Validator;


interface QuizValidatorInterface
{
    public function validateTitle(string $title): void;
    public function validateQuestions(array $questions): void;
}