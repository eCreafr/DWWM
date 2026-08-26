<?php
/**
 * Quiz DWWM — fonctions utilitaires
 * Auteur : Raphaël Lang (eCrea) — évaluation formative back-end
 */

declare(strict_types=1);

/* ------------------------------------------------------------------ */
/*  Configuration (à adapter au moment du déploiement OVH)             */
/* ------------------------------------------------------------------ */

const QUIZ_MAIL_TO      = 'raphael@ecrea.fr';
// IMPORTANT OVH : le From doit être une adresse de VOTRE domaine (ecrea.fr)
// sinon SPF/DKIM échouent et le mail part en spam ou est rejeté.
const QUIZ_MAIL_FROM    = 'quiz@ecrea.fr';
const QUIZ_MAIL_SUBJECT = '[Quiz DWWM] Résultat d\'un stagiaire';

/* ------------------------------------------------------------------ */
/*  Chargement des questions                                           */
/* ------------------------------------------------------------------ */

function charger_questions(): array
{
    $chemin = __DIR__ . '/../data/questions.json';
    $json   = file_get_contents($chemin);

    if ($json === false) {
        throw new RuntimeException('Impossible de lire data/questions.json');
    }

    $questions = json_decode($json, true);

    if (!is_array($questions)) {
        throw new RuntimeException('questions.json invalide : ' . json_last_error_msg());
    }

    return $questions;
}

/* ------------------------------------------------------------------ */
/*  Calcul du score à partir des réponses stockées en session          */
/* ------------------------------------------------------------------ */

function calculer_score(array $questions, array $reponses): int
{
    $score = 0;
    foreach ($questions as $i => $q) {
        if (isset($reponses[$i]) && (int) $reponses[$i] === (int) $q['correct']) {
            $score++;
        }
    }
    return $score;
}

/* ------------------------------------------------------------------ */
/*  Enregistrement du résultat dans data/resultats.json (append sûr)   */
/*  -> source de vérité fiable pour le suivi de participation.         */
/* ------------------------------------------------------------------ */

function enregistrer_resultat(string $prenom, int $score, int $total): bool
{
    $fichier = __DIR__ . '/../data/resultats.json';

    $ligne = [
        'prenom' => $prenom,
        'score'  => $score,
        'total'  => $total,
        'date'   => date('Y-m-d H:i:s'),
        'ip'     => $_SERVER['REMOTE_ADDR'] ?? '',
    ];

    // Ouverture en lecture/écriture, création si absent, verrou exclusif
    $fp = fopen($fichier, 'c+');
    if ($fp === false) {
        return false;
    }

    if (!flock($fp, LOCK_EX)) {
        fclose($fp);
        return false;
    }

    $contenu = stream_get_contents($fp);
    $data    = $contenu !== '' ? json_decode($contenu, true) : [];
    if (!is_array($data)) {
        $data = [];
    }

    $data[] = $ligne;

    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);

    return true;
}

/* ------------------------------------------------------------------ */
/*  Envoi du mail de résultat                                          */
/* ------------------------------------------------------------------ */

function envoyer_mail_resultat(string $prenom, int $score, int $total): bool
{
    $prenomSafe = htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8');

    $corps  = "Un stagiaire vient de terminer le quiz DWWM.\n\n";
    $corps .= "Prénom  : {$prenom}\n";
    $corps .= "Score   : {$score} / {$total}\n";
    $corps .= "Date    : " . date('d/m/Y à H:i') . "\n";

    $headers  = 'From: ' . QUIZ_MAIL_FROM . "\r\n";
    $headers .= 'Reply-To: ' . QUIZ_MAIL_FROM . "\r\n";
    $headers .= 'Content-Type: text/plain; charset=UTF-8' . "\r\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();

    // -f force l'enveloppe expéditeur (améliore la délivrabilité sur OVH)
    return @mail(
        QUIZ_MAIL_TO,
        '=?UTF-8?B?' . base64_encode(QUIZ_MAIL_SUBJECT . " ({$prenomSafe})") . '?=',
        $corps,
        $headers,
        '-f' . QUIZ_MAIL_FROM
    );
}

/* ------------------------------------------------------------------ */
/*  Petit helper d'échappement pour les vues                           */
/* ------------------------------------------------------------------ */

function e(string $valeur): string
{
    return htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8');
}
