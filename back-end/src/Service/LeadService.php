<?php
namespace Src\Service;

use SplObserver;
use SplSubject;
use Src\Validator\LeadValidatorInterface;

class LeadService implements SplSubject
{
    private array $leads = [];
    private LeadValidatorInterface $validator;
    private \SplObjectStorage $observers;
    private array $lastLead = [];

    public function __construct(LeadValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->observers = new \SplObjectStorage();
    }

   
    public function attach(SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    
    public function detach(SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    
    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    /**
     * Submete um lead para o quiz.
     *
     * @param string $quizId
     * @param string $name
     * @param string $email
     * @param array<int, mixed> $answers
     * @return array{id: string, quizId: string, name: string, email: string, answers: array}
     */
    public function submitLead(string $quizId, string $name, string $email, array $answers): array
    {
        $this->validator->validateQuizExists($quizId);
        $this->validator->validateEmailFormat($email);
        $this->validator->validateAnswers($quizId, $answers);

        $id   = uniqid('lead_', true);
        $lead = [
            'id'      => $id,
            'quizId'  => $quizId,
            'name'    => trim($name),
            'email'   => trim($email),
            'answers' => $answers,
        ];

        $this->leads[$id] = $lead;
        $this->lastLead   = $lead;

        $this->notify();

        return $lead;
    }

    /**
     * Recupera o último lead submetido.
     */
    public function getLastSubmittedLead(): array
    {
        return $this->lastLead;
    }

    /**
     * Retorna todos os leads em memória.
     */
    public function getAllLeads(): array
    {
        return array_values($this->leads);
    }

    /**
     * Recupera um lead pelo ID.
     */
    public function getLead(string $id): ?array
    {
        return $this->leads[$id] ?? null;
    }
}
