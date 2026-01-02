<?php
use App\Helpers\UrlHelper;

$isTwoFactorEnabled = !empty($user['two_factor_enabled']);
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php
            // Affichage des messages d'erreur
            if (isset($_SESSION['error_message'])) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                    . htmlspecialchars($_SESSION['error_message'])
                    . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
                unset($_SESSION['error_message']);
            }

            // Affichage des messages de succès
            if (isset($_SESSION['success_message'])) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                    . htmlspecialchars($_SESSION['success_message'])
                    . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
                unset($_SESSION['success_message']);
            }
            ?>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Authentification à deux facteurs (2FA)</h4>
                </div>
                <div class="card-body">
                    <?php if ($isTwoFactorEnabled): ?>
                        <!-- 2FA activé -->
                        <div class="alert alert-success">
                            <h5 class="alert-heading">L'authentification à deux facteurs est activée</h5>
                            <p>Votre compte est protégé par l'authentification à deux facteurs. Un code sera demandé à chaque connexion.</p>
                        </div>

                        <form action="disable-2fa.html" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir désactiver l\'authentification à deux facteurs ?');">
                            <button type="submit" class="btn btn-danger">
                                Désactiver le 2FA
                            </button>
                        </form>

                    <?php else: ?>
                        <!-- 2FA non activé -->
                        <div class="alert alert-info">
                            <h5 class="alert-heading">Sécurisez votre compte</h5>
                            <p>L'authentification à deux facteurs ajoute une couche de sécurité supplémentaire à votre compte.</p>
                        </div>

                        <h5 class="mb-3">Configuration du 2FA</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <h6>Étape 1 : Scannez le QR Code</h6>
                                <p class="text-muted">Utilisez une application comme Google Authenticator, Authy, ou Microsoft Authenticator pour scanner ce code :</p>
                                <div class="text-center mb-3">
                                    <img src="<?= htmlspecialchars($qrCodeUrl) ?>" alt="QR Code 2FA" class="img-fluid border p-2" style="max-width: 200px;">
                                </div>
                                <p class="text-muted small">Ou entrez manuellement cette clé :</p>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($secret) ?>" readonly id="secret-key">
                                    <button class="btn btn-outline-secondary" type="button" onclick="copySecret()">
                                        Copier
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6>Étape 2 : Vérifiez le code</h6>
                                <p class="text-muted">Entrez le code à 6 chiffres affiché dans votre application pour activer le 2FA :</p>

                                <form action="enable-2fa.html" method="POST">
                                    <input type="hidden" name="secret" value="<?= htmlspecialchars($secret) ?>">

                                    <div class="mb-3">
                                        <label for="code" class="form-label">Code de vérification</label>
                                        <input
                                            type="text"
                                            class="form-control form-control-lg text-center"
                                            id="code"
                                            name="code"
                                            placeholder="000000"
                                            maxlength="6"
                                            pattern="[0-9]{6}"
                                            required
                                            autocomplete="off"
                                            style="letter-spacing: 0.5em; font-size: 1.5rem;"
                                        >
                                    </div>

                                    <button type="submit" class="btn btn-success w-100">
                                        Activer le 2FA
                                    </button>
                                </form>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="alert alert-warning">
                            <strong>Important :</strong> Conservez votre clé secrète dans un endroit sûr. Si vous perdez l'accès à votre application d'authentification, vous devrez contacter l'administrateur.
                        </div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <a href="home.html" class="btn btn-outline-primary">
                            Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Applications d'authentification recommandées</h5>
                </div>
                <div class="card-body">
                    <ul>
                        <li><strong>Google Authenticator</strong> - Android et iOS</li>
                        <li><strong>Microsoft Authenticator</strong> - Android et iOS</li>
                        <li><strong>Authy</strong> - Android, iOS, Desktop</li>
                        <li><strong>1Password</strong> - Multi-plateforme (payant)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copySecret() {
    const secretInput = document.getElementById('secret-key');
    secretInput.select();
    document.execCommand('copy');
    alert('Clé secrète copiée dans le presse-papier !');
}

// Auto-format du code
document.getElementById('code')?.addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '');
});
</script>
