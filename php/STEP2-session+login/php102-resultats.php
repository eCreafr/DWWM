<!--
    Page des résultats sportifs - php102-resultats.php
    ===================================================

    Cette page affiche les résultats des matchs de football.
    L'accès est RÉSERVÉ aux utilisateurs connectés (abonnés).

    📚 Concepts abordés :
    - Restriction d'accès basée sur les sessions
    - Boucle foreach pour afficher des données dynamiques
    - Affichage conditionnel avec if/else
    - Récupération de données depuis la base de données (via functions.php)
    - Structure de cards Bootstrap responsive
-->

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php
    // Inclusion des éléments communs (session, DB, CSS)
    require_once(__DIR__ . '/head.php');
    ?>
    <title>structure site PHP par section / bloc</title>
</head>

<body>
    <div style="background: #F8F9F9;" class="container-fluid d-flex flex-column justify-content-center ">

        <!-- En-tête du site -->
        <?php require_once(__DIR__ . '/header.php'); ?>

        <!-- === CORPS DE LA PAGE === -->

        <div class="container text-center">
            <!--
                Titre personnalisé avec le nom de l'utilisateur
                Si l'utilisateur est connecté, on affiche son nom complet
            -->
            <h1>Résultats des matchs de football pour toi <?php if (isset($_SESSION['LOGGED_USER'])) : echo $_SESSION['LOGGED_USER']['prenom'] . ' ' . $_SESSION['LOGGED_USER']['nom'];
                                                            endif; ?>
            </h1>
        </div>

        <!--
            === CONTENU RÉSERVÉ AUX ABONNÉS ===

            Tout le contenu de cette page est protégé par une condition.
            Seuls les utilisateurs connectés peuvent voir les résultats.
        -->
        <?php if (isset($_SESSION['LOGGED_USER'])) :  ?>

            <!-- Zone d'affichage des matchs -->
            <div class="container d-flex flex-wrap gap-5 justify-content-center">

                <!--
                    Boucle foreach pour afficher tous les matchs
                    =============================================

                    $Matches vient de functions.php qui récupère tous les matchs depuis la DB.
                    Pour chaque match, on crée une card avec les informations.
                -->
                <?php foreach ($Matches as $match) {  ?>

                    <!-- Card représentant un match -->
                    <article class="card my-3 p-5 bg-secondary bg-opacity-25 w-25">

                        <!-- Affichage des équipes -->
                        <h3>
                            <!--
                                echo $match['equipe1'] affiche le nom de l'équipe 1
                                Les données viennent directement de la base de données
                            -->
                            <span>
                                <?php echo $match['equipe1']; ?>
                            </span>
                            vs
                            <span>
                                <?php echo $match['equipe2']; ?>
                            </span>
                        </h3>

                        <!-- Affichage du score -->
                        <p><strong>Score : </strong><?php echo $match['score']; ?></p>

                        <!-- Affichage du résumé du match -->
                        <div><?php echo $match['resume']; ?></div>
                    </article>

                <?php } ?>

            <?php else :  ?>
                <!--
                    === ACCÈS REFUSÉ ===

                    Si l'utilisateur n'est PAS connecté, on affiche un message
                    l'invitant à s'abonner pour accéder au contenu.
                -->
                <div class="container d-flex flex-wrap gap-5 justify-content-center">
                    <div> <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <h2>Sorry</h2>les résultats sont reservés aux abonnés<br><br><br><br><br><br>
                    </div>
                </div>
            <?php endif;  ?>
            </div>

            <!-- Pied de page -->
            <?php require_once(__DIR__ . '/footer.php'); ?>
    </div>

</body>

</html>
