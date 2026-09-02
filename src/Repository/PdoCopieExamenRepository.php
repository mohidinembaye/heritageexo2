<?php

declare(strict_types=1);

namespace App\Repository;

final class PdoCopieExamenRepository extends AbstractRepository implements CopieExamenRepositoryInterface
{
    public function save(\App\Dto\SoumettreCopieDTO $dto): \App\Entity\CopieExamen
    {
        $statement = $this->prepare(
            'INSERT INTO copies_examen (
                date_depot,
                note_brute,
                note_finale,
                penalite_appliquee,
                date_limite
            ) VALUES (
                :date_depot,
                :note_brute,
                :note_finale,
                :penalite_appliquee,
                :date_limite
            )
            RETURNING id, date_depot, note_brute, penalite_appliquee, date_limite',
            [
                'date_depot' => $dto->dateDepot->format('Y-m-d'),
                'note_brute' => $dto->noteBrute,
                'note_finale' => $dto->noteBrute,
                'penalite_appliquee' => 0,
                'date_limite' => $dto->dateLimite->format('Y-m-d'),
            ],
        );

        $row = $statement->fetch(\PDO::FETCH_OBJ);

        if ($row === false) {
            throw new \RuntimeException('La copie d’examen n’a pas pu être enregistrée.');
        }

        return $this->hydrater($row);
    }

    /**
     * @return list<\App\Entity\CopieExamen>
     */
    public function findAll(): array
    {
        $rows = $this->executeQuery(
            'SELECT id, date_depot, note_brute, penalite_appliquee, date_limite
             FROM copies_examen
             ORDER BY id',
            [],
            false,
        );

        return array_map(
            fn (object $row): \App\Entity\CopieExamen => $this->hydrater($row),
            $rows,
        );
    }

    public function findById(int $id): ?\App\Entity\CopieExamen
    {
        $row = $this->executeQuery(
            'SELECT id, date_depot, note_brute, penalite_appliquee, date_limite
             FROM copies_examen
             WHERE id = :id',
            ['id' => $id],
        );

        if ($row === false) {
            return null;
        }

        return $this->hydrater($row);
    }

    private function hydrater(object $row): \App\Entity\CopieExamen
    {
        return new \App\Entity\CopieExamen(
            (int) $row->id,
            \App\Service\DateService::convertirDate((string) $row->date_depot, 'date_depot'),
            (float) $row->note_brute,
            (float) $row->penalite_appliquee > 0.0,
            \App\Service\DateService::convertirDate((string) $row->date_limite, 'date_limite'),
        );
    }
}
