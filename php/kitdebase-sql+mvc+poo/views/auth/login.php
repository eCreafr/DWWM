<?php require_once __DIR__ . '/../layout/header.php'; ?>

<h1><?= $titre ?></h1>

<?php if (isset($_SESSION['erreur'])): ?>
    <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        <?= $_SESSION['erreur'] ?>
        <?php unset($_SESSION['erreur']); ?>
    </div>
<?php endif; ?>

<form action="<?= BASE_URL ?>auth/authentifier" method="POST">
    <div class="form-group">
        <label for="login">Identifiant :</label>
        <input type="text" id="login" name="login" required>
    </div>

    <div class="form-group">
        <label for="motDePasse">Mot de passe :</label>
        <input type="password" id="motDePasse" name="motDePasse" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
    </div>

    <div class="actions">
        <button type="submit" class="btn">Se connecter</button>
    </div> Login : admin <br>
    Mot de passe : admin123
</form>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>