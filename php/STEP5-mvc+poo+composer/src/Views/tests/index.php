<!-- Vue de la page des tests PHPUnit -->
<!-- Cette page affiche les résultats des tests de manière formatée et lisible -->

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- En-tête de la page -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">
                        <i class="bi bi-check-circle"></i> Suite de Tests PHPUnit
                    </h1>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        Cette page exécute automatiquement tous les tests PHPUnit du projet.
                        <strong>Réservé aux administrateurs uniquement.</strong>
                    </p>
                </div>
            </div>

            <!-- Statistiques des tests -->
            <div class="row mb-4">
                <!-- Statut global -->
                <div class="col-md-3 mb-3">
                    <div class="card h-100 <?php
                        echo match($results['status']) {
                            'success' => 'border-success',
                            'failure' => 'border-danger',
                            'warning' => 'border-warning',
                            default => 'border-secondary'
                        };
                    ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title">Statut Global</h5>
                            <div class="display-4 <?php
                                echo match($results['status']) {
                                    'success' => 'text-success',
                                    'failure' => 'text-danger',
                                    'warning' => 'text-warning',
                                    default => 'text-secondary'
                                };
                            ?>">
                                <?php
                                    echo match($results['status']) {
                                        'success' => '✓',
                                        'failure' => '✗',
                                        'warning' => '⚠',
                                        default => '?'
                                    };
                                ?>
                            </div>
                            <p class="card-text text-muted">
                                <?php
                                    echo match($results['status']) {
                                        'success' => 'Tous les tests passent',
                                        'failure' => 'Échecs détectés',
                                        'warning' => 'Avertissements',
                                        default => 'Statut inconnu'
                                    };
                                ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Nombre de tests -->
                <div class="col-md-3 mb-3">
                    <div class="card h-100 border-info">
                        <div class="card-body text-center">
                            <h5 class="card-title">Tests Exécutés</h5>
                            <div class="display-4 text-info"><?= $results['total_tests'] ?></div>
                            <p class="card-text text-muted">
                                <?= $results['assertions'] ?> assertions
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Taux de réussite -->
                <div class="col-md-3 mb-3">
                    <div class="card h-100 border-primary">
                        <div class="card-body text-center">
                            <h5 class="card-title">Taux de Réussite</h5>
                            <div class="display-4 text-primary"><?= $results['success_rate'] ?>%</div>
                            <div class="progress mt-2">
                                <div class="progress-bar <?= $results['success_rate'] == 100 ? 'bg-success' : 'bg-warning' ?>"
                                     role="progressbar"
                                     style="width: <?= $results['success_rate'] ?>%"
                                     aria-valuenow="<?= $results['success_rate'] ?>"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Temps d'exécution -->
                <div class="col-md-3 mb-3">
                    <div class="card h-100 border-secondary">
                        <div class="card-body text-center">
                            <h5 class="card-title">Temps d'Exécution</h5>
                            <div class="display-4 text-secondary">⏱</div>
                            <p class="card-text text-muted">
                                <?= htmlspecialchars($results['time']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails des erreurs/échecs -->
            <?php if ($results['failures'] > 0 || $results['errors'] > 0): ?>
            <div class="alert alert-danger mb-4" role="alert">
                <h4 class="alert-heading">⚠ Attention : Des tests ont échoué</h4>
                <hr>
                <ul class="mb-0">
                    <?php if ($results['failures'] > 0): ?>
                        <li><strong><?= $results['failures'] ?> échec(s)</strong> : Les assertions n'ont pas été satisfaites</li>
                    <?php endif; ?>
                    <?php if ($results['errors'] > 0): ?>
                        <li><strong><?= $results['errors'] ?> erreur(s)</strong> : Erreurs PHP ou exceptions non gérées</li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- Résultats détaillés -->
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-terminal"></i> Sortie Détaillée de PHPUnit
                    </h5>
                </div>
                <div class="card-body bg-dark text-light p-0">
                    <pre class="m-0 p-4" style="font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.5; max-height: 600px; overflow-y: auto;"><?php
                        // Convertit les codes ANSI en HTML pour préserver les couleurs
                        $coloredOutput = htmlspecialchars($output);

                        // Remplace les marqueurs de couleur ANSI par du HTML
                        $coloredOutput = preg_replace('/\[32m(.*?)\[39m/', '<span style="color: #2ecc71;">$1</span>', $coloredOutput);
                        $coloredOutput = preg_replace('/\[31m(.*?)\[39m/', '<span style="color: #e74c3c;">$1</span>', $coloredOutput);
                        $coloredOutput = preg_replace('/\[33m(.*?)\[39m/', '<span style="color: #f39c12;">$1</span>', $coloredOutput);
                        $coloredOutput = preg_replace('/\[36m(.*?)\[39m/', '<span style="color: #3498db;">$1</span>', $coloredOutput);

                        echo $coloredOutput;
                    ?></pre>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-4 d-flex justify-content-between">
                <a href="<?= BASE_URL ?>/home.html" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour à l'accueil
                </a>
                <a href="<?= BASE_URL ?>/tests.html" class="btn btn-primary">
                    <i class="bi bi-arrow-clockwise"></i> Relancer les tests
                </a>
            </div>

            <!-- Informations sur les tests -->
            <div class="card mt-4 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">ℹ Informations sur les Tests</h5>
                </div>
                <div class="card-body">
                    <h6>Tests Implémentés (92 tests, 147 assertions) :</h6>
                    <ul>
                        <li><strong>Helpers (60 tests)</strong>
                            <ul>
                                <li>EmailValidator (15 tests) : Validation stricte des emails (RFC + DNS + MX)</li>
                                <li>StringHelper (16 tests) : Manipulation de chaînes (slugify, truncate, sanitize, escape)</li>
                                <li>UrlHelper (12 tests) : Génération d'URLs (createArticleUrl, url, getCurrentUrl)</li>
                                <li>AuthHelper (17 tests) : Authentification et autorisations</li>
                            </ul>
                        </li>
                        <li><strong>Models (21 tests)</strong>
                            <ul>
                                <li>User (11 tests) : Gestion des utilisateurs, rôles, authentification</li>
                                <li>Article (10 tests) : Structure et validation des articles</li>
                            </ul>
                        </li>
                        <li><strong>Integration (11 tests)</strong>
                            <ul>
                                <li>RouterIntegration : Tests de routing complet, sécurité (SQL injection, path traversal)</li>
                            </ul>
                        </li>
                    </ul>

                    <h6 class="mt-3">Configuration :</h6>
                    <ul>
                        <li>Framework : <strong>PHPUnit <?= class_exists('PHPUnit\Runner\Version') ? PHPUnit\Runner\Version::id() : '12.5+' ?></strong></li>
                        <li>Fichier de config : <code>phpunit.xml</code></li>
                        <li>Dossier des tests : <code>tests/</code></li>
                        <li>Autoload : PSR-4 via Composer</li>
                    </ul>

                    <h6 class="mt-3">Pour exécuter les tests en ligne de commande :</h6>
                    <pre class="bg-dark text-light p-3 rounded"><code>composer test
# ou directement :
vendor/bin/phpunit</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Amélioration visuelle pour la sortie de terminal */
    pre {
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .card {
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
    }
</style>
