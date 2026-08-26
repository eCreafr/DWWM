<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/includes/functions.php';

// Démarrage : on (ré)initialise la session quand le prénom est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = trim($_POST['prenom'] ?? '');

    if ($prenom !== '') {
        // Nettoyage : lettres, espaces, tirets, apostrophes ; 30 car. max
        $prenom = mb_substr($prenom, 0, 30);

        $_SESSION = []; // reset complet pour un nouveau stagiaire
        $_SESSION['prenom']   = $prenom;
        $_SESSION['index']    = 0;
        $_SESSION['reponses'] = [];

        // Cookie « souvenir » du prénom (confort, 30 jours) — non sensible
        setcookie('quiz_prenom', $prenom, time() + 30 * 86400, '/');

        header('Location: quiz.php');
        exit;
    }
    $erreur = 'Merci de saisir un prénom.';
}

$prenomMemorise = $_COOKIE['quiz_prenom'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz DWWM — Révisions back-end</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="page-accueil">

    <main class="carte-accueil">
        <div class="badge-cp">PHP · SQL · POO · MVC</div>
        <h1 class="titre-accueil">Quiz DWWM</h1>
        <p class="sous-titre">Révision &amp; évaluation formative — back-end</p>

        <ul class="infos-quiz">
            <li><span>20</span> questions</li>
            <li><span>1</span> bonne réponse par question</li>
            <li><span>∞</span> temps libre, correction expliquée</li>
        </ul>

        <?php if (!empty($erreur)): ?>
            <p class="alerte"><?= e($erreur) ?></p>
        <?php endif; ?>

        <form method="post" class="form-prenom" autocomplete="off">
            <label for="prenom">Entrez votre prénom pour commencer</label>
            <input
                type="text"
                id="prenom"
                name="prenom"
                value="<?= e($prenomMemorise) ?>"
                placeholder="Votre prénom"
                maxlength="30"
                required
                autofocus>
            <button type="submit" class="btn-principal">Commencer&nbsp;→</button>
        </form>

        <p class="mention">Évaluation formative — le score n'a aucune incidence sur votre parcours. Il sert uniquement au formateur pour le suivi.</p>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="assets/quiz.js"></script>
</body>
</html>
