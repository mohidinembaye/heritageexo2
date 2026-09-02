<?php

$copie = $copie ?? null;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Detail d'une copie</title>
</head>
<body>
    <main>
        <?php if ($copie === null): ?>
            <h1>Copie introuvable</h1>
        <?php else: ?>
            <h1>Detail de la copie #<?= htmlspecialchars((string) $copie->id, ENT_QUOTES, 'UTF-8') ?></h1>
            <dl>
                <dt>Date de depot</dt>
                <dd><?= htmlspecialchars($copie->dateDepot, ENT_QUOTES, 'UTF-8') ?></dd>
                <dt>Date limite</dt>
                <dd><?= htmlspecialchars($copie->dateLimite, ENT_QUOTES, 'UTF-8') ?></dd>
                <dt>Note brute</dt>
                <dd><?= htmlspecialchars((string) $copie->noteBrute, ENT_QUOTES, 'UTF-8') ?>/20</dd>
                <dt>Note finale</dt>
                <dd><?= htmlspecialchars((string) $copie->noteFinale, ENT_QUOTES, 'UTF-8') ?>/20</dd>
                <dt>Penalite appliquee</dt>
                <dd><?= $copie->penaliteAppliquee ? 'Oui' : 'Non' ?></dd>
            </dl>
        <?php endif; ?>

        <a href="/copies">Retour a la liste</a>
    </main>
</body>
</html>
