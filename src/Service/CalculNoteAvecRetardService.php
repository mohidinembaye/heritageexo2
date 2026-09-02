<?php

namespace App\Service;

final class CalculNoteAvecRetardService implements CalculNoteInterface
{
    private const PENALITE_POINTS = 2.0;

    public function calculerNoteFinale(\App\Entity\CopieExamen $copie): float
    {
        if ($copie->getDateDepot() <= $copie->getDateLimite()) {
            return $copie->getNoteBrute();
        }

        return $copie->getNoteBrute() - self::PENALITE_POINTS;
    }
}
