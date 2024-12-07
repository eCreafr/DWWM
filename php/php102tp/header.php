<div class="container d-flex flex-row justify-content-between align-items-center my-5">

    <div class="col-4">
        <a href="php102-index.php">
            <img src="../img/equipe.png" class="w-50" alt=""></a><br> "
        <?php if (isset($_SESSION['LOGGED_USER'])) : echo $_SESSION['LOGGED_USER']['nom'];
        endif; ?>
        Edition" du <?php echo date("d/m/Y") ?></p>
    </div>

    <div class="col-8 text-end d-flex gap-5">
        <div>

        </div>
        <div>
            <ul class="list-inline">
                <li class="list-inline-item">
                    <a href="php102-index.php">Accueil</a>
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