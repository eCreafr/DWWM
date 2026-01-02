<!-- Vue du formulaire de connexion -->
<!-- Cette vue affiche un formulaire permettant aux utilisateurs de se connecter -->

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-lg-4">
            <!-- Carte Bootstrap pour un design épuré -->
            <div class="card shadow">
                <div class="card-body p-4">
                    <!-- Titre du formulaire -->
                    <h2 class="card-title text-center mb-4">Connexion</h2>

                    <?php
                    // Affichage du message d'erreur (si identifiants incorrects)
                    // Ces messages sont stockés en session et affichés une seule fois
                    if (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
                        // Supprime le message après affichage pour qu'il ne réapparaisse pas
                        unset($_SESSION['error_message']);
                    }

                    // Affichage du message de succès (par exemple après déconnexion)
                    if (isset($_SESSION['success_message'])) {
                        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
                        unset($_SESSION['success_message']);
                    }
                    ?>

                    <!-- Formulaire de connexion -->
                    <!-- L'action pointe vers loginpost.html qui sera géré par le Router -->
                    <form action="<?= BASE_URL ?>/loginpost.html" method="POST">

                        <!-- Champ Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="votre.email@example.com"
                                required
                                autocomplete="email">
                        </div>

                        <!-- Champ Mot de passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Votre mot de passe"
                                required
                                autocomplete="current-password">
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Se connecter
                            </button>
                        </div>
                    </form>

                    <!-- Informations pour les tests (à retirer en production !) -->
                    <div class="alert alert-info mt-4 small">
                        <strong>Comptes de test :</strong><br>
                        Admin : raphael.lang@gmail.com / 123 (haché en bdd)<br>
                        User : jane@example.com / 123 (haché en bdd)
                    </div>

                    <!-- Lien vers la page d'accueil -->
                    <div class="text-center mt-3">
                        <a href="<?= BASE_URL ?>/home.html" class="text-muted small">
                            Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>