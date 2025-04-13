<section id="login-container">
    <form action='login' method="post">
        <input type="text" name="email" placeholder="Identifiant"></input>
        <input type="password" name="password" placeholder="Mot de passe "></input>

        <input type="submit" class="button" value="Connexion" />
    </form>
    <?php
    if (isset($error_message)) {
    ?>
        <div>
            <p><?= $error_message ?></p>
        </div>
    <?php
    } ?>
</section>