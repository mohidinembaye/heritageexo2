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

    public function soumettre(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            header('Allow: POST');

            return;
        }

        $dto = \App\Dto\SoumettreCopieDTO::fromRequest($_POST);
        $this->service->soumettre($dto);

        header('Location: /copies', true, 303);
    }

    private function rendre(string $vue, array $donnees = [], int $code = 200): void
    {
        http_response_code($code);
        extract($donnees, EXTR_SKIP);
        require dirname(__DIR__, 2) . '/templates/' . $vue;
    }
}
