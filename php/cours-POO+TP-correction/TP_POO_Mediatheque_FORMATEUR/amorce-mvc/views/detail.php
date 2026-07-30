<?php
/**
 * AMORCE MVC — VUE détail.
 * @var array $document
 * @var string $titrePage
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($titrePage, ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <a href="index.php?action=liste" class="btn btn-sm btn-link mb-3">&larr; Retour au catalogue</a>
    <h1><?= htmlspecialchars($document['titre'], ENT_QUOTES, 'UTF-8') ?></h1>
    <dl class="row mt-4">
        <dt class="col-sm-3">Type</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($document['type'], ENT_QUOTES, 'UTF-8') ?></dd>
        <dt class="col-sm-3">Année</dt>
        <dd class="col-sm-9"><?= (int) $document['annee'] ?></dd>
        <dt class="col-sm-3">Auteur / Réalisateur</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($document['auteur_ou_realisateur'], ENT_QUOTES, 'UTF-8') ?></dd>
    </dl>
</div>
</body>
</html>
