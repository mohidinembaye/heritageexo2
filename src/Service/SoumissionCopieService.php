<?php

namespace App\Service;

use App\Dto\SoumettreCopieDTO;
use App\Entity\CopieExamen;
use App\Repository\CopieExamenRepository;

final class SoumissionCopieService
{
    public function __construct(
        private readonly CalculNoteInterface $calculateur,
        private readonly CopieExamenRepository $repository,
    ) {
    }

    public function soumettre(SoumettreCopieDTO $dto): CopieExamen
    {
        $copie = new CopieExamen(
            0,
            $dto->dateDepot,
            $dto->noteBrute,
            $dto->dateDepot > $dto->dateLimite,
            $dto->dateLimite,
        );

        return $this->calculerEtEnregistrer($copie);
    }

    public function calculerEtEnregistrer(CopieExamen $copie): CopieExamen
    {
        $noteFinale = $copie->calculerNoteFinale($this->calculateur);

        $copie = $copie->withNoteFinale($noteFinale);

        return $this->repository->enregistrer($copie);
    }
}
