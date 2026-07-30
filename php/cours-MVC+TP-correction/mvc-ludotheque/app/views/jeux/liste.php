<?php
// Affiche $jeux (tableau) et $adherents (tableau, pour le formulaire d'emprunt Niveau 3).
// Toute donnee affichee est echappee avec htmlspecialchars : les jeux viennent de la BDD,
// potentiellement saisis par un utilisateur.

function e(?string $valeur): string
{
    return htmlspecialchars((string) $valeur, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>La Ludotheque du Bourg — Catalogue des jeux</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
      <span class="navbar-brand mb-0 h1">La Ludotheque du Bourg</span>
    </div>
  </nav>

  <main class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0">Catalogue des jeux</h1>
      <a href="index.php?action=ajouter" class="btn btn-primary">+ Ajouter un jeu</a>
    </div>

    <?php if (!empty($_GET['erreur'])): ?>
      <div class="alert alert-danger"><?= e($_GET['erreur']) ?></div>
    <?php endif; ?>

    <table class="table table-striped table-hover bg-white shadow-sm align-middle">
      <thead class="table-dark">
        <tr>
          <th>Titre</th>
          <th>Editeur</th>
          <th>Joueurs</th>
          <th>Duree</th>
          <th>Disponibilite</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($jeux)): ?>
          <tr>
            <td colspan="6" class="text-center text-muted py-4">Aucun jeu enregistre.</td>
          </tr>
        <?php endif; ?>

        <?php foreach ($jeux as $jeu): ?>
          <tr>
            <td><?= e($jeu['titre']) ?></td>
            <td><?= e($jeu['editeur'] ?? '') ?></td>
            <td><?= e((string) $jeu['nb_joueurs_min']) ?> - <?= e((string) $jeu['nb_joueurs_max']) ?></td>
            <td><?= $jeu['duree_minutes'] !== null ? e((string) $jeu['duree_minutes']) . ' min' : '—' ?></td>
            <td>
              <?php if ($jeu['disponible']): ?>
                <span class="badge bg-success">Disponible</span>
              <?php else: ?>
                <span class="badge bg-secondary">Emprunte</span>
              <?php endif; ?>
            </td>
            <td class="text-end">
              <div class="d-flex justify-content-end align-items-center gap-1 flex-wrap">

                <?php if ($jeu['disponible']): ?>
                  <form method="post" action="index.php?action=emprunter" class="d-flex gap-1">
                    <input type="hidden" name="jeu_id" value="<?= e((string) $jeu['id']) ?>">
                    <select name="adherent_id" class="form-select form-select-sm" required>
                      <option value="">Emprunteur...</option>
                      <?php foreach ($adherents as $adherent): ?>
                        <option value="<?= e((string) $adherent['id']) ?>">
                          <?= e($adherent['prenom']) ?> <?= e($adherent['nom']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-success">Emprunter</button>
                  </form>
                <?php else: ?>
                  <form method="post" action="index.php?action=retourner">
                    <input type="hidden" name="jeu_id" value="<?= e((string) $jeu['id']) ?>">
                    <button type="submit" class="btn btn-sm btn-outline-success">Retourner</button>
                  </form>
                <?php endif; ?>

                <a href="index.php?action=modifier&id=<?= e((string) $jeu['id']) ?>" class="btn btn-sm btn-outline-secondary">Modifier</a>

                <form method="post" action="index.php?action=supprimer" onsubmit="return confirm('Supprimer ce jeu ?');">
                  <input type="hidden" name="id" value="<?= e((string) $jeu['id']) ?>">
                  <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                </form>

              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </main>

</body>
</html>
