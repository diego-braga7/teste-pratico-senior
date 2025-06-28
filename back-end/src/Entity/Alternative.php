<?php
namespace Src\Entity;

class Alternative implements EntityInterface
{
    private ?int $id = null;
    private int $questionId;
    private string $optionText;
    private int $sortOrder;

    public function __construct(int $questionId, string $text, int $order = 0)
    {
        $this->questionId = $questionId;
        $this->optionText = $text;
        $this->sortOrder  = $order;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getQuestionId(): int { return $this->questionId; }
    public function setQuestionId(int $qid): void { $this->questionId = $qid; }

    public function getOptionText(): string { return $this->optionText; }
    public function setOptionText(string $text): void { $this->optionText = $text; }

    public function getSortOrder(): int { return $this->sortOrder; }
    public function setSortOrder(int $order): void { $this->sortOrder = $order; }
}