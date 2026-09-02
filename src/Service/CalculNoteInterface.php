<?php

namespace App\Service;

interface CalculNoteInterface
{
    public function calculerNoteFinale(\App\Dto\SoumettreCopieDTO $dto): float;
}
