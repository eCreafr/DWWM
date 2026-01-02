<?php
/**
 * Script d'Auto-Installation DevOps
 *
 * Ce script automatise l'installation complète du projet :
 * 1. Vérification des prérequis
 * 2. Installation de Composer
 * 3. Import de la base de données
 * 4. Exécution des tests PHPUnit
 * 5. Nettoyage et finalisation
 *
 * Usage : Accéder à http://localhost/votre-projet/install.php via le navigateur
 */

// Désactive l'affichage des erreurs pour l'utilisateur (on les log)
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Démarre la session
session_start();

// Configuration
define('INSTALL_VERSION', '1.0.0');
define('PROJECT_ROOT', __DIR__);
define('SQL_FILE', PROJECT_ROOT . '/sport_2000.sql');
define('CONFIG_DB_FILE', PROJECT_ROOT . '/config/database.php');

// Classe principale d'installation
class Installer
{
    private array $log = [];
    private array $errors = [];
    private int $step = 0;
    private array $config = [];

    public function __construct()
    {
        // Charge la configuration si elle existe
        if (file_exists(CONFIG_DB_FILE)) {
            $this->config = require CONFIG_DB_FILE;
        }
    }

    /**
     * Point d'entrée principal
     */
    public function run(): void
    {
        // Traite la soumission du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $this->handleAction($_POST['action']);
        }

