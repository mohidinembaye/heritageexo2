<?php

$old = $old ?? [];
$erreurs = $erreurs ?? [];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Soumettre une copie</title>
</head>
<body>
    <main>
        <h1>Soumettre une copie</h1>

        <?php if ($erreurs !== []): ?>
            <div role="alert">
                <h2>La soumission est invalide</h2>
                <ul>
                    <?php foreach ($erreurs as $erreur): ?>
                        <li><?= htmlspecialchars((string) $erreur, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="/copies">
            <div>
                <label for="noteBrute">Note brute</label>
                <input type="number" id="noteBrute" name="noteBrute" min="0" max="20" step="0.01" required value="<?= htmlspecialchars((string) ($old['noteBrute'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div>
                <label for="dateDepot">Date de depot</label>
                <input type="date" id="dateDepot" name="dateDepot" required value="<?= htmlspecialchars((string) ($old['dateDepot'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div>
                <label for="dateLimite">Date limite</label>
                <input type="date" id="dateLimite" name="dateLimite" required value="<?= htmlspecialchars((string) ($old['dateLimite'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <button type="submit">Soumettre</button>
        </form>

        <a href="/copies">Voir les copies</a>
    </main>
</body>
</html>
