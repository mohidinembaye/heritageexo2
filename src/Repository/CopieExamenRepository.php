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

    public function lister(): array
    {
        $lignes = $this->query(
            'SELECT id, date_depot, note_brute, note_finale, penalite_appliquee, date_limite
             FROM copies_examen
             ORDER BY id DESC',
            false,
        );

        return array_map(
            fn (object $ligne): CopieExamen => $this->hydrater($ligne),
            $lignes,
        );
    }

    public function trouver(int $id): ?CopieExamen
    {
        $ligne = $this->executeQuery(
            'SELECT id, date_depot, note_brute, note_finale, penalite_appliquee, date_limite
             FROM copies_examen
             WHERE id = :id',
            ['id' => $id],
        );

        return $ligne === false ? null : $this->hydrater($ligne);
    }

    private function hydrater(object $ligne): CopieExamen
    {
        return new CopieExamen(
            (int) $ligne->id,
            new \DateTimeImmutable($ligne->date_depot),
            (float) $ligne->note_brute,
            (float) $ligne->penalite_appliquee > 0,
            new \DateTimeImmutable($ligne->date_limite),
            (float) $ligne->note_finale,
        );
    }
}
