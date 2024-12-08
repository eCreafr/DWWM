<div class="card">
    <img src="assets/img/bart.webp" class="rounded" alt="" width="100%">

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["sentence"]) && $_POST["sentence"] !== '') {


        $lines = 1;

        while ($lines <= $_POST["count"]) {
            echo '<span class="simpsons"> ' . $lines . ' ' . htmlspecialchars($_POST["sentence"]) . '</span><br>';
            $lines++;
        }
    } else {
        echo '<strong>Veuillez fournir une phrase a recopier pour punir Bart ! </strong> <br>
        <a href="home.html">revenir à l\'accueil</a>';
    }
    ?>
</div>