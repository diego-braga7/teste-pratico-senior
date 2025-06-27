<?php
namespace Src\Entity;

use DateTime;

/**
 * Representa um usuário do sistema.
 */
class User
{
    private ?int $id = null;
    private string $username;
    private string $email;
    private string $passwordHash;
    private string $role;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        string $username,
        string $email,
        string $passwordHash,
        string $role = 'user'
    ) {
        $this->username     = $username;
        $this->email        = $email;
        $this->passwordHash = $passwordHash;
        $this->role         = $role;
        $this->createdAt    = new DateTime();
        $this->updatedAt    = new DateTime();
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getUsername(): string { return $this->username; }
    public function setUsername(string $username): void { $this->username = $username; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getPasswordHash(): string { return $this->passwordHash; }
    public function setPasswordHash(string $hash): void { $this->passwordHash = $hash; }

    public function getRole(): string { return $this->role; }
    public function setRole(string $role): void { $this->role = $role; }

    public function getCreatedAt(): DateTime { return $this->createdAt; }
    public function setCreatedAt(DateTime $dt): void { $this->createdAt = $dt; }

    public function getUpdatedAt(): DateTime { return $this->updatedAt; }
    public function setUpdatedAt(DateTime $dt): void { $this->updatedAt = $dt; }
}
