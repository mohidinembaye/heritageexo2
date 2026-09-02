<?php


namespace App\Dto;

readonly class SoumettreCopieDTO
{
    public function __construct(
        public float $noteBrute,
        public \DateTimeImmutable $dateDepot,
        public \DateTimeImmutable $dateLimite,
    ) {
        \App\Service\NoteService::verifierNote($this->noteBrute);
    }

    public static function fromRequest(array $data): self
    {
        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            \App\Service\NoteService::convertirNote(
                \App\Service\NoteService::valeurObligatoire($data, 'noteBrute'),
            ),
            \App\Service\DateService::convertirDate(
                \App\Service\NoteService::valeurObligatoire($data, 'dateDepot'),
                'dateDepot',
            ),
            \App\Service\DateService::convertirDate(
                \App\Service\NoteService::valeurObligatoire($data, 'dateLimite'),
                'dateLimite',
            ),
        );
    }

}
