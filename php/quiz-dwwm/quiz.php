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
$index     = (int) ($_SESSION['index'] ?? 0);

/* ---------------------------------------------------------------- */
/*  1) Traitement d'une réponse soumise (POST) → PRG                  */
/* ---------------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reponse'])) {
    // On n'enregistre qu'une fois par question (anti double-clic / refresh)
    if (!isset($_SESSION['reponses'][$index])) {
        $_SESSION['reponses'][$index] = (int) $_POST['reponse'];
    }
    header('Location: quiz.php'); // redirect → affiche le feedback en GET
    exit;
}

/* ---------------------------------------------------------------- */
/*  2) Passage à la question suivante                                 */
/* ---------------------------------------------------------------- */
if (isset($_GET['suivant'])) {
    $_SESSION['index'] = $index + 1;
    if ($_SESSION['index'] >= $total) {
        header('Location: resultat.php');
        exit;
    }
    header('Location: quiz.php');
    exit;
}

// Si toutes les questions sont traitées → résultat
if ($index >= $total) {
    header('Location: resultat.php');
    exit;
}

/* ---------------------------------------------------------------- */
/*  3) Détermination de la phase : question ou feedback               */
/* ---------------------------------------------------------------- */
$q          = $questions[$index];
$aRepondu   = isset($_SESSION['reponses'][$index]);
$choix      = $aRepondu ? (int) $_SESSION['reponses'][$index] : null;
$estCorrect = $aRepondu && $choix === (int) $q['correct'];
$numero     = $index + 1;
$progression = (int) round($index / $total * 100);

// Palette « Kahoot » : couleur + forme par option
$styles = [
    ['couleur' => 'rouge', 'forme' => '▲'],
    ['couleur' => 'bleu',  'forme' => '◆'],
    ['couleur' => 'jaune', 'forme' => '●'],
    ['couleur' => 'vert',  'forme' => '■'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz DWWM — Question <?= $numero ?>/<?= $total ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="page-quiz<?= $aRepondu ? ' phase-feedback' : ' phase-question' ?>">

    <header class="barre-haut">
        <div class="progression">
            <div class="progression-remplissage" style="width: <?= $aRepondu ? (int) round($numero / $total * 100) : $progression ?>%"></div>
        </div>
        <div class="meta-question">
            <span class="compteur">Question <?= $numero ?> / <?= $total ?></span>
            <span class="tag-cp"><?= e($q['cp']) ?> · <?= e($q['theme']) ?></span>
            <span class="joueur">👤 <?= e($_SESSION['prenom']) ?></span>
        </div>
    </header>

    <main class="scene">

        <section class="bloc-question">
            <h1 class="enonce"><?= e($q['question']) ?></h1>
            <?php if (!empty($q['code'])): ?>
                <pre class="bloc-code"><code><?= e($q['code']) ?></code></pre>
            <?php endif; ?>
        </section>

        <?php if (!$aRepondu): ?>
            <!-- ============ PHASE QUESTION ============ -->
            <form method="post" class="grille-reponses" id="grille-reponses">
                <?php foreach ($q['options'] as $i => $opt): ?>
                    <button
                        type="submit"
                        name="reponse"
                        value="<?= $i ?>"
                        class="tuile tuile-<?= $styles[$i]['couleur'] ?>">
                        <span class="forme"><?= $styles[$i]['forme'] ?></span>
                        <span class="libelle"><?= e($opt) ?></span>
                    </button>
                <?php endforeach; ?>
            </form>

        <?php else: ?>
            <!-- ============ PHASE FEEDBACK ============ -->
            <div class="verdict <?= $estCorrect ? 'verdict-ok' : 'verdict-ko' ?>">
                <?= $estCorrect ? '✔ Bonne réponse !' : '✘ Raté' ?>
            </div>

            <div class="grille-reponses figee">
                <?php foreach ($q['options'] as $i => $opt): ?>
                    <?php
                        $classes = 'tuile tuile-' . $styles[$i]['couleur'];
                        if ($i === (int) $q['correct']) {
                            $classes .= ' est-correcte';
                        } elseif ($i === $choix) {
                            $classes .= ' est-choisie-fausse';
                        } else {
                            $classes .= ' est-neutre';
                        }
                    ?>
                    <div class="<?= $classes ?>">
                        <span class="forme"><?= $styles[$i]['forme'] ?></span>
                        <span class="libelle"><?= e($opt) ?></span>
                        <?php if ($i === (int) $q['correct']): ?>
                            <span class="marqueur">✔</span>
                        <?php elseif ($i === $choix): ?>
                            <span class="marqueur">✘</span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="explication">
                <h2>Pourquoi&nbsp;?</h2>
                <p><?= e($q['explication']) ?></p>
            </div>

            <div class="zone-suivant">
                <a href="quiz.php?suivant=1" class="btn-principal">
                    <?= $numero < $total ? 'Question suivante →' : 'Voir mon résultat →' ?>
                </a>
            </div>
        <?php endif; ?>

    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="assets/quiz.js"></script>
</body>
</html>
