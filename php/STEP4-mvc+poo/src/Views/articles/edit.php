<!-- Vue du formulaire de modification d'article -->
<!-- Cette vue affiche le formulaire pour modifier un article existant -->

<div class="d-flex flex-column min-vh-100">
    <div class="container">

        <!-- Affichage du message de succès -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success my-4">
                <?= htmlspecialchars($_SESSION['success_message']); ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <!-- Affichage des messages d'erreur -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger my-4">
                <?= htmlspecialchars($_SESSION['error_message']); ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <h1>Mettre à jour "<?= htmlspecialchars($article['titre']); ?>"</h1>

        <!-- Formulaire de modification (POST vers editpost.html) -->
        <form action="<?= BASE_URL ?>/editpost.html" method="POST">

            <!-- Champ caché contenant l'ID de l'article -->
            <input type="hidden" name="id" value="<?= $article['id']; ?>">

            <!-- Champ Titre pré-rempli avec la valeur actuelle -->
            <div class="mb-3">
                <label for="titre" class="form-label">Titre</label>
                <input type="text"
                    class="form-control"
                    id="titre"
                    name="titre"
                    aria-describedby="titre-help"
                    value="<?= htmlspecialchars($article['titre']); ?>"
                    required>
                <div id="titre-help" class="form-text">
                    Choisissez un titre percutant !
                </div>
            </div>

            <!-- Champ Contenu pré-rempli avec la valeur actuelle -->
            <div class="mb-3">
                <label for="contenu" class="form-label">Contenu</label>
                <textarea class="form-control"
                    id="contenu"
                    name="contenu"
                    rows="10"
                    required><?= htmlspecialchars($article['contenu']); ?></textarea>
            </div>

            <!-- Si l'article a un match associé, proposer de le modifier -->
            <?php if (!empty($article['match_id']) && $article['match_id'] > 0): ?>
                <div class="mb-3 form-check">
                    <input type="checkbox"
                        class="form-check-input"
                        id="modifierMatch"
                        name="modifierMatch">
                    <label class="form-check-label" for="modifierMatch">
                        Voulez-vous modifier aussi les résultats du match ?
                    </label>
                </div>

                <!-- Section des détails du match (cachée par défaut) -->
                <!-- Pré-remplie avec les valeurs actuelles du match -->
                <div id="detailsMatch" style="display: none;">

                    <h3 class="mt-4 mb-3">Détails du match</h3>

                    <!-- Équipe 1 -->
                    <div class="mb-3">
                        <label for="equipe1" class="form-label">Équipe 1</label>
                        <input type="text"
                            class="form-control"
                            id="equipe1"
                            name="equipe1"
                            value="<?= htmlspecialchars($article['equipe1'] ?? ''); ?>">
                    </div>

                    <!-- Équipe 2 -->
                    <div class="mb-3">
                        <label for="equipe2" class="form-label">Équipe 2</label>
                        <input type="text"
                            class="form-control"
                            id="equipe2"
                            name="equipe2"
                            value="<?= htmlspecialchars($article['equipe2'] ?? ''); ?>">
                    </div>

                    <!-- Score -->
                    <div class="mb-3">
                        <label for="score" class="form-label">Score</label>
                        <input type="text"
                            class="form-control"
                            id="score"
                            name="score"
                            value="<?= htmlspecialchars($article['score'] ?? ''); ?>">
                    </div>

                    <!-- Lieu -->
                    <div class="mb-3">
                        <label for="lieu" class="form-label">Lieu</label>
                        <input type="text"
                            class="form-control"
                            id="lieu"
                            name="lieu"
                            value="<?= htmlspecialchars($article['lieu'] ?? ''); ?>">
                    </div>

                    <!-- Résumé du match -->
                    <div class="mb-3">
                        <label for="resume" class="form-label">Commentaire sur le match</label>
                        <textarea class="form-control"
                            id="resume"
                            name="resume"
                            rows="4"><?= htmlspecialchars($article['resume'] ?? ''); ?></textarea>
                    </div>

                </div>
            <?php endif; ?>

            <!-- Boutons de soumission et retour -->
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a class="btn btn-secondary" role="button" href="<?= BASE_URL ?>/home.html">RETOUR</a>

        </form>

    </div>
</div>

<!-- Script JavaScript pour afficher/masquer les détails du match -->
<?php if (!empty($article['match_id']) && $article['match_id'] > 0): ?>
    <script>
        // Récupère la case à cocher
        const modifierMatchCheckbox = document.getElementById('modifierMatch');
        // Récupère la section des détails du match
        const detailsMatchSection = document.getElementById('detailsMatch');

        // Écoute le changement d'état de la case à cocher
        modifierMatchCheckbox.addEventListener('change', function() {
            // Si cochée : affiche la section, sinon : la cache
            detailsMatchSection.style.display = this.checked ? 'block' : 'none';

            // Rend les champs obligatoires ou facultatifs selon l'état
            const matchInputs = detailsMatchSection.querySelectorAll('input:not(#resume), textarea:not(#resume)');
            matchInputs.forEach(input => {
                input.required = this.checked;
            });
        });
    </script>
<?php endif; ?>
