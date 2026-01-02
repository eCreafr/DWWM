<!-- Vue du formulaire d'ajout d'article -->
<!-- Cette vue affiche le formulaire pour créer un nouvel article -->

<div class="d-flex flex-column min-vh-100">
    <div class="container">

        <h1>Ajouter un article</h1>

        <!-- Affichage des messages d'erreur -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger my-4">
                <?= htmlspecialchars($_SESSION['error_message']); ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Formulaire d'ajout (POST vers addpost.html) -->
        <form action="<?= BASE_URL ?>/addpost.html" method="POST" enctype="multipart/form-data">

            <!-- Champ Auteur -->
            <div class="mb-3">
                <label for="auteur" class="form-label">Auteur de l'article</label>
                <input type="text"
                    class="form-control"
                    id="auteur"
                    name="auteur"
                    required>
            </div>

            <!-- Champ Titre -->
            <div class="mb-3">
                <label for="titre" class="form-label">Titre de l'article</label>
                <input type="text"
                    class="form-control"
                    id="titre"
                    name="titre"
                    aria-describedby="titre-help"
                    required>
                <div id="titre-help" class="form-text">
                    Choisissez un titre percutant !
                </div>
            </div>

            <!-- Champ Contenu -->
            <div class="mb-3">
                <label for="contenu" class="form-label">Contenu de l'article</label>
                <textarea class="form-control"
                    placeholder="Seulement du contenu vous appartenant ou libre de droits."
                    id="contenu"
                    name="contenu"
                    rows="10"
                    required></textarea>
            </div>

            <!-- Champ Image -->
            <div class="mb-3">
                <label for="image" class="form-label">Image de l'article (JPG ou WEBP uniquement)</label>
                <input type="file"
                    class="form-control"
                    id="image"
                    name="image"
                    accept=".jpg,.jpeg,.webp">
                <div class="form-text">
                    Taille maximale : 5 Mo. Formats acceptés : JPG, WEBP
                </div>
            </div>

            <!-- Case à cocher pour ajouter un match -->
            <div class="mb-3 form-check">
                <input type="checkbox"
                    class="form-check-input"
                    id="ajouterMatch"
                    name="ajouterMatch">
                <label class="form-check-label" for="ajouterMatch">
                    Voulez-vous saisir les résultats d'un match ?
                </label>
            </div>

            <!-- Section des détails du match (cachée par défaut) -->
            <!-- Affichée uniquement si la case "ajouterMatch" est cochée -->
            <div id="detailsMatch" style="display: none;">

                <h3 class="mt-4 mb-3">Détails du match</h3>

                <!-- Équipe 1 -->
                <div class="mb-3">
                    <label for="equipe1" class="form-label">Équipe 1</label>
                    <input type="text"
                        class="form-control"
                        id="equipe1"
                        name="equipe1">
                </div>

                <!-- Équipe 2 -->
                <div class="mb-3">
                    <label for="equipe2" class="form-label">Équipe 2</label>
                    <input type="text"
                        class="form-control"
                        id="equipe2"
                        name="equipe2">
                </div>

                <!-- Score du match -->
                <div class="mb-3">
                    <label for="score" class="form-label">Score</label>
                    <input type="text"
                        class="form-control"
                        id="score"
                        name="score"
                        placeholder="Ex: 3-2">
                </div>

                <!-- Lieu du match -->
                <div class="mb-3">
                    <label for="lieu" class="form-label">Lieu</label>
                    <input type="text"
                        class="form-control"
                        id="lieu"
                        name="lieu"
                        placeholder="Ex: Stade de France">
                </div>

                <!-- Résumé/Commentaire du match (facultatif) -->
                <div class="mb-3">
                    <label for="resume" class="form-label">Commentaire sur le match (facultatif)</label>
                    <textarea class="form-control"
                        id="resume"
                        name="resume"
                        rows="4"></textarea>
                </div>
            </div>

            <!-- Boutons de soumission et retour -->
            <button type="submit" class="btn btn-primary">Envoyer</button>
            <a class="btn btn-secondary" role="button" href="<?= BASE_URL ?>/home.html">RETOUR</a>

        </form>

    </div>
</div>

<!-- Script JavaScript pour afficher/masquer les détails du match -->
<script>
    // Récupère la case à cocher
    const ajouterMatchCheckbox = document.getElementById('ajouterMatch');
    // Récupère la section des détails du match
    const detailsMatchSection = document.getElementById('detailsMatch');

    // Écoute le changement d'état de la case à cocher
    ajouterMatchCheckbox.addEventListener('change', function() {
        // Si cochée : affiche la section, sinon : la cache
        detailsMatchSection.style.display = this.checked ? 'block' : 'none';

        // Rend les champs obligatoires ou facultatifs selon l'état
        const matchInputs = detailsMatchSection.querySelectorAll('input:not(#resume), textarea:not(#resume)');
        matchInputs.forEach(input => {
            input.required = this.checked;
        });
    });
</script>
