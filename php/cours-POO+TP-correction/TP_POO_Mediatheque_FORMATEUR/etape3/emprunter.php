<?php
declare(strict_types=1);

/**
 * ===================================================================
 * CORRIGÉ FORMATEUR — ÉTAPE 3 — emprunter.php
 * ===================================================================
 * C'est le fichier le plus important du TP sur le plan sécurité.
 * Chaque TODO corrigé correspond à un critère de performance du REAC.
 */

require_once __DIR__ . '/src/DocumentRepository.php';

// =====================================================================
// TODO 1 corrigé — VALIDATION DE L'ENTRÉE
// =====================================================================
// filter_input() fait deux choses en une : il lit la superglobale ET
// valide le format. Retours possibles :
//   - l'entier          si la valeur est un entier valide
//   - false             si la valeur existe mais n'est pas un entier
//   - null              si la clé 'id' est absente de l'URL
// Le test `!$id` couvre false, null et 0 d'un coup ; on ajoute
// explicitement le cas négatif.

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    header('Location: index.php?erreur=id_invalide');
    exit;
}

// =====================================================================
// TODO 2 corrigé — LISTE BLANCHE SUR L'ACTION
// =====================================================================
// Ne jamais tester ce qui est interdit (liste noire) : tester ce qui
// est autorisé. Le troisième argument `true` de in_array() impose la
// comparaison stricte et évite les conversions de type surprenantes.

$action = $_GET['action'] ?? '';

if (!in_array($action, ['emprunter', 'rendre'], true)) {
    header('Location: index.php?erreur=action_invalide');
    exit;
}

// =====================================================================
// TODO 3 corrigé — VÉRIFICATION DE L'EXISTENCE EN BASE
// =====================================================================
// Un id peut être un entier parfaitement valide (42) et ne correspondre
// à aucune ligne. Validation de format ≠ validation métier.

$repository = new DocumentRepository();

try {
    $document = $repository->findById($id);
} catch (Throwable $e) {
    error_log('findById a échoué : ' . $e->getMessage());
    header('Location: index.php?erreur=technique');
    exit;
}

if ($document === null) {
    header('Location: index.php?erreur=document_introuvable');
    exit;
}

// =====================================================================
// TODO 4 corrigé — EXÉCUTION AVEC GESTION D'ERREUR
// =====================================================================
// Le message d'exception part dans les logs serveur, JAMAIS à l'écran :
// il contient noms de tables, de colonnes et parfois le chemin absolu
// du fichier — autant d'informations offertes à un attaquant.

$nouvelleDisponibilite = ($action === 'rendre');

try {
    $repository->setDisponibilite($id, $nouvelleDisponibilite);
} catch (PDOException $e) {
    error_log('setDisponibilite a échoué : ' . $e->getMessage());
    header('Location: index.php?erreur=technique');
    exit;
}

// =====================================================================
// TODO 5 corrigé — REDIRECTION (motif Post/Redirect/Get)
// =====================================================================
// Sans redirection, un F5 rejouerait l'action. Le `exit;` après
// header() n'est pas décoratif : sans lui, PHP continue d'exécuter
// le script et peut produire une sortie avant la redirection.

header('Location: index.php?succes=' . urlencode($action));
exit;
