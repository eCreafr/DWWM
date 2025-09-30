<?php
/**
 * Vue : Formulaire utilisateur
 * Utilisé pour créer ET modifier un utilisateur
 */
require_once __DIR__ . '/../layout/header.php';

// Détermine si on est en mode création ou modification
$estModification = isset($user);
$action = $estModification ? 'user/mettreAJour' : 'user/enregistrer';
?>

<h1><?= htmlspecialchars($titre) ?></h1>

<form action="<?= BASE_URL . $action ?>" method="POST">

    <?php if($estModification): ?>
        <!-- Champ caché pour l'ID en mode modification -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
    <?php endif; ?>

    <div class="form-group">
        <label for="nom">Nom :</label>
        <input type="text"
               id="nom"
               name="nom"
               value="<?= $estModification ? htmlspecialchars($user['nom']) : '' ?>"
               required>
    </div>

    <div class="form-group">
        <label for="email">Email :</label>
        <input type="email"
               id="email"
               name="email"
               value="<?= $estModification ? htmlspecialchars($user['email']) : '' ?>"
               required>
    </div>

    <div class="actions">
        <button type="submit" class="btn">
            <?= $estModification ? '💾 Enregistrer les modifications' : '➕ Créer l\'utilisateur' ?>
        </button>
        <a href="<?= BASE_URL ?>user/index" class="btn btn-danger">❌ Annuler</a>
    </div>

</form>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>