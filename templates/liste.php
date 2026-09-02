<?php

$copies = $copies ?? [];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Copies d'examen</title>
</head>
<body>
    <main>
        <h1>Copies d'examen</h1>
        <a href="/copies/nouvelle">Soumettre une copie</a>

        <?php if ($copies === []): ?>
            <p>Aucune copie n'a ete soumise.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date de depot</th>
                        <th>Note brute</th>
                        <th>Note finale</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($copies as $copie): ?>
                        <tr>
                            <td><?= htmlspecialchars((string) $copie->getId(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($copie->getDateDepot()->format('Y-m-d'), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars((string) $copie->getNoteBrute(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars((string) $copie->getNoteFinale(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><a href="/copies/<?= rawurlencode((string) $copie->getId()) ?>">Voir</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>
