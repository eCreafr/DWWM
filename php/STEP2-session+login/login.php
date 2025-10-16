<?php
/*
 * Composant de connexion - login.php
 * ===================================
 *
 * Ce fichier gère deux affichages différents selon l'état de connexion :
 * 1. Si NON connecté : Affiche un formulaire de connexion
 * 2. Si connecté : Affiche une modale de bienvenue (une seule fois par session)
 *
 * 📚 Concepts abordés :
 * - Affichage conditionnel avec isset()
 * - Validation de formulaire
 * - Messages d'erreur stockés en session
 * - Modale Bootstrap avec JavaScript
 * - Sécurité : htmlspecialchars() pour éviter les injections XSS
 */

// ============================================================================
// CAS 1 : UTILISATEUR NON CONNECTÉ - Affichage du formulaire
// ============================================================================

if (!isset($_SESSION['LOGGED_USER'])) : ?>
    <div class="card col-12 col-md-4 p-3">
        <!--
            Formulaire de connexion
            - method="POST" : Les données sont envoyées de manière sécurisée (non visibles dans l'URL)
            - action="submit-login.php" : Le fichier qui traitera le formulaire
        -->
        <form action="submit-login.php" method="POST">
            <!--
                Affichage conditionnel des erreurs de connexion
                Si une erreur existe en session, on l'affiche puis on la supprime
            -->
            <?php if (!empty($_SESSION['LOGIN_ERROR_MESSAGE'])) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php
                    // htmlspecialchars() protège contre les injections XSS
                    echo htmlspecialchars($_SESSION['LOGIN_ERROR_MESSAGE']);
                    // Suppression du message pour qu'il ne s'affiche qu'une fois
                    unset($_SESSION['LOGIN_ERROR_MESSAGE']);
                    ?>
                </div>
            <?php endif; ?>

            <!-- Champ Email -->
            <div class="mb-3">
                <h2>Vous voulez lire plus ? Abonnez-vous :</h2>
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required
                    aria-describedby="email-help" placeholder="you@exemple.com">
                <div id="email-help" class="form-text">L'email utilisé lors de la création de compte.</div>
            </div>

            <!-- Champ Mot de passe -->
            <div class="mb-3">
                <label for="mdp" class="form-label">Mot de passe</label>
                <!-- type="password" : masque le texte saisi -->
                <input type="password" class="form-control" id="mdp" name="mdp" required>
            </div>

            <!-- Bouton de soumission -->
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
    </div>

<?php else :
    // ============================================================================
    // CAS 2 : UTILISATEUR CONNECTÉ - Affichage de la modale de bienvenue
    // ============================================================================

    /*
     * Vérification si la modale a déjà été affichée
     * On utilise $_SESSION['MODAL_SHOWN'] pour ne l'afficher qu'une seule fois
     */
    if (!isset($_SESSION['MODAL_SHOWN'])) :
        // Marquage : la modale sera affichée, on ne la montrera plus après
        $_SESSION['MODAL_SHOWN'] = true;
?>
        <!-- Modale Bootstrap (popup) -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- En-tête de la modale -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Bonjour <?php echo $_SESSION['LOGGED_USER']['nom']; ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Corps de la modale -->
                    <div class="modal-body">
                        Bienvenue sur le site de l'équipe ! <br>
                        Vous avez à présent accès aux articles et résultats sportifs.
                    </div>

                    <!-- Pied de la modale -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import de Bootstrap JavaScript (nécessaire pour les modales) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

        <!--
            Script JavaScript pour afficher automatiquement la modale
            DOMContentLoaded : attend que la page soit complètement chargée
        -->
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Création d'une instance de modale Bootstrap
                const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
                // Affichage de la modale
                myModal.show();
            });
        </script>
<?php
    endif; // Fin de la vérification MODAL_SHOWN
endif; // Fin de la vérification LOGGED_USER
?>
