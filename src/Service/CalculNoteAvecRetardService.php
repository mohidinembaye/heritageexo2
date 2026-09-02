<?php

namespace App\Service;

final class CalculNoteAvecRetardService implements CalculNoteInterface
{
    private const PENALITE_POINTS = 2.0;

    public function calculerNoteFinale(\App\Dto\SoumettreCopieDTO $dto): float
    {
        if ($dto->dateDepot <= $dto->dateLimite) {
            return $dto->noteBrute;
        }

        return max(0.0, $dto->noteBrute - self::PENALITE_POINTS);
    }
}
