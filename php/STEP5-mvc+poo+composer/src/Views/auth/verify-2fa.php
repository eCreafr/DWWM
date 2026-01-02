<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Authentification à deux facteurs</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Veuillez entrer le code à 6 chiffres généré par votre application d'authentification (Google Authenticator, Authy, etc.).</p>

                    <form action="verify-2fa-post.html" method="POST">
                        <div class="mb-3">
                            <label for="code" class="form-label">Code d'authentification</label>
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
                                autofocus
                                style="letter-spacing: 0.5em; font-size: 1.5rem;"
                            >
                            <small class="form-text text-muted">Entrez les 6 chiffres affichés dans votre application</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Vérifier
                            </button>
                            <a href="logout.html" class="btn btn-outline-secondary">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-3 text-center">
                <small class="text-muted">
                    Problème avec votre code ? Contactez l'administrateur.
                </small>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-format du code : ajoute un espace tous les 3 chiffres pour la lisibilité
document.getElementById('code').addEventListener('input', function(e) {
    // Supprime tout sauf les chiffres
    this.value = this.value.replace(/\D/g, '');
});
</script>
