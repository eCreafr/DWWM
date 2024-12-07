<?php

// Simuler la connexion pour ce cas d'exemple
// $_SESSION['LOGGED_USER'] devrait être défini lors de la connexion réussie
if (!isset($_SESSION['LOGGED_USER'])) : ?>
    <div class="card col-12 col-md-4 p-3">
        <form action="submit-login.php" method="POST">
            <?php if (!empty($_SESSION['LOGIN_ERROR_MESSAGE'])) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($_SESSION['LOGIN_ERROR_MESSAGE']); ?>
                    <?php unset($_SESSION['LOGIN_ERROR_MESSAGE']); ?>
                </div>
            <?php endif; ?>
            <div class="mb-3">
                <h2>Vous voulez lire plus ? Abonnez-vous :</h2>
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required
                    aria-describedby="email-help" placeholder="you@exemple.com">
                <div id="email-help" class="form-text">L'email utilisé lors de la création de compte.</div>
            </div>
            <div class="mb-3">
                <label for="mdp" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mdp" name="mdp" required>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
    </div>
    <?php else :
    // Vérifie si la modal a déjà été affichée
    if (!isset($_SESSION['MODAL_SHOWN'])) :
        $_SESSION['MODAL_SHOWN'] = true; // Définit comme affichée
    ?>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> Bonjour <?php echo $_SESSION['LOGGED_USER']['nom']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Bienvenue sur le site de l'équipe ! <br> Vous avez à présent accès aux articles et résultats sportifs.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
                myModal.show();
            });
        </script>
<?php
    endif;
endif;
?>