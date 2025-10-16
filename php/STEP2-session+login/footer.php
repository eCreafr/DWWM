<!--
    Pied de page - footer.php
    =========================

    Ce fichier contient le footer (bas de page) du site qui apparaît sur toutes les pages.
    Il inclut : le logo, le lien de déconnexion (si connecté), et les informations du site.
-->

<div class="container  my-3 p-5">
    <!-- Logo positionné à droite grâce à float-end -->
    <img src="../img/equipe.png" alt="" class="w-25 float-end">
    <p>

        <!--
            Affichage conditionnel du lien de déconnexion
            =============================================

            Le lien n'apparaît QUE si l'utilisateur est connecté.
            Le paramètre ?action=logout dans l'URL permet à functions.php
            de détecter qu'il faut déconnecter l'utilisateur.
        -->
        <?php if (isset($_SESSION['LOGGED_USER'])) : ?>

            <a href="?action=logout">Déconnexion</a>

        <?php endif; ?>


        mon bas de page 2024® | liens vers reseaux sociaux 1 | reseau 2
    </p>
    <p>
        Abonnez-vous !</p>

</div>