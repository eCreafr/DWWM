<?php
// Formulaire Bootstrap d'ajout / modification d'un jeu.
// $jeu (optionnel) : valeurs a pre-remplir, transmises par le controleur
//   - absent ou sans 'id'  -> mode ajout
//   - present avec 'id'    -> mode modification
// $erreur (optionnel) : message de validation transmis par le controleur

$estModification = isset($jeu['id']);
$action = $estModification ? 'modifier' : 'ajouter';
$titrePage = $estModification ? 'Modifier un jeu' : 'Ajouter un jeu';

function valeur(?array $jeu, string $champ): string
{
    return htmlspecialchars((string) ($jeu[$champ] ?? ''), ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($titrePage, ENT_QUOTES, 'UTF-8') ?> — La Ludotheque du Bourg</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
      <span class="navbar-brand mb-0 h1">La Ludotheque du Bourg</span>
    </div>
  </nav>

  <main class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">

        <h1 class="h3 mb-3"><?= htmlspecialchars($titrePage, ENT_QUOTES, 'UTF-8') ?></h1>

        <?php if (!empty($erreur)): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form method="post" action="index.php?action=<?= $action ?>" class="bg-white p-4 rounded shadow-sm">
          <?php if ($estModification): ?>
            <input type="hidden" name="id" value="<?= valeur($jeu, 'id') ?>">
          <?php endif; ?>

          <div class="mb-3">
            <label for="titre" class="form-label">Titre *</label>
            <input type="text" class="form-control" id="titre" name="titre" value="<?= valeur($jeu, 'titre') ?>" required>
          </div>

          <div class="mb-3">
            <label for="editeur" class="form-label">Editeur</label>
            <input type="text" class="form-control" id="editeur" name="editeur" value="<?= valeur($jeu, 'editeur') ?>">
          </div>

          <div class="row">
            <div class="col-6 mb-3">
              <label for="nb_joueurs_min" class="form-label">Joueurs min *</label>
              <input type="number" min="1" class="form-control" id="nb_joueurs_min" name="nb_joueurs_min" value="<?= valeur($jeu, 'nb_joueurs_min') ?>" required>
            </div>
            <div class="col-6 mb-3">
              <label for="nb_joueurs_max" class="form-label">Joueurs max *</label>
              <input type="number" min="1" class="form-control" id="nb_joueurs_max" name="nb_joueurs_max" value="<?= valeur($jeu, 'nb_joueurs_max') ?>" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="duree_minutes" class="form-label">Duree (minutes)</label>
            <input type="number" min="1" class="form-control" id="duree_minutes" name="duree_minutes" value="<?= valeur($jeu, 'duree_minutes') ?>">
          </div>

          <div class="d-flex justify-content-between">
            <a href="index.php?action=liste" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary"><?= $estModification ? 'Enregistrer' : 'Ajouter' ?></button>
          </div>
        </form>

      </div>
    </div>
  </main>

</body>
</html>
