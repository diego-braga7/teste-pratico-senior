<?php
namespace Src\Validator;

interface LeadValidatorInterface
{
    public function validateQuizExists(string $quizId): void;
    public function validateEmailFormat(string $email): void;
    public function validateAnswers(string $quizId, array $answers): void;
}
