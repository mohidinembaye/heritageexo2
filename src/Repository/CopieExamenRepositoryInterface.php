<?php

declare(strict_types=1);

namespace App\Repository;

interface CopieExamenRepositoryInterface
{
    public function save(\App\Dto\SoumettreCopieDTO $dto): \App\Entity\CopieExamen;

    /**
     * @return list<\App\Entity\CopieExamen>
     */
    public function findAll(): array;

    public function findById(int $id): ?\App\Entity\CopieExamen;
}
