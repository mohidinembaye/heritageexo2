<?php


namespace App\Dto;

readonly class SoumettreCopieDTO
{
    public function __construct(
        private float $noteBrute,
        private \DateTimeImmutable $dateDepot,
        private \DateTimeImmutable $dateLimite,
    ) {
        self::verifierNote($this->noteBrute);
    }

    public static function fromRequest(array $data): self
    {
        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            self::convertirNote(self::valeurObligatoire($data, 'noteBrute')),
            self::convertirDate(self::valeurObligatoire($data, 'dateDepot'), 'dateDepot'),
            self::convertirDate(self::valeurObligatoire($data, 'dateLimite'), 'dateLimite'),
        );
    }

    public function getNoteBrute(): float
    {
        return $this->noteBrute;
    }

    public function getDateDepot(): \DateTimeImmutable
    {
        return $this->dateDepot;
    }

    public function getDateLimite(): \DateTimeImmutable
    {
        return $this->dateLimite;
    }

    private static function valeurObligatoire(array $data, string $field): mixed
    {
        if (
            !array_key_exists($field, $data)
            || (empty($data[$field]) && $data[$field] !== 0 && $data[$field] !== 0.0 && $data[$field] !== '0')
        ) {
            throw new \InvalidArgumentException("Le champ {$field} est obligatoire.");
        }

        return $data[$field];
    }

    private static function convertirNote(mixed $value): float
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException('La noteBrute doit être numérique.');
        }

        $note = (float) $value;
        self::verifierNote($note);

        return $note;
    }

    private static function verifierNote(float $note): void
    {
        if (!is_finite($note) || $note < 0.0 || $note > 20.0) {
            throw new \InvalidArgumentException('La noteBrute doit être comprise entre 0 et 20.');
        }
    }

    private static function convertirDate(mixed $value, string $field): \DateTimeImmutable
    {
        if ($value instanceof \DateTimeImmutable) {
            return $value;
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException("Le champ {$field} doit être une date au format Y-m-d.");
        }

        $dateValue = trim($value);
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $dateValue);

        if ($date === false || $date->format('Y-m-d') !== $dateValue) {
            throw new \InvalidArgumentException("Le champ {$field} doit être une date valide au format Y-m-d.");
        }

        return $date;
    }
}
