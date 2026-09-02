<?php

namespace App\Entity;

final class CopieExamen extends AbstractDocument
{
    public function __construct(
        int $id,
        \DateTimeImmutable $dateDepot,
        private readonly float $noteBrute,
        private readonly bool $penaliteAppliquee,
        private readonly \DateTimeImmutable $dateLimite,
    ) {
        self::verifierNote($noteBrute, 'noteBrute');

        parent::__construct($id, $dateDepot);
    }

    public function getNoteBrute(): float
    {
        return $this->noteBrute;
    }

    public function isPenaliteAppliquee(): bool
    {
        return $this->penaliteAppliquee;
    }

    public function getDateLimite(): \DateTimeImmutable
    {
        return $this->dateLimite;
    }

    public static function fromPersistence(
        int $id,
        \DateTimeImmutable $dateDepot,
        float $noteBrute,
        bool $penaliteAppliquee,
        \DateTimeImmutable $dateLimite,
    ): self {
        return new self($id, $dateDepot, $noteBrute, $penaliteAppliquee, $dateLimite);
    }

    private static function verifierNote(float $note, string $nom): void
    {
        if ($note < 0.0 || $note > 20.0) {
            throw new \InvalidArgumentException(
                sprintf('%s doit être comprise entre 0 et 20.', $nom),
            );
        }
    }
}
