<?php
namespace Src\Entity;

use DateTime;

class LeadResponse implements EntityInterface
{
    private ?int $id = null;
    private int $leadId;
    private int $quizId;
    private int $questionId;
    private ?int $alternativeId;
    private ?string $answerText;
    private DateTime $respondedAt;

    public function __construct(
        int $leadId,
        int $quizId,
        int $questionId,
        ?int $alternativeId,
        ?string $answerText
    ) {
        $this->leadId        = $leadId;
        $this->quizId        = $quizId;
        $this->questionId    = $questionId;
        $this->alternativeId = $alternativeId;
        $this->answerText    = $answerText;
        $this->respondedAt   = new DateTime();
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getLeadId(): int { return $this->leadId; }
    public function getQuizId(): int { return $this->quizId; }
    public function getQuestionId(): int { return $this->questionId; }
    public function getAlternativeId(): ?int { return $this->alternativeId; }
    public function getAnswerText(): ?string { return $this->answerText; }
    public function getRespondedAt(): DateTime { return $this->respondedAt; }
}