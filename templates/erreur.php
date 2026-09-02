<?php

$code = $code ?? 500;
$message = $message ?? 'Une erreur inattendue est survenue.';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Erreur</title>
</head>
<body>
    <main role="alert">
        <h1>Erreur <?= htmlspecialchars((string) $code, ENT_QUOTES, 'UTF-8') ?></h1>
        <p><?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8') ?></p>
        <a href="/copies">Retour a la liste</a>
    </main>
</body>
</html>
