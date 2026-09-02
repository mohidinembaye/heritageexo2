<?php

namespace App\Controller;

final class CopieExamenController
{
    public function __construct(
        private readonly \App\Service\SoumissionCopieService $service,
        private readonly \App\Repository\CopieExamenRepository $repository,
    ) {
    }

    public function formulaire(): void
    {
        $this->rendre('home.php');
    }

    private function rendre(string $vue, array $donnees = [], int $code = 200): void
    {
        http_response_code($code);
        extract($donnees, EXTR_SKIP);
        require dirname(__DIR__, 2) . '/templates/' . $vue;
    }
}
