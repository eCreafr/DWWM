<?=

// Initialisation du logger
$logger = new JsonLogger();

// Exemple de logging pour une connexion
$logger->log('login', [
    'username' => $username,
    'success' => true
]);

// Exemple de logging pour une recherche
$logger->log('search', [
    'keywords' => $searchTerms,
    'results_count' => count($results)
]);

// Recherche dans les logs
$failedLogins = $logger->search([
    'action' => 'login',
    'data.success' => false
]);

// Obtenir des statistiques
$stats = $logger->getStats();
