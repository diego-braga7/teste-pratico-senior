<?php
namespace Src\Entity;

use DateTime;

class SchemaMigration
{
    private string $version;
    private DateTime $appliedAt;

    public function __construct(string $version)
    {
        $this->version   = $version;
        $this->appliedAt = new DateTime();
    }

    public function getVersion(): string { return $this->version; }
    public function getAppliedAt(): DateTime { return $this->appliedAt; }
}