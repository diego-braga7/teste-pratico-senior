<?php
namespace Src\Entity;

use DateTime;

class Lead
{
    private ?int $id = null;
    private string $name;
    private string $email;
    private DateTime $createdAt;

    public function __construct(string $name, string $email)
    {
        $this->name      = $name;
        $this->email     = $email;
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getCreatedAt(): DateTime { return $this->createdAt; }
    public function setCreatedAt(DateTime $dt): void { $this->createdAt = $dt; }
}