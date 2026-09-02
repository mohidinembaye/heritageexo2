<?php

namespace App\Service;

final class SoumissionCopieService
{
    public function __construct(
        private readonly \App\Service\CalculNoteInterface $calculateur,
        private readonly \App\Repository\CopieExamenRepository $repository,
    ) {
    }

    public function soumettre(\App\Dto\SoumettreCopieDTO $dto): \App\Entity\CopieExamen
    {
        $copie = new \App\Entity\CopieExamen(
            0,
            $dto->dateDepot,
            $dto->noteBrute,
            $dto->dateDepot > $dto->dateLimite,
            $dto->dateLimite,
        );

        return $this->calculerEtEnregistrer($copie);
    }

    public function calculerEtEnregistrer(\App\Entity\CopieExamen $copie): \App\Entity\CopieExamen
    {
        $noteFinale = $copie->calculerNoteFinale($this->calculateur);

        $copie = $copie->withNoteFinale($noteFinale);

        return $this->repository->enregistrer($copie);
    }

    public function lister(): array
    {
        return array_map(
            \App\Dto\CopieExamenDto::fromEntity(...),
            $this->repository->lister(),
        );
    }

    public function trouver(int $id): ?\App\Dto\CopieExamenDto
    {
        $copie = $this->repository->trouver($id);

        return $copie === null
            ? null
            : \App\Dto\CopieExamenDto::fromEntity($copie);
    }
}
