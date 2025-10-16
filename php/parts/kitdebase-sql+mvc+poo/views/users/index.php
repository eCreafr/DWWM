<?php
/**
 * Vue : Liste des utilisateurs
 * Affiche tous les utilisateurs dans un tableau
 */
require_once __DIR__ . '/../layout/header.php';
?>

<h1><?= htmlspecialchars($titre) ?></h1>

<div class="actions">
    <a href="<?= BASE_URL ?>user/creer" class="btn">➕ Ajouter un utilisateur</a>
    <a href="<?= BASE_URL ?>auth/logout" class="btn btn-danger" style="float: right;">🚪 Déconnexion</a>
</div>

<?php if(empty($users)): ?>
    <p>Aucun utilisateur trouvé. Commencez par en créer un !</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['nom']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['date_creation']) ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>user/modifier/<?= $user['id'] ?>" class="btn">✏️ Modifier</a>
                        <a href="<?= BASE_URL ?>user/supprimer/<?= $user['id'] ?>"
                           class="btn btn-danger"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                            🗑️ Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>