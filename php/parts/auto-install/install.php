<?php
session_start();

// Configuration
$config = [
    'db_host' => 'localhost',
    'db_user' => 'root',
    'db_password' => '',
    'db_name' => 'autoinstalltest',
    'site_path' => __DIR__ . '/',
    'zip_file' => 'site.zip',
    'sql_file' => 'database.sql',
    'deploy_password' => 'DWWM@LATESTE',
    'salt' => '33',
    // Fichiers qui vont etre supprimé après déploiement
    'files_to_delete' => [
        __FILE__,
        'site.zip',
        'index.html',
        'database.sql'
    ]
];

// Gestion de l'authentification
function checkAuth($config)
{
    if (isset($_POST['password'])) {
        $hashedPassword = hash('sha256', $_POST['password'] . $config['salt']);
        $expectedHash = hash('sha256', $config['deploy_password'] . $config['salt']);

        if (hash_equals($expectedHash, $hashedPassword)) {
            $_SESSION['authenticated'] = true;
            return true;
        }
    }
    return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
}

// Formulaire de connexion
function showLoginForm()
{
    echo <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <title>Déploiement - Authentification</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; }
            .login-form { max-width: 400px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
            .input-group { margin-bottom: 15px; }
            input[type="password"] { width: 100%; padding: 8px; margin: 5px 0; }
            button { background: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 3px; cursor: pointer; }
            .error { color: red; margin-bottom: 15px; }
        </style>
    </head>
    <body>
        <div class="login-form">
            <h2>Authentification requise</h2>
            <form method="POST">
                <div class="input-group">
                    <label>Mot de passe de déploiement:</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit">Se connecter</button>
            </form>
        </div>
    </body>
    </html>
HTML;
    exit;
}

// Classe de déploiement
class Deployer
{
    private $config;
    private $errors = [];
    private $success = false;

    public function __construct($config)
    {
        $this->config = $config;
    }

    // Vérifie les prérequis
    public function checkRequirements()
    {
        if (!file_exists($this->config['zip_file'])) {
            $this->errors[] = "Le fichier ZIP n'existe pas";
            return false;
        }
        if (!file_exists($this->config['sql_file'])) {
            $this->errors[] = "Le fichier SQL n'existe pas";
            return false;
        }
        return true;
    }

    // Crée et extrait les fichiers
    public function extractFiles()
    {
        if (!is_dir($this->config['site_path'])) {
            mkdir($this->config['site_path'], 0755, true);
        }

        $zip = new ZipArchive;
        if ($zip->open($this->config['zip_file']) === TRUE) {
            $zip->extractTo($this->config['site_path']);
            $zip->close();
            return true;
        } else {
            $this->errors[] = "Échec de l'extraction du ZIP";
            return false;
        }
    }

    // Configure les permissions
    public function setPermissions()
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->config['site_path'])
        );

        foreach ($iterator as $item) {
            chmod($item, 0755);
        }
        return true;
    }

    // Importe la base de données
    public function importDatabase()
    {
        try {
            $pdo = new PDO(
                "mysql:host=" . $this->config['db_host'],
                $this->config['db_user'],
                $this->config['db_password']
            );

            $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . $this->config['db_name'] . "`");
            $pdo->exec("USE `" . $this->config['db_name'] . "`");

            $sql = file_get_contents($this->config['sql_file']);
            $pdo->exec($sql);
            return true;
        } catch (PDOException $e) {
            $this->errors[] = "Erreur base de données: " . $e->getMessage();
            return false;
        }
    }

    // Supprime les fichiers de déploiement
    private function cleanupFiles()
    {
        foreach ($this->config['files_to_delete'] as $file) {
            if (file_exists($file)) {
                if (@unlink($file)) {
                    echo "✓ Suppression de " . basename($file) . " réussie\n";
                } else {
                    echo "⚠ Échec de la suppression de " . basename($file) . "\n";
                }
            }
        }
    }

    // Exécute le déploiement
    public function deploy()
    {
        echo "<!DOCTYPE html><html><head><title>Déploiement du site</title>";
        echo "<style>
            body { font-family: Arial, sans-serif; margin: 40px; } 
            pre { background: #f5f5f5; padding: 15px; border-radius: 5px; }
            .success { color: green; } 
            .error { color: red; }
            .warning { color: orange; font-weight: bold; }
        </style></head><body>";
        echo "<h1>Déploiement du site</h1>";
        echo "<pre>";

        if (!$this->checkRequirements()) {
            $this->showErrors();
            return false;
        }
        echo "✓ Vérification des prérequis OK\n";

        if (!$this->extractFiles()) {
            $this->showErrors();
            return false;
        }
        echo "✓ Extraction des fichiers OK\n";

        if (!$this->setPermissions()) {
            $this->showErrors();
            return false;
        }
        echo "✓ Configuration des permissions OK\n";

        if (!$this->importDatabase()) {
            $this->showErrors();
            return false;
        }
        echo "✓ Import de la base de données OK\n";

        echo "\n<span class='success'>Déploiement terminé avec succès!</span>\n";
        echo "<a href='./'>Le site est accessible ici</a>\n";

        echo "\n<span class='warning'>Suppression des fichiers de déploiement...</span>\n";
        $this->cleanupFiles();

        echo "\n⚠ Ce script va s'auto-détruire et ne sera plus accessible.\n";
        echo "</pre>";
        echo "</body></html>";

        // On vide le buffer de sortie avant la suppression
        if (ob_get_level()) ob_end_flush();
        flush();

        // On attend une seconde pour s'assurer que la page s'affiche
        sleep(1);

        return true;
    }

    // Affiche les erreurs
    private function showErrors()
    {
        echo "<span class='error'>Erreurs rencontrées:</span>\n";
        foreach ($this->errors as $error) {
            echo "- " . htmlspecialchars($error) . "\n";
        }
    }
}

// Gestion de la déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Vérification de l'authentification avant le déploiement
if (!checkAuth($config)) {
    showLoginForm();
}

// Exécution du déploiement si authentifié
$deployer = new Deployer($config);
$deployer->deploy();
