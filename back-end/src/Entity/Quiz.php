<?php
namespace Src\Entity;

use DateTime;

class Quiz implements EntityInterface
{
    private ?int $id = null;
    private string $title;
    private int $createdBy;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(string $title, int $createdBy)
    {
        $this->title     = $title;
        $this->createdBy = $createdBy;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): void { $this->title = $title; }

    public function getCreatedBy(): int { return $this->createdBy; }
    public function setCreatedBy(int $userId): void { $this->createdBy = $userId; }

    public function getCreatedAt(): DateTime { return $this->createdAt; }
    public function setCreatedAt(DateTime $dt): void { $this->createdAt = $dt; }

    public function getUpdatedAt(): DateTime { return $this->updatedAt; }
    public function setUpdatedAt(DateTime $dt): void { $this->updatedAt = $dt; }
}