<?php

namespace App\Repository;

use App\Entity\CopieExamen;

final class CopieExamenRepository extends AbstractRepository
{
    public function enregistrer(CopieExamen $copie): CopieExamen
    {
        $this->executeUpdate(
            'INSERT INTO copies_examen (date_depot, note_brute, note_finale, penalite_appliquee, date_limite)
             VALUES (:dateDepot, :noteBrute, :noteFinale, :penaliteAppliquee, :dateLimite)',
            [
                'dateDepot' => $copie->getDateDepot()->format('Y-m-d'),
                'noteBrute' => $copie->getNoteBrute(),
                'noteFinale' => $copie->getNoteFinale(),
                'penaliteAppliquee' => $copie->isPenaliteAppliquee() ? 2.0 : 0.0,
                'dateLimite' => $copie->getDateLimite()->format('Y-m-d'),
            ],
        );

        return $copie;
    }
}
