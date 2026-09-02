<?php

namespace App\Entity;

abstract class AbstractDocument
{
    public function __construct(
        private readonly int $id,
        private readonly \DateTimeImmutable $dateDepot,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDateDepot(): \DateTimeImmutable
    {
        return $this->dateDepot;
    }
}
