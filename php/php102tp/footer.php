<div class="container  my-3 p-5">
    <img src="../img/equipe.png" alt="" class="w-25 float-end">
    <p>



        <?php if (isset($_SESSION['LOGGED_USER'])) : ?>

            <a href="?action=logout">Déconnexion</a>

        <?php endif; ?>






        mon bas de page 2024® | liens vers reseaux sociaux 1 | reseau 2
    </p>
    <p><?php
        echo htmlspecialchars($_GET["firstname"] ?? $_POST["firstname"] ?? '');
        ?>
        Abonnez-vous !</p>

</div>