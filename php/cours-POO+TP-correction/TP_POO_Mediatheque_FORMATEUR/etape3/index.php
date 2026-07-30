<?php
declare(strict_types=1);

/**
 * TP POO — ÉTAPE 3 — Affichage du catalogue.
 * FOURNI pour le niveau 1. Vous l'enrichirez au niveau 2.
 *
 * Ouverture : http://localhost/tp-poo-mediatheque/etape3/index.php
 */

require_once __DIR__ . '/src/DocumentRepository.php';

$repository = new DocumentRepository();

try {
    $documents = $repository->findAll();
} catch (Throwable $e) {
    // Message générique pour l'utilisateur, détail dans les logs.
    error_log($e->getMessage());
    die('Une erreur est survenue lors du chargement du catalogue.');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Médiathèque La Grande Ourse — Catalogue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">

    <h1 class="mb-4">Médiathèque « La Grande Ourse »</h1>
    <p class="text-muted">
        <?= count($documents) ?> document(s) au catalogue.
    </p>

    <table class="table table-striped align-middle bg-white">
        <thead class="table-dark">
            <tr>
                <th>Titre</th>
                <th>Type</th>
                <th>Année</th>
                <th>Auteur / Réalisateur</th>
                <th>Statut</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($documents as $doc): ?>
            <tr>
                <!--
                  htmlspecialchars() est OBLIGATOIRE sur toute donnée
                  affichée : c'est la parade contre la faille XSS.
                  Un titre contenant <script>alert(1)</script> serait
                  sinon exécuté par le navigateur.
                -->
                <td><?= htmlspecialchars($doc['titre'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($doc['type'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= (int) $doc['annee'] ?></td>
                <td><?= htmlspecialchars($doc['auteur_ou_realisateur'], ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <?php if ((bool) $doc['disponible']): ?>
                        <span class="badge text-bg-success">Disponible</span>
                    <?php else: ?>
                        <span class="badge text-bg-secondary">Emprunté</span>
                    <?php endif; ?>
                </td>
                <td>
                    <!-- TODO NIVEAU 2 : ce lien ne fonctionnera qu'une fois
                         emprunter.php complété. -->
                    <?php if ((bool) $doc['disponible']): ?>
                        <a class="btn btn-sm btn-primary"
                           href="emprunter.php?id=<?= (int) $doc['id'] ?>&amp;action=emprunter">
                            Emprunter
                        </a>
                    <?php else: ?>
                        <a class="btn btn-sm btn-outline-secondary"
                           href="emprunter.php?id=<?= (int) $doc['id'] ?>&amp;action=rendre">
                            Rendre
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
</body>
</html>