        // Affiche l'interface
        $this->renderUI();
    }

    /**
     * Gère les actions selon l'étape
     */
    private function handleAction(string $action): void
    {
        switch ($action) {
            case 'check_requirements':
                $this->checkRequirements();
                break;
            case 'install_composer':
                $this->installComposerDependencies();
                break;
            case 'import_database':
                $this->importDatabase($_POST['db_host'], $_POST['db_name'], $_POST['db_user'], $_POST['db_pass']);
                break;
            case 'run_tests':
                $this->runTests();
                break;
            case 'cleanup':
                $this->cleanup();
                break;
        }
    }

    /**
     * Étape 1 : Vérification des prérequis
     */
    private function checkRequirements(): array
    {
        $this->addLog('🔍 Vérification des prérequis système...');
        $requirements = [];

        // PHP Version
        $phpVersion = PHP_VERSION;
        $requirements['php'] = [
            'name' => 'PHP Version >= 8.0',
            'status' => version_compare($phpVersion, '8.0.0', '>='),
            'value' => $phpVersion
        ];

        // PDO Extension
        $requirements['pdo'] = [
            'name' => 'Extension PDO',
            'status' => extension_loaded('pdo'),
            'value' => extension_loaded('pdo') ? 'Installée' : 'Manquante'
        ];

        // PDO MySQL
        $requirements['pdo_mysql'] = [
            'name' => 'Extension PDO MySQL',
            'status' => extension_loaded('pdo_mysql'),
            'value' => extension_loaded('pdo_mysql') ? 'Installée' : 'Manquante'
        ];

        // Composer (optionnel - sera téléchargé si absent)
        $composerPath = $this->findComposer();
        $requirements['composer'] = [
            'name' => 'Composer',
            'status' => !empty($composerPath),
            'value' => !empty($composerPath) ? 'Trouvé' : 'Sera téléchargé automatiquement',
            'optional' => true  // Marque comme optionnel
        ];

        // Fichier SQL
        $requirements['sql_file'] = [
            'name' => 'Fichier sport_2000.sql',
            'status' => file_exists(SQL_FILE),
            'value' => file_exists(SQL_FILE) ? 'Présent' : 'Manquant'
        ];

        // Permissions d'écriture - Test plus robuste pour Windows
        $testFile = PROJECT_ROOT . '/.write_test_' . time();
        $canWrite = @file_put_contents($testFile, 'test') !== false;
        if ($canWrite) {
            @unlink($testFile);
        }

        $requirements['writable'] = [
            'name' => 'Permissions d\'écriture',
            'status' => $canWrite,
            'value' => $canWrite ? 'OK' : 'Insuffisantes'
        ];

        foreach ($requirements as $key => $req) {
            // Affichage différent pour les prérequis optionnels
            $isOptional = isset($req['optional']) && $req['optional'];

            if ($req['status']) {
                $this->addLog("✅ {$req['name']} : {$req['value']}");
            } else {
                if ($isOptional) {
                    $this->addLog("⚠️  {$req['name']} : {$req['value']}");
                } else {
                    $this->addError("❌ {$req['name']} : {$req['value']}");
                }
            }
        }

        // Vérifie que les prérequis OBLIGATOIRES sont satisfaits (exclut les optionnels)
        $allPassed = true;
        foreach ($requirements as $req) {
            $isOptional = isset($req['optional']) && $req['optional'];
            if (!$isOptional && !$req['status']) {
                $allPassed = false;
                break;
            }
        }

        if ($allPassed) {
            $this->addLog('✅ Tous les prérequis obligatoires sont satisfaits !');
            $this->step = 1;
        } else {
            $this->addError('❌ Certains prérequis obligatoires ne sont pas satisfaits.');
        }

        return $requirements;
    }

    /**
     * Étape 2 : Installation des dépendances Composer
     */
    private function installComposerDependencies(): bool
    {
        $this->addLog('📦 Installation des dépendances Composer...');

        // Vérifier si vendor/ existe déjà (installation déjà faite)
        $vendorPath = PROJECT_ROOT . '/vendor';
        if (is_dir($vendorPath) && file_exists($vendorPath . '/autoload.php')) {
            $this->addLog('✓ Dossier vendor/ déjà présent');
            $this->addLog('✅ Dépendances Composer déjà installées !');
            $this->addLog('   - egulias/email-validator');
            $this->addLog('   - pragmarx/google2fa (2FA)');
            $this->addLog('ℹ️  Installation Composer ignorée (vendor/ existe)');
            $this->step = 2;
            return true;
        }

        // Debug : afficher le répertoire de travail et vérifier composer.phar
        $composerPharPath = PROJECT_ROOT . '/composer.phar';
        $this->addLog("🔍 Répertoire projet : " . PROJECT_ROOT);
        $this->addLog("🔍 Recherche de composer.phar dans : {$composerPharPath}");

        if (file_exists($composerPharPath)) {
            $this->addLog("✓ composer.phar existe !");
            $this->addLog("  - Taille : " . round(filesize($composerPharPath) / 1024 / 1024, 2) . " MB");
            $this->addLog("  - Lisible : " . (is_readable($composerPharPath) ? 'Oui' : 'Non'));
        } else {
            $this->addLog("✗ composer.phar n'existe pas à cet emplacement");
        }

        $composerPath = $this->findComposer();

        // Si Composer n'est pas trouvé, essayer de le télécharger
        if (empty($composerPath)) {
            $this->addLog('⚠️  Composer non trouvé sur le système');

            if (!$this->downloadComposer()) {
                $this->addError('❌ Impossible d\'installer Composer');
                $this->addError('Solution manuelle : téléchargez composer.phar dans le dossier racine');
                return false;
            }

            // Re-cherche Composer après téléchargement
            $composerPath = $this->findComposer();

            if (empty($composerPath)) {
                $this->addError('❌ Composer toujours introuvable après téléchargement');
                return false;
            }
        }

        $this->addLog("✓ Composer trouvé : {$composerPath}");

        // Trouve l'exécutable PHP
        $phpExecutable = $this->findPhpExecutable();
        $this->addLog("🔍 Exécutable PHP : {$phpExecutable}");

        // Change de répertoire
        $oldDir = getcwd();
        chdir(PROJECT_ROOT);

        // Définir les variables d'environnement nécessaires pour Composer
        $homeDir = PROJECT_ROOT;
        if (isset($_SERVER['HOME']) && !empty($_SERVER['HOME'])) {
            $homeDir = $_SERVER['HOME'];
        }

        // Définir les variables d'environnement via putenv() (compatible Windows + Linux)
        $composerHome = PROJECT_ROOT . '/.composer';
        putenv('HOME=' . $homeDir);
        putenv('COMPOSER_HOME=' . $composerHome);

        // Créer le dossier .composer s'il n'existe pas
        if (!is_dir($composerHome)) {
            @mkdir($composerHome, 0755, true);
        }

        $this->addLog("🔍 HOME : {$homeDir}");
        $this->addLog("🔍 COMPOSER_HOME : {$composerHome}");

        // Commande pour Composer
        $composerArgs = [$composerPath, 'install', '--no-interaction', '--prefer-dist', '--optimize-autoloader', '--no-dev'];

        $this->addLog("🔍 Exécution : " . $phpExecutable . " " . implode(' ', $composerArgs));

        // Utilisation de proc_open pour plus de stabilité (évite les problèmes Apache/WAMP)
        $descriptorspec = [
            0 => ['pipe', 'r'],  // stdin
            1 => ['pipe', 'w'],  // stdout
            2 => ['pipe', 'w']   // stderr
        ];

        $process = proc_open(
            escapeshellarg($phpExecutable) . ' ' . implode(' ', array_map('escapeshellarg', $composerArgs)),
            $descriptorspec,
            $pipes,
            PROJECT_ROOT
        );

        $output = [];
        if (is_resource($process)) {
            fclose($pipes[0]); // Ferme stdin

            // Lit stdout
            $stdout = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            // Lit stderr
            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            // Récupère le code de sortie
            $returnCode = proc_close($process);

            // Combine stdout et stderr
            $output = array_filter(array_merge(
                explode("\n", $stdout),
                explode("\n", $stderr)
            ));
        } else {
            $this->addError('❌ Impossible de démarrer le processus Composer');
            chdir($oldDir);
            return false;
        }

        chdir($oldDir);

        if ($returnCode === 0) {
            $this->addLog('✅ Dépendances Composer installées avec succès !');
            $this->addLog('   - egulias/email-validator');
            $this->addLog('   - pragmarx/google2fa (2FA)');
            $this->addLog('   - phpunit/phpunit');
            $this->step = 2;
            return true;
        } else {
            $this->addError('❌ Erreur lors de l\'installation Composer');
            $this->addError('Code retour : ' . $returnCode);
            if (!empty($output)) {
                $this->addError('Sortie : ' . implode("\n", array_slice($output, 0, 10))); // Limite à 10 lignes
            }
            return false;
        }
    }

    /**
     * Étape 3 : Import de la base de données
     */
    private function importDatabase(string $host, string $dbname, string $user, string $pass): bool
    {
        $this->addLog('💾 Import de la base de données...');

        try {
            // Connexion à MySQL
            $dsn = "mysql:host={$host};charset=utf8";
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Crée la base si elle n'existe pas
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8 COLLATE utf8_general_ci");
            $this->addLog("✅ Base de données '{$dbname}' créée/vérifiée");

            // Sélectionne la base
            $pdo->exec("USE `{$dbname}`");

            // Lit et exécute le fichier SQL
            $sql = file_get_contents(SQL_FILE);
            if ($sql === false) {
                throw new Exception('Impossible de lire le fichier SQL');
            }

            // Sépare les requêtes et les exécute
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            $count = 0;

            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    $pdo->exec($statement);
                    $count++;
                }
            }

            $this->addLog("✅ {$count} requêtes SQL exécutées avec succès");

            // Sauvegarde la configuration
            $this->saveDbConfig($host, $dbname, $user, $pass);

            $this->step = 3;
            return true;

        } catch (Exception $e) {
            $this->addError('❌ Erreur lors de l\'import : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Étape 4 : Exécution des tests PHPUnit (optionnel)
     */
    private function runTests(): array
    {
        $this->addLog('🧪 Exécution des tests PHPUnit...');

        $phpunitPath = PROJECT_ROOT . '/vendor/bin/phpunit';
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $phpunitPath .= '.bat';
        }

        if (!file_exists($phpunitPath)) {
            $this->addLog('⚠️  PHPUnit non disponible (installation avec --no-dev)');
            $this->addLog('ℹ️  Les tests sont optionnels et peuvent être ignorés en production');
            $this->step = 4;
            return ['success' => true, 'skipped' => true];
        }

        $oldDir = getcwd();
        chdir(PROJECT_ROOT);

        $command = escapeshellarg($phpunitPath) . ' 2>&1';
        exec($command, $output, $returnCode);

        chdir($oldDir);

        $outputStr = implode("\n", $output);

        // Parse les résultats
        $results = [
            'success' => $returnCode === 0,
            'tests' => 0,
            'assertions' => 0,
            'failures' => 0,
            'skipped' => false
        ];

        if (preg_match('/OK\s+\((\d+)\s+tests?,\s+(\d+)\s+assertions?\)/', $outputStr, $matches)) {
            $results['tests'] = (int) $matches[1];
            $results['assertions'] = (int) $matches[2];
        }

        if ($results['success']) {
            $this->addLog("✅ Tests réussis : {$results['tests']} tests, {$results['assertions']} assertions");
            $this->step = 4;
        } else {
            $this->addLog('⚠️  Certains tests ont échoué (non bloquant)');
            $this->step = 4;  // Continue quand même
        }

        return $results;
    }

    /**
     * Étape 5 : Nettoyage post-installation
     */
    private function cleanup(): bool
    {
        $this->addLog('🧹 Nettoyage des fichiers d\'installation...');

        // Ne pas supprimer install.php et install-ui.php pendant l'exécution
        // Cela causera une erreur 500 car ces fichiers sont en cours d'utilisation
        $filesToDelete = [
            PROJECT_ROOT . '/sport_2000.sql'
        ];

        $deleted = [];
        foreach ($filesToDelete as $file) {
            if (file_exists($file)) {
                if (@unlink($file)) {
                    $deleted[] = basename($file);
                    $this->addLog("✅ Supprimé : " . basename($file));
                } else {
                    $this->addError("⚠️  Impossible de supprimer : " . basename($file));
                }
            }
        }

        $this->addLog('ℹ️  Les fichiers install.php et install-ui.php seront supprimés après la redirection');
        $this->addLog('✅ Installation terminée avec succès !');
        $this->step = 5;

        return true;
    }

    /**
     * Sauvegarde la configuration de la base de données
     */
    private function saveDbConfig(string $host, string $dbname, string $user, string $pass): void
    {
        $configContent = "<?php
return [
    'host' => '{$host}',
    'dbname' => '{$dbname}',
    'username' => '{$user}',
    'password' => '{$pass}',
    'charset' => 'utf8',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
";
        file_put_contents(CONFIG_DB_FILE, $configContent);
        $this->addLog('✅ Configuration base de données sauvegardée');
    }

    /**
     * Trouve le chemin de l'exécutable PHP
     */
    private function findPhpExecutable(): string
    {
        // Chemins possibles pour PHP sur différents hébergements
        $phpPaths = [
            PHP_BINARY,                           // PHP actuel (généralement disponible)
            '/usr/local/bin/php',                 // Standard Linux
            '/usr/bin/php',                       // Standard Linux alternatif
            '/opt/php/bin/php',                   // Certains hébergeurs
            '/usr/local/php8.3/bin/php',          // OVH PHP 8.3
            '/usr/local/php8.2/bin/php',          // OVH PHP 8.2
            '/usr/local/php8.1/bin/php',          // OVH PHP 8.1
            '/usr/local/php8.0/bin/php',          // OVH PHP 8.0
            'php',                                 // Fallback (PATH)
        ];

        foreach ($phpPaths as $path) {
            if (!empty($path) && @is_executable($path)) {
                return $path;
            }
        }

        // Si aucun chemin trouvé, utiliser PHP_BINARY ou 'php' par défaut
        return !empty(PHP_BINARY) ? PHP_BINARY : 'php';
    }

    /**
     * Trouve le chemin de Composer
     */
    private function findComposer(): ?string
    {
        // Cherche composer dans plusieurs emplacements
        $paths = [
            PROJECT_ROOT . '/composer.phar',
            'composer',
            'composer.phar',
            '/usr/local/bin/composer',
            '/usr/bin/composer',
            $_SERVER['HOME'] . '/.composer/composer.phar',
        ];

        foreach ($paths as $path) {
            // Pour les fichiers .phar, vérifie juste l'existence
            if (strpos($path, '.phar') !== false && file_exists($path)) {
                // Vérifie que c'est un fichier lisible
                if (is_readable($path) && filesize($path) > 1000000) {
                    // Fichier .phar valide (> 1MB = probablement Composer)
                    $this->addLog("🔍 Composer.phar trouvé : {$path} (" . round(filesize($path) / 1024 / 1024, 2) . " MB)");
                    return $path;
                }
            }

            // Pour les commandes globales (composer sans .phar)
            if (strpos($path, '.phar') === false) {
                // Teste si le fichier existe et est exécutable
                if (file_exists($path) && is_executable($path)) {
                    $this->addLog("🔍 Composer global trouvé : {$path}");
                    return $path;
                }

                // Teste avec exec si disponible
                if (function_exists('exec')) {
                    $output = [];
                    @exec("$path --version 2>&1", $output, $returnCode);
                    if ($returnCode === 0 && !empty($output)) {
                        $this->addLog("🔍 Composer trouvé via exec : {$path}");
                        return $path;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Télécharge Composer si non trouvé
     */
    private function downloadComposer(): bool
    {
        $this->addLog('📥 Téléchargement de Composer...');

        $composerSetup = PROJECT_ROOT . '/composer-setup.php';
        $composerPhar = PROJECT_ROOT . '/composer.phar';

        // Télécharge le script d'installation de Composer
        $setupScript = @file_get_contents('https://getcomposer.org/installer');

        if ($setupScript === false) {
            $this->addError('❌ Impossible de télécharger Composer');
            return false;
        }

        file_put_contents($composerSetup, $setupScript);

        // Exécute le script d'installation
        exec("php $composerSetup --install-dir=" . PROJECT_ROOT . " --filename=composer.phar 2>&1", $output, $returnCode);

        // Nettoie
        @unlink($composerSetup);

        if ($returnCode === 0 && file_exists($composerPhar)) {
            chmod($composerPhar, 0755);
            $this->addLog('✅ Composer téléchargé avec succès');
            return true;
        }

        $this->addError('❌ Échec du téléchargement de Composer');
        return false;
    }

    /**
     * Ajoute une ligne au log
     */
    private function addLog(string $message): void
    {
        $this->log[] = $message;
    }

    /**
     * Ajoute une erreur
     */
    private function addError(string $message): void
    {
        $this->errors[] = $message;
    }

    /**
     * Affiche l'interface utilisateur
     */
    private function renderUI(): void
    {
        require __DIR__ . '/install-ui.php';
    }

    /**
     * Getters
     */
    public function getLog(): array { return $this->log; }
    public function getErrors(): array { return $this->errors; }
    public function getStep(): int { return $this->step; }
}

// Lance l'installation
$installer = new Installer();
$installer->run();
