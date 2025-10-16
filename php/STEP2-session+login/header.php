<!--
    En-tête du site - header.php
    =============================

    Ce fichier contient le header (bandeau supérieur) du site qui apparaît sur toutes les pages.
    Il inclut : le logo, le nom de l'édition, et le menu de navigation principal.
-->

<div class="container d-flex flex-row justify-content-between align-items-center my-5">

    <!-- Zone logo et titre (colonne de gauche) -->
    <div class="col-4">
        <!-- Logo cliquable qui renvoie à l'accueil -->
        <a href="php102-index.php">
            <img src="../img/equipe.png" class="w-50" alt=""></a><br> "

        <!-- Affichage conditionnel du prénom de l'utilisateur connecté -->
        <!-- isset() vérifie si la variable de session existe -->
        <?php if (isset($_SESSION['LOGGED_USER'])) : echo $_SESSION['LOGGED_USER']['prenom'];
        endif; ?>

        <!-- Affichage de la date du jour au format JJ/MM/AAAA -->
        Edition" du <?php echo date("d/m/Y") ?></p>
    </div>

    <!-- Zone menu de navigation (colonne de droite) -->
    <div class="col-8 text-end d-flex gap-5">
        <div>
            <!-- Espace réservé pour des éléments futurs -->
        </div>
        <div>
            <!-- Menu de navigation horizontal -->
            <!-- list-inline : classe Bootstrap pour afficher les éléments de liste en ligne -->
            <ul class="list-inline">
                <li class="list-inline-item">
                    <a href="index.php">Accueil</a>
                </li>
                <li class="list-inline-item">
                    <a href="php102-resultats.php">Résultats des matchs</a>
                </li>
                <li class="list-inline-item">
                    <a href="#">menu 3</a>
                </li>
                <li class="list-inline-item">
                    <a href="#">menu 4</a>
                </li>
                <li class="list-inline-item">
                    <a href="php102-contact.php">contact</a>
                </li>
            </ul>
        </div>

    </div>
</div>