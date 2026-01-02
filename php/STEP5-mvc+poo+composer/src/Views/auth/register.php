<!-- Vue du formulaire d'inscription -->
<!-- Cette vue affiche un formulaire permettant aux nouveaux utilisateurs de créer un compte -->

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-lg-5">
            <!-- Carte Bootstrap pour un design épuré -->
            <div class="card shadow">
                <div class="card-body p-4">
                    <!-- Titre du formulaire -->
                    <h2 class="card-title text-center mb-4">Inscription</h2>

                    <?php
                    // Affichage du message d'erreur (si validation échouée)
                    // Ces messages sont stockés en session et affichés une seule fois
                    if (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                        // Supprime le message après affichage pour qu'il ne réapparaisse pas
                        unset($_SESSION['error_message']);
                    }

                    // Affichage du message de succès (si nécessaire)
                    if (isset($_SESSION['success_message'])) {
                        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
                        unset($_SESSION['success_message']);
                    }

                    // Récupération des données du formulaire en cas d'erreur (pour réaffichage)
                    $formData = $_SESSION['form_data'] ?? [];
                    $name = $formData['name'] ?? '';
                    $email = $formData['email'] ?? '';
                    unset($_SESSION['form_data']); // Nettoyage après récupération
                    ?>

                    <!-- Formulaire d'inscription -->
                    <!-- L'action pointe vers registerpost.html qui sera géré par le Router -->
                    <form action="<?= BASE_URL ?>/registerpost.html" method="POST">

                        <!-- Champ Nom -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom complet</label>
                            <input type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                placeholder="Jean Dupont"
                                value="<?= htmlspecialchars($name) ?>"
                                required
                                minlength="2"
                                autocomplete="name">
                            <small class="form-text text-muted">Minimum 2 caractères</small>
                        </div>

                        <!-- Champ Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="votre.email@example.com"
                                value="<?= htmlspecialchars($email) ?>"
                                required
                                autocomplete="email">
                            <small class="form-text text-muted">Nous vérifions la validité de l'email (DNS + MX)</small>
                        </div>

                        <!-- Champ Mot de passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Minimum 8 caractères"
                                required
                                minlength="8"
                                autocomplete="new-password">
                            <small class="form-text text-muted">Au moins 8 caractères</small>
                        </div>

                        <!-- Champ Confirmation mot de passe -->
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                            <input type="password"
                                class="form-control"
                                id="confirm_password"
                                name="confirm_password"
                                placeholder="Répétez votre mot de passe"
                                required
                                minlength="8"
                                autocomplete="new-password">
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                S'inscrire
                            </button>
                        </div>
                    </form>

                    <!-- Informations supplémentaires -->
                    <div class="alert alert-info mt-4 small">
                        <strong>Validation stricte :</strong><br>
                        L'email est vérifié avec <strong>Egulias Email Validator</strong> :
                        <ul class="mb-0 mt-1">
                            <li>Syntaxe RFC 5322</li>
                            <li>Vérification DNS du domaine</li>
                            <li>Vérification des enregistrements MX</li>
                        </ul>
                    </div>

                    <!-- Lien vers la page de connexion -->
                    <div class="text-center mt-3">
                        <p class="mb-1">Vous avez déjà un compte ?</p>
                        <a href="<?= BASE_URL ?>/login.html" class="btn btn-outline-secondary btn-sm">
                            Se connecter
                        </a>
                    </div>

                    <!-- Lien vers la page d'accueil -->
                    <div class="text-center mt-2">
                        <a href="<?= BASE_URL ?>/home.html" class="text-muted small">
                            Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
