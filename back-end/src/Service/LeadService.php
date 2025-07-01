<?php

namespace Src\Service;

use SplObserver;
use SplSubject;
use Src\Entity\Lead;
use Src\Repository\LeadRepository;
use Src\Validator\LeadValidatorInterface;

class LeadService implements SplSubject
{
    private \SplObjectStorage $observers;
    private Lead $lead;

    public function __construct(private LeadValidatorInterface $validator, private LeadRepository $leadRepository, private LeadResponseService $leadResponseService)
    {
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
     * @return Lead{id: string, quizId: string, name: string, email: string, answers: array}
     */
    public function submitLead(string $quizId, string $name, string $email, array $answers): Lead
    {
        $this->validator->validateQuizExists($quizId);
        $this->validator->validateEmailFormat($email);
        $this->validator->validateAnswers($quizId, $answers);

        /** @var ?Lead $lead */
        $lead = $this->leadRepository->getByCollumn('email', $email);
        if (!$lead) {
            /** @var Lead $lead */
            $lead = $this->leadRepository->save((
                new Lead($name, $email)
            ));
        }
        $this->lead = $lead;

        $this->leadResponseService->save($quizId, $lead->getId(), $answers);

        $this->notify();

        return $lead;
    }

    public function getLastSubmittedLead(): Lead
    {
        return $this->lead;
    }
}
