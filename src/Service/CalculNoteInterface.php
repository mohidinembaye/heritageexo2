<?php

namespace App\Service;

interface CalculNoteInterface
{
    public function calculerNoteFinale(\App\Entity\CopieExamen $copie): float;
}
