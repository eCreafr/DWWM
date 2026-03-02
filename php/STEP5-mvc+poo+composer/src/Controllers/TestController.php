<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Helpers\UrlHelper;

/**
 * Classe TestController - Contrôleur pour exécuter et afficher les tests PHPUnit
 *
 * Ce contrôleur permet d'exécuter les tests PHPUnit depuis une interface web
 * et d'afficher les résultats de manière formatée et lisible.
 */
class TestController
{
    /**
     * Affiche la page des tests avec les résultats
     */
    public function index(): void
    {
        // Sécurité : Réservé aux administrateurs uniquement
        // En environnement de production, il faudrait désactiver complètement cette page
        if (!AuthHelper::isAdmin()) {
            $_SESSION['error_message'] = 'Accès réservé aux administrateurs.';
            UrlHelper::redirect('home.html');
            return;
        }

        // Prépare les métadonnées de la page
        $title = "Tests PHPUnit - Suite de tests";
        $metadesc = "Exécution des tests PHPUnit du projet";

        // Chemin vers le répertoire racine du projet
        $projectRoot = dirname(__DIR__, 2);

        // Exécute PHPUnit et capture la sortie
        $output = $this->runTests($projectRoot);

        // Parse les résultats pour un affichage formaté
        $results = $this->parseTestResults($output);

        // Affiche la vue
        $this->render('tests/index', compact('title', 'metadesc', 'output', 'results'));
    }

    /**
     * Exécute les tests PHPUnit et retourne la sortie
     *
     * @param string $projectRoot Chemin vers la racine du projet
     * @return string La sortie complète de PHPUnit
     */
    private function runTests(string $projectRoot): string
    {
        // Chemin vers l'exécutable PHPUnit
        $phpunitPath = $projectRoot . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'phpunit';

        // Sous Windows, ajoute .bat
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $phpunitPath .= '.bat';
        }

        // Vérifie que PHPUnit existe
        if (!file_exists($phpunitPath)) {
            return "Erreur : PHPUnit n'est pas installé. Exécutez 'composer install' pour l'installer.";
        }

        // Commande à exécuter
        // --testdox : Affichage lisible des noms de tests
        // --colors=always : Préserve les couleurs ANSI
        $command = escapeshellarg($phpunitPath) . ' --testdox --colors=always 2>&1';

        // Change de répertoire vers la racine du projet
        $oldDir = getcwd();
        chdir($projectRoot);

        // Exécute la commande et capture la sortie
        ob_start();
        passthru($command, $returnCode);
        $output = ob_get_clean();

        // Retourne au répertoire original
        chdir($oldDir);

        return $output ?: "Aucune sortie de PHPUnit.";
    }

    /**
     * Parse les résultats de PHPUnit pour extraire des statistiques
     *
     * @param string $output La sortie brute de PHPUnit
     * @return array Tableau contenant les statistiques extraites
     */
    private function parseTestResults(string $output): array
    {
        $results = [
            'total_tests' => 0,
            'assertions' => 0,
            'failures' => 0,
            'errors' => 0,
            'warnings' => 0,
            'skipped' => 0,
            'time' => '0s',
            'status' => 'unknown',
            'success_rate' => 0,
        ];

        // Supprime les codes couleur ANSI pour faciliter le parsing
        $cleanOutput = preg_replace('/\x1b\[[0-9;]*m/', '', $output);

        // Extraction des statistiques depuis la ligne de résumé
        // PHPUnit 12 format: "OK (92 tests, 147 assertions)" ou "FAILURES! Tests: 92, Assertions: 147, Failures: 3."

        // Cas 1: Format succès "OK (X tests, Y assertions)"
        if (preg_match('/OK\s+\((\d+)\s+tests?,\s+(\d+)\s+assertions?\)/', $cleanOutput, $matches)) {
            $results['total_tests'] = (int) $matches[1];
            $results['assertions'] = (int) $matches[2];
            $results['status'] = 'success';
        }

        // Cas 2: Format échec "FAILURES! Tests: X, Assertions: Y, Failures: Z"
        if (preg_match('/Tests:\s+(\d+)/', $cleanOutput, $matches)) {
            $results['total_tests'] = (int) $matches[1];
        }

        if (preg_match('/Assertions:\s+(\d+)/', $cleanOutput, $matches)) {
            $results['assertions'] = (int) $matches[1];
        }

        if (preg_match('/Failures:\s+(\d+)/', $cleanOutput, $matches)) {
            $results['failures'] = (int) $matches[1];
        }

        if (preg_match('/Errors:\s+(\d+)/', $cleanOutput, $matches)) {
            $results['errors'] = (int) $matches[1];
        }

        if (preg_match('/Warnings:\s+(\d+)/', $cleanOutput, $matches)) {
            $results['warnings'] = (int) $matches[1];
        }

        if (preg_match('/Skipped:\s+(\d+)/', $cleanOutput, $matches)) {
            $results['skipped'] = (int) $matches[1];
        }

        // Extraction du temps d'exécution
        // Exemple : "Time: 00:01.234"
        if (preg_match('/Time:\s+([\d:.]+)/', $cleanOutput, $matches)) {
            $results['time'] = $matches[1];
        }

        // Détermine le statut global
        if ($results['failures'] > 0 || $results['errors'] > 0) {
            $results['status'] = 'failure';
        } elseif (strpos($cleanOutput, 'OK') !== false) {
            $results['status'] = 'success';
        } elseif ($results['warnings'] > 0) {
            $results['status'] = 'warning';
        }

        // Calcul du taux de réussite
        if ($results['total_tests'] > 0) {
            $passed = $results['total_tests'] - $results['failures'] - $results['errors'] - $results['skipped'];
            $results['success_rate'] = round(($passed / $results['total_tests']) * 100, 2);
        }

        return $results;
    }

    /**
     * Méthode utilitaire pour rendre une vue
     *
     * @param string $view Le chemin de la vue (ex: 'tests/index')
     * @param array $data Les données à passer à la vue
     */
    private function render(string $view, array $data = []): void
    {
        extract($data);

        ob_start();
        require_once __DIR__ . "/../Views/{$view}.php";
        $content = ob_get_clean();

        require_once __DIR__ . '/../Views/layouts/default.php';
    }
}
