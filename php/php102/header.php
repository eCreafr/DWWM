<div class="container d-flex flex-row justify-content-between align-items-center my-5">

    <div class="col-4">
    <a href="php102-index.php?firstname=<?php echo htmlspecialchars($_GET["firstname"] ?? $_POST["firstname"] ?? '');?>">
        <img src="img/equipe.png" class="w-50" alt=""></a><br> "
        <?php echo htmlspecialchars($_GET["firstname"] ?? $_POST["firstname"] ?? ''); ?>
        Edition" du <?php echo date("d/m/Y") ?></p>
    </div>

    <div class="col-8 text-end d-flex gap-5">
        <div>     
        
        <?php

/*  
// version fonctionnelle mais un peu longue
       if (isset($_GET["firstname"]) && $_GET["firstname"] !== '') {  } 
        elseif (isset($_POST["firstname"]) && $_POST["firstname"] !== '') {   } 
        else {
                    echo '<form action="php102-index.php" method="POST">
                    <label for="firstname" class="form-label">Votre prénom svp :</label>
                    <input  type="text" id="firstname" name="firstname" required> 
                    <button type="submit" class="btn  btn-primary">go</button>
                </form>';
                } */
        ?>

    <?php
    // version plus compacte
        if (!empty($_GET["firstname"])) {
        } elseif (!empty($_POST["firstname"])) {
        } else {
            echo '
                <form action="php102-index.php" method="POST">
                    <label for="firstname" class="form-label">Votre prénom svp :</label>
                    <input type="text" id="firstname" name="firstname" required>
                    <button type="submit" class="btn btn-primary">go</button>
                </form>';
        }
    ?>





        </div>
        <div>
        <ul class="list-inline">
            <li class="list-inline-item">
            <a href="php102-index.php?firstname=<?php echo htmlspecialchars($_GET["firstname"] ?? $_POST["firstname"] ?? ''); ?>
">Accueil</a></li>
            <li class="list-inline-item">
            <a href="php102-resultats.php?firstname=<?php echo htmlspecialchars($_GET["firstname"] ?? $_POST["firstname"] ?? ''); ?>
">Résultats des matchs</a></li>
            <li class="list-inline-item">
            <a href="#?firstname=<?php echo htmlspecialchars($_GET["firstname"] ?? $_POST["firstname"] ?? ''); ?>
">menu 3</a></li>
            <li class="list-inline-item">
            <a href="#?firstname=<?php echo htmlspecialchars($_GET["firstname"] ?? $_POST["firstname"] ?? ''); ?>
">menu 4</a></li>
            <li class="list-inline-item">
            <a href="php102-contact.php?firstname=<?php echo htmlspecialchars($_GET["firstname"] ?? $_POST["firstname"] ?? ''); ?>
">contact</a></li>
        </ul></div>

    </div>
</div>