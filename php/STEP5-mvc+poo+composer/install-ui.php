<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation - Projet MVC PHP</title>
    <link rel="stylesheet" href="public/assets/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .install-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: none;
            border-radius: 15px;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 25px;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .step {
            flex: 1;
            text-align: center;
            padding: 10px;
            background: #e0e0e0;
            margin: 0 5px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }

        .step.active {
            background: #28a745;
            color: white;
        }

        .step.completed {
            background: #17a2b8;
            color: white;
        }

        .log-output {
            background: #1e1e1e;
            color: #00ff00;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 15px;
        }

        .log-output div {
            margin-bottom: 5px;
        }

        .error-output {
            background: #ffe0e0;
            color: #c00;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .btn-install {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: bold;
        }

        .btn-install:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .success-animation {
            text-align: center;
            font-size: 80px;
            animation: pulse 1s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }
    </style>
</head>

<body>
    <div class="install-container">
        <!-- En-tête -->
        <div class="card mb-4">
            <div class="card-header text-center">
                <h1 class="mb-0">🚀 Installation Automatique</h1>
                <p class="mb-0 mt-2">Projet MVC PHP - Version <?= INSTALL_VERSION ?></p>
            </div>
        </div>

        <!-- Indicateur d'étapes -->
        <div class="step-indicator">
            <div class="step <?= $this->getStep() >= 0 ? 'active' : '' ?> <?= $this->getStep() > 0 ? 'completed' : '' ?>">
                1. Prérequis
            </div>
            <div class="step <?= $this->getStep() >= 1 ? 'active' : '' ?> <?= $this->getStep() > 1 ? 'completed' : '' ?>">
                2. Composer
            </div>
            <div class="step <?= $this->getStep() >= 2 ? 'active' : '' ?> <?= $this->getStep() > 2 ? 'completed' : '' ?>">
                3. Base de données
            </div>
            <div class="step <?= $this->getStep() >= 3 ? 'active' : '' ?> <?= $this->getStep() > 3 ? 'completed' : '' ?>">
                4. Tests
            </div>
            <div class="step <?= $this->getStep() >= 4 ? 'active' : '' ?> <?= $this->getStep() > 4 ? 'completed' : '' ?>">
                5. Nettoyage
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="card">
            <div class="card-body p-4">

                <?php if ($this->getStep() === 5): ?>
                    <!-- Installation terminée -->
                    <div class="success-animation">🎉</div>
                    <h2 class="text-center text-success mb-4">Installation Terminée !</h2>

                    <div class="alert alert-success">
                        <h5>Résumé de l'installation :</h5>
                        <ul class="mb-0">
                            <li>✅ Site installé et opérationnel</li>
                            <li>✅ Base de données importée</li>
                            <li>✅ Dépendances Composer installées</li>
                            <li>✅ Tests PHPUnit réussis</li>
                            <li>✅ Fichiers d'installation supprimés</li>
                        </ul>
                    </div>

                    <?php if (!empty($this->getLog())): ?>
                        <div class="log-output">
                            <?php foreach ($this->getLog() as $line): ?>
                                <div><?= htmlspecialchars($line) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="text-center mt-4">
                        <a href="cleanup-install.php" class="btn btn-success btn-lg">
                            🏠 Accéder au site
                        </a>
                        <p class="text-muted mt-3">
                            <small>Redirection automatique dans <span id="countdown">5</span> secondes...</small>
                        </p>
                    </div>

                    <script>
                        // Redirection automatique après 5 secondes vers le script de nettoyage
                        let countdown = 5;
                        const countdownElement = document.getElementById('countdown');

                        const interval = setInterval(() => {
                            countdown--;
                            if (countdownElement) {
                                countdownElement.textContent = countdown;
                            }

                            if (countdown <= 0) {
                                clearInterval(interval);
                                // Redirige vers cleanup-install.php qui supprimera les fichiers d'installation
                                window.location.href = 'cleanup-install.php';
                            }
                        }, 1000);
                    </script>

                <?php elseif ($this->getStep() === 0): ?>
                    <!-- Étape 1: Vérification des prérequis -->
                    <h3 class="mb-4">📋 Étape 1 : Vérification des Prérequis</h3>

                    <p>Cette installation va automatiser :</p>
                    <ul>
                        <li>✅ Installation des dépendances Composer (Egulias, PHPUnit)</li>
                        <li>✅ Import de la base de données MySQL</li>
                        <li>✅ Exécution de la suite de tests PHPUnit</li>
                        <li>✅ Nettoyage des fichiers d'installation</li>
                    </ul>

                    <form method="POST">
                        <input type="hidden" name="action" value="check_requirements">
                        <div class="text-center">
                            <button type="submit" class="btn btn-install btn-primary btn-lg">
                                Démarrer l'installation
                            </button>
                        </div>
                    </form>

                <?php elseif ($this->getStep() === 1): ?>
                    <!-- Étape 2: Installation Composer -->
                    <h3 class="mb-4">📦 Étape 2 : Installation des Dépendances</h3>

                    <?php if (!empty($this->getLog())): ?>
                        <div class="log-output">
                            <?php foreach ($this->getLog() as $line): ?>
                                <div><?= htmlspecialchars($line) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="action" value="install_composer">
                        <div class="text-center">
                            <button type="submit" class="btn btn-install btn-primary btn-lg">
                                Installer les dépendances Composer
                            </button>
                        </div>
                    </form>

                <?php elseif ($this->getStep() === 2): ?>
                    <!-- Étape 3: Configuration base de données -->
                    <h3 class="mb-4">💾 Étape 3 : Configuration de la Base de Données</h3>

                    <?php if (!empty($this->getLog())): ?>
                        <div class="log-output">
                            <?php foreach ($this->getLog() as $line): ?>
                                <div><?= htmlspecialchars($line) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="mt-4">
                        <input type="hidden" name="action" value="import_database">

                        <div class="mb-3">
                            <label class="form-label">Hôte MySQL</label>
                            <input type="text" name="db_host" class="form-control" value="localhost" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nom de la base</label>
                            <input type="text" name="db_name" class="form-control" value="sport_2000" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Utilisateur MySQL</label>
                            <input type="text" name="db_user" class="form-control" value="root" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mot de passe MySQL</label>
                            <input type="password" name="db_pass" class="form-control" value="">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-install btn-primary btn-lg">
                                Importer la base de données
                            </button>
                        </div>
                    </form>

                <?php elseif ($this->getStep() === 3): ?>
                    <!-- Étape 4: Tests PHPUnit -->
                    <h3 class="mb-4">🧪 Étape 4 : Exécution des Tests</h3>

                    <?php if (!empty($this->getLog())): ?>
                        <div class="log-output">
                            <?php foreach ($this->getLog() as $line): ?>
                                <div><?= htmlspecialchars($line) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="action" value="run_tests">
                        <div class="text-center">
                            <button type="submit" class="btn btn-install btn-primary btn-lg">
                                Exécuter les tests PHPUnit
                            </button>
                        </div>
                    </form>

                <?php elseif ($this->getStep() === 4): ?>
                    <!-- Étape 5: Nettoyage -->
                    <h3 class="mb-4">🧹 Étape 5 : Nettoyage</h3>

                    <?php if (!empty($this->getLog())): ?>
                        <div class="log-output">
                            <?php foreach ($this->getLog() as $line): ?>
                                <div><?= htmlspecialchars($line) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="alert alert-warning">
                        <strong>⚠️ Attention :</strong> Cette étape va supprimer les fichiers d'installation :
                        <ul class="mb-0 mt-2">
                            <li>install.php</li>
                            <li>install-ui.php (ce fichier)</li>
                            <li>sport_2000.sql</li>
                        </ul>
                    </div>

                    <form method="POST">
                        <input type="hidden" name="action" value="cleanup">
                        <div class="text-center">
                            <button type="submit" class="btn btn-install btn-success btn-lg">
                                Finaliser l'installation
                            </button>
                        </div>
                    </form>

                <?php endif; ?>

                <!-- Affichage des erreurs -->
                <?php if (!empty($this->getErrors())): ?>
                    <div class="error-output mt-3">
                        <h5>❌ Erreurs détectées :</h5>
                        <?php foreach ($this->getErrors() as $error): ?>
                            <div><?= htmlspecialchars($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-4 text-white">
            <small>Installation DevOps automatique - Projet MVC PHP <?= INSTALL_VERSION ?></small>
        </div>
    </div>

    <script src="public/assets/js/bootstrap.bundle.js"></script>
</body>

</html>