<?php
namespace Src\Entity;


class Question
{
    private ?int $id = null;
    private int $quizId;
    private string $questionText;
    private string $responseType;
    private int $sortOrder;

    public function __construct(int $quizId, string $text, string $type, int $order = 0)
    {
        $this->quizId       = $quizId;
        $this->questionText = $text;
        $this->responseType = $type;
        $this->sortOrder    = $order;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getQuizId(): int { return $this->quizId; }
    public function setQuizId(int $qid): void { $this->quizId = $qid; }

    public function getQuestionText(): string { return $this->questionText; }
    public function setQuestionText(string $text): void { $this->questionText = $text; }

    public function getResponseType(): string { return $this->responseType; }
    public function setResponseType(string $type): void { $this->responseType = $type; }

    public function getSortOrder(): int { return $this->sortOrder; }
    public function setSortOrder(int $order): void { $this->sortOrder = $order; }
}
