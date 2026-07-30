<?php
/**
 * ===================================================================
 * AMORCE MVC — VUE
 * ===================================================================
 * Une vue AFFICHE. Elle ne décide de rien.
 *
 * Règles à énoncer aux stagiaires :
 *   - aucune requête SQL ici
 *   - aucune logique métier ici
 *   - uniquement de l'affichage et des conditions de PRÉSENTATION
 *   - htmlspecialchars() sur TOUTE variable affichée (parade XSS)
 *
 * La syntaxe alternative (foreach: / endforeach;) est préférée en vue :
 * elle reste lisible quand le HTML domine.
 *
 * @var array $documents
 * @var string $titrePage
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($titrePage, ENT_QUOTES, 'UTF-8') ?> — La Grande Ourse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">

    <h1 class="mb-4">Médiathèque « La Grande Ourse »</h1>

    <?php if (isset($erreur)): ?>
        <div class="alert alert-warning"><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="row g-3">
    <?php foreach ($documents as $doc): ?>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <?= htmlspecialchars($doc['titre'], ENT_QUOTES, 'UTF-8') ?>
                    </h5>
                    <p class="card-text text-muted mb-1">
                        <?= htmlspecialchars($doc['auteur_ou_realisateur'], ENT_QUOTES, 'UTF-8') ?>
                        — <?= (int) $doc['annee'] ?>
                    </p>
                    <?php if ((bool) $doc['disponible']): ?>
                        <span class="badge text-bg-success">Disponible</span>
                    <?php else: ?>
                        <span class="badge text-bg-secondary">Emprunté</span>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white border-0">
                    <a class="btn btn-sm btn-outline-primary"
                       href="index.php?action=detail&amp;id=<?= (int) $doc['id'] ?>">
                        Voir le détail
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>

</div>
</body>
</html>
