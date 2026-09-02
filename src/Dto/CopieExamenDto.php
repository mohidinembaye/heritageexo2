<?php

namespace App\Dto;

final readonly class CopieExamenDto
{
    public function __construct(
        public int $id,
        public string $dateDepot,
        public float $noteBrute,
        public ?float $noteFinale,
        public string $dateLimite,
        public bool $penaliteAppliquee,
    ) {
    }

    public static function fromEntity(\App\Entity\CopieExamen $copie): self
    {
        return new self(
            $copie->getId(),
            $copie->getDateDepot()->format('Y-m-d'),
            $copie->getNoteBrute(),
            $copie->getNoteFinale(),
            $copie->getDateLimite()->format('Y-m-d'),
            $copie->isPenaliteAppliquee(),
        );
    }
}
