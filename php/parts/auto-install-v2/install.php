<?php
session_start();

// Configuration de base (modifiable via formulaire)
$defaultConfig = [
    'db_host' => 'localhost',
    'db_user' => 'root',
    'db_password' => '',
    'db_name' => 'autoinstalltest',
    'site_path' => __DIR__ . '/',
    'zip_file' => 'site.zip',
    'sql_file' => 'database.sql',
    'deploy_password' => 'DWWM@LATESTE',
    'salt' => '33',
    'files_to_delete' => [
        __FILE__,
        'site.zip',
        'index.html',
        'database.sql'
    ]
];

// Récupère la configuration depuis la session ou les valeurs par défaut
function getConfig() {
    global $defaultConfig;
    if (isset($_SESSION['db_config'])) {
        return array_merge($defaultConfig, $_SESSION['db_config']);
    }
    return $defaultConfig;
}

// Test de connexion à la base de données (AJAX)
if (isset($_POST['action']) && $_POST['action'] === 'test_connection') {
    header('Content-Type: application/json');

    $host = $_POST['db_host'] ?? '';
    $user = $_POST['db_user'] ?? '';
    $password = $_POST['db_password'] ?? '';
    $dbname = $_POST['db_name'] ?? '';

    try {
        $pdo = new PDO(
            "mysql:host=" . $host,
            $user,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        // Teste si la base existe
        $stmt = $pdo->prepare("SHOW DATABASES LIKE ?");
        $stmt->execute([$dbname]);
        $dbExists = $stmt->rowCount() > 0;

        echo json_encode([
            'success' => true,
            'message' => 'Connexion réussie!',
            'db_exists' => $dbExists,
            'warning' => $dbExists ? "La base de données '$dbname' existe déjà et sera écrasée." : null
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Erreur de connexion: ' . $e->getMessage()
        ]);
    }
    exit;
}

// Sauvegarde de la configuration
if (isset($_POST['action']) && $_POST['action'] === 'save_config') {
    $_SESSION['db_config'] = [
        'db_host' => $_POST['db_host'] ?? 'localhost',
        'db_user' => $_POST['db_user'] ?? 'root',
        'db_password' => $_POST['db_password'] ?? '',
        'db_name' => $_POST['db_name'] ?? 'autoinstalltest'
    ];
    $_SESSION['config_validated'] = true;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

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
            body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
            .login-form { max-width: 400px; margin: 0 auto; padding: 20px; background: white; border: 1px solid #ccc; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
            .input-group { margin-bottom: 15px; }
            label { display: block; margin-bottom: 5px; font-weight: bold; }
            input[type="password"] { width: 100%; padding: 8px; margin: 5px 0; box-sizing: border-box; }
            button { background: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 3px; cursor: pointer; width: 100%; }
            button:hover { background: #45a049; }
            .error { color: red; margin-bottom: 15px; }
        </style>
    </head>
    <body>
        <div class="login-form">
            <h2>🔒 Authentification requise</h2>
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

// Formulaire de configuration de la base de données
function showConfigForm()
{
    global $defaultConfig;

    $host = $defaultConfig['db_host'];
    $user = $defaultConfig['db_user'];
    $password = $defaultConfig['db_password'];
    $dbname = $defaultConfig['db_name'];

    echo <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <title>Configuration de la base de données</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
            .config-form { max-width: 600px; margin: 0 auto; padding: 30px; background: white; border: 1px solid #ccc; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
            .input-group { margin-bottom: 20px; }
            label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
            input[type="text"], input[type="password"] {
                width: 100%;
                padding: 10px;
                box-sizing: border-box;
                border: 1px solid #ddd;
                border-radius: 3px;
            }
            input:focus { outline: none; border-color: #4CAF50; }
            .button-group { display: flex; gap: 10px; margin-top: 25px; }
            button {
                padding: 12px 20px;
                border: none;
                border-radius: 3px;
                cursor: pointer;
                font-size: 14px;
                flex: 1;
            }
            .btn-test { background: #2196F3; color: white; }
            .btn-test:hover { background: #0b7dda; }
            .btn-deploy { background: #4CAF50; color: white; }
            .btn-deploy:hover { background: #45a049; }
            .btn-deploy:disabled { background: #ccc; cursor: not-allowed; }
            .message {
                padding: 12px;
                margin-bottom: 20px;
                border-radius: 3px;
                display: none;
            }
            .message.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
            .message.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
            .message.warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
            .loader {
                border: 3px solid #f3f3f3;
                border-top: 3px solid #2196F3;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                animation: spin 1s linear infinite;
                display: none;
                margin: 0 auto;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            h2 { color: #333; margin-bottom: 10px; }
            .subtitle { color: #666; margin-bottom: 25px; }
        </style>
    </head>
    <body>
        <div class="config-form">
            <h2>⚙️ Configuration de la base de données</h2>
            <p class="subtitle">Configurez les paramètres de connexion à votre base de données</p>

            <div id="message" class="message"></div>

            <form id="configForm" method="POST">
                <input type="hidden" name="action" value="save_config">

                <div class="input-group">
                    <label for="db_host">Adresse du serveur MySQL:</label>
                    <input type="text" id="db_host" name="db_host" value="$host" required
                           placeholder="localhost ou 127.0.0.1">
                </div>

                <div class="input-group">
                    <label for="db_user">Nom d'utilisateur:</label>
                    <input type="text" id="db_user" name="db_user" value="$user" required
                           placeholder="root">
                </div>

                <div class="input-group">
                    <label for="db_password">Mot de passe:</label>
                    <input type="password" id="db_password" name="db_password" value="$password"
                           placeholder="Laissez vide si pas de mot de passe">
                </div>

                <div class="input-group">
                    <label for="db_name">Nom de la base de données:</label>
                    <input type="text" id="db_name" name="db_name" value="$dbname" required
                           placeholder="nom_de_la_base">
                </div>

                <div class="loader" id="loader"></div>

                <div class="button-group">
                    <button type="button" class="btn-test" onclick="testConnection()">
                        🔌 Tester la connexion
                    </button>
                    <button type="submit" class="btn-deploy" id="deployBtn" disabled>
                        🚀 Déployer le site
                    </button>
                </div>
            </form>
        </div>

        <script>
            let connectionTested = false;

            function showMessage(type, text) {
                const msgDiv = document.getElementById('message');
                msgDiv.className = 'message ' + type;
                msgDiv.textContent = text;
                msgDiv.style.display = 'block';
            }

            function hideMessage() {
                document.getElementById('message').style.display = 'none';
            }

            function testConnection() {
                hideMessage();
                const loader = document.getElementById('loader');
                const deployBtn = document.getElementById('deployBtn');

                loader.style.display = 'block';
                deployBtn.disabled = true;
                connectionTested = false;

                const formData = new FormData();
                formData.append('action', 'test_connection');
                formData.append('db_host', document.getElementById('db_host').value);
                formData.append('db_user', document.getElementById('db_user').value);
                formData.append('db_password', document.getElementById('db_password').value);
                formData.append('db_name', document.getElementById('db_name').value);

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    loader.style.display = 'none';

                    if (data.success) {
                        let message = '✓ ' + data.message;
                        if (data.warning) {
                            message += ' ⚠️ ' + data.warning;
                            showMessage('warning', message);
                        } else {
                            showMessage('success', message);
                        }
                        deployBtn.disabled = false;
                        connectionTested = true;
                    } else {
                        showMessage('error', '✗ ' + data.message);
                        deployBtn.disabled = true;
                    }
                })
                .catch(error => {
                    loader.style.display = 'none';
                    showMessage('error', '✗ Erreur lors du test de connexion: ' + error);
                    deployBtn.disabled = true;
                });
            }

            // Désactive le bouton si l'utilisateur modifie un champ après le test
            document.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', function() {
                    if (connectionTested) {
                        document.getElementById('deployBtn').disabled = true;
                        showMessage('warning', '⚠️ Configuration modifiée. Veuillez tester à nouveau la connexion.');
                        connectionTested = false;
                    }
                });
            });

            // Validation avant soumission
            document.getElementById('configForm').addEventListener('submit', function(e) {
                if (!connectionTested) {
                    e.preventDefault();
                    showMessage('error', '⚠️ Vous devez d\'abord tester la connexion avant de déployer.');
                }
            });
        </script>
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
            body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
            .deploy-container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
            pre { background: #f5f5f5; padding: 15px; border-radius: 5px; border-left: 4px solid #4CAF50; }
            .success { color: green; font-weight: bold; }
            .error { color: red; font-weight: bold; }
            .warning { color: orange; font-weight: bold; }
            h1 { color: #333; }
            a { display: inline-block; margin-top: 15px; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 3px; }
            a:hover { background: #45a049; }
        </style></head><body>";
        echo "<div class='deploy-container'>";
        echo "<h1>🚀 Déploiement du site</h1>";
        echo "<p><strong>Configuration utilisée:</strong></p>";
        echo "<ul>";
        echo "<li>Serveur: " . htmlspecialchars($this->config['db_host']) . "</li>";
        echo "<li>Utilisateur: " . htmlspecialchars($this->config['db_user']) . "</li>";
        echo "<li>Base de données: " . htmlspecialchars($this->config['db_name']) . "</li>";
        echo "</ul>";
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

        echo "\n<span class='success'>✓ Déploiement terminé avec succès!</span>\n";

        echo "\n<span class='warning'>⚠ Suppression des fichiers de déploiement...</span>\n";
        $this->cleanupFiles();

        echo "\n⚠ Ce script va s'auto-détruire et ne sera plus accessible.\n";
        echo "</pre>";
        echo "<a href='./'>🌐 Accéder au site</a>";
        echo "</div>";
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

// Récupération de la configuration
$config = getConfig();

// Flux d'installation en 3 étapes:
// 1. Authentification
if (!checkAuth($config)) {
    showLoginForm();
}

// 2. Configuration de la base de données
if (!isset($_SESSION['config_validated'])) {
    showConfigForm();
}

// 3. Déploiement
$deployer = new Deployer($config);
$deployer->deploy();
