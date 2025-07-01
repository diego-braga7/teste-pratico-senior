<?php

namespace Src\Service;

use Src\Entity\LeadResponse;
use Src\Repository\LeadResponseRepository;
use Src\Repository\RepositoryInterface;

class LeadResponseService
{
    public function __construct(private RepositoryInterface $repository) {
    }

    public function save(int $quizId, int $leadId, array $answers){
        foreach($answers as $answer){
            $alternativeid = $answer['alternativeId'] ?? null;
            $answerText = $answer['answerText'] ?? null;
            $response = new LeadResponse(
                $leadId,
                $quizId,
                $answer['questionId'],
                $alternativeid,
                $answerText
            );
            $this->repository->save($response);
        }
    }
}