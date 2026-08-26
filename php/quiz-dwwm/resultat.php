<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/includes/functions.php';

// Garde : sans prénom, retour accueil
if (empty($_SESSION['prenom'])) {
    header('Location: index.php');
    exit;
}

$questions = charger_questions();
$total     = count($questions);
$reponses  = $_SESSION['reponses'] ?? [];

// Garde : quiz réellement terminé
if (count($reponses) < $total) {
    header('Location: quiz.php');
    exit;
}

$prenom = (string) $_SESSION['prenom'];
$score  = calculer_score($questions, $reponses);

/* ---------------------------------------------------------------- */
/*  Enregistrement + mail : une seule fois par session               */
/* ---------------------------------------------------------------- */
if (empty($_SESSION['finalise'])) {
    enregistrer_resultat($prenom, $score, $total); // source de vérité (JSON)
    envoyer_mail_resultat($prenom, $score, $total); // doublon pratique (mail)
    $_SESSION['finalise'] = true;
}

// Message et couleur selon le score
$pourcent = (int) round($score / $total * 100);
if ($pourcent >= 80) {
    $niveau = 'excellent';  $message = 'Excellent — socle back-end solide.';
} elseif ($pourcent >= 60) {
    $niveau = 'bien';       $message = 'Bien — quelques points à consolider.';
} elseif ($pourcent >= 40) {
    $niveau = 'moyen';      $message = 'À revoir — reprenez les notions manquées.';
} else {
    $niveau = 'faible';     $message = 'Il faut retravailler le back-end en priorité.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz DWWM — Résultat</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="page-resultat niveau-<?= $niveau ?>">

    <main class="carte-resultat">
        <p class="felicitation">Terminé, <?= e($prenom) ?> 👋</p>

        <div class="score-cercle" data-score="<?= $score ?>" data-total="<?= $total ?>">
            <span class="score-valeur"><?= $score ?></span>
            <span class="score-total">/ <?= $total ?></span>
        </div>

        <p class="score-message"><?= e($message) ?></p>

        <div class="barre-resultat">
            <div class="barre-resultat-remplissage" style="width: <?= $pourcent ?>%"></div>
        </div>
        <p class="pourcent"><?= $pourcent ?> % de bonnes réponses</p>

        <p class="mention">Votre résultat a été transmis au formateur pour le suivi de participation.</p>

        <a href="reset.php" class="btn-principal btn-clair">Nouveau stagiaire →</a>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="assets/quiz.js"></script>
</body>
</html>
