<?php

namespace App\Controllers;

use App\Models\Article;
use App\Models\MatchModel;
use App\Helpers\StringHelper;
use App\Helpers\UrlHelper;

/**
 * Classe ArticleController - Contrôleur gérant les articles
 *
 * Dans le pattern MVC :
 * - Le Contrôleur (Controller) reçoit les requêtes de l'utilisateur
 * - Il fait le lien entre les Modèles (données) et les Vues (affichage)
 * - Il contient la logique de traitement des actions utilisateur
 * - Il ne contient PAS de requêtes SQL (c'est le rôle des Modèles)
 * - Il ne contient PAS de code HTML (c'est le rôle des Vues)
 */
class ArticleController
{
    /**
     * Instance du modèle Article
     * @var Article
     */
    private Article $articleModel;

    /**
     * Instance du modèle Match
     * @var MatchModel
     */
    private MatchModel $matchModel;

    /**
     * Constructeur - Initialise les modèles nécessaires
     */
    public function __construct()
    {
        // On instancie les modèles dont on aura besoin
        $this->articleModel = new Article();
        $this->matchModel = new MatchModel();
    }

    /**
     * Affiche la page d'accueil avec la liste de tous les articles
     *
     * Cette méthode :
     * 1. Récupère tous les articles depuis le modèle
     * 2. Prépare les données pour la vue
     * 3. Appelle la vue pour l'affichage
     */
    public function index(): void
    {
        // Récupère tous les articles depuis la base de données
        $articles = $this->articleModel->getAll();

        // Définit les métadonnées de la page
        $title = "L'Actu avec Sport 2000";
        $metadesc = "L'Actu avec Sport 2000 : c'est les meilleurs journalistes sportifs spécialisés qui...";

        // Charge la vue avec les données
        // On utilise require pour inclure le fichier de vue
        // Les variables $articles, $title, $metadesc seront accessibles dans la vue
        $this->render('articles/index', compact('articles', 'title', 'metadesc'));
    }

    /**
     * Affiche un article spécifique
     *
     * @param int $id L'identifiant de l'article à afficher
     */
    public function show(int $id): void
    {
        // Récupère l'article par son ID
        $article = $this->articleModel->getById($id);

        // Si l'article n'existe pas, affiche une erreur 404
        if (!$article) {
            $this->notFound();
            return;
        }

        // Prépare les métadonnées de la page avec les infos de l'article
        $title = $article['titre'];
        $metadesc = $article['titre'] . ", " . StringHelper::truncate($article['contenu'], 80);

        // Charge la vue de l'article
        $this->render('articles/show', compact('article', 'title', 'metadesc'));
    }

    /**
     * Affiche le formulaire d'ajout d'un nouvel article
     */
    public function create(): void
    {
        $title = "Ajouter un nouvel article";
        $metadesc = "Formulaire d'ajout d'un nouvel article";

        $this->render('articles/create', compact('title', 'metadesc'));
    }

    /**
     * Traite la soumission du formulaire d'ajout d'article
     *
     * Cette méthode gère :
     * 1. La validation des données
     * 2. La création du match (si demandé)
     * 3. La création de l'article
     * 4. La redirection vers la page d'édition
     */
    public function store(): void
    {
        // Vérifie que la requête est bien en POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            UrlHelper::redirect('home.html');
            return;
        }

        $postData = $_POST;

        // Validation des champs obligatoires de l'article
        if (
            empty($postData['titre']) ||
            empty($postData['contenu']) ||
            empty($postData['auteur']) ||
            StringHelper::sanitize($postData['titre']) === '' ||
            StringHelper::sanitize($postData['contenu']) === '' ||
            StringHelper::sanitize($postData['auteur']) === ''
        ) {
            // En cas d'erreur, on stocke le message en session et on redirige
            $_SESSION['error_message'] = 'Il faut un titre + un contenu + un auteur pour soumettre le formulaire.';
            UrlHelper::redirect('add.html');
            return;
        }

        // Nettoie les données de l'article (sécurité contre XSS)
        $titre = StringHelper::sanitize($postData['titre']);
        $contenu = StringHelper::sanitize($postData['contenu']);
        $auteur = StringHelper::sanitize($postData['auteur']);

        // Initialise l'ID du match à 0 (pas de match par défaut)
        $match_id = 0;

        // Si l'utilisateur a coché la case "ajouter un match"
        if (isset($postData['ajouterMatch']) && $postData['ajouterMatch'] === 'on') {
            // Vérifie que tous les champs du match sont remplis
            if (
                empty($postData['equipe1']) ||
                empty($postData['equipe2']) ||
                empty($postData['score']) ||
                empty($postData['lieu'])
            ) {
                $_SESSION['error_message'] = 'Tous les champs du match doivent être remplis.';
                UrlHelper::redirect('add.html');
                return;
            }

            // Prépare les données du match
            $matchData = [
                'equipe1' => StringHelper::sanitize($postData['equipe1']),
                'equipe2' => StringHelper::sanitize($postData['equipe2']),
                'score' => StringHelper::sanitize($postData['score']),
                'lieu' => StringHelper::sanitize($postData['lieu']),
                'resume' => StringHelper::sanitize($postData['resume'] ?? ''),
                'date_match' => date('Y-m-d'),
            ];

            // Crée le match en base de données et récupère son ID
            $match_id = $this->matchModel->create($matchData);
        }

        // Prépare les données de l'article
        $articleData = [
            'titre' => $titre,
            'contenu' => $contenu,
            'auteur' => $auteur,
            'match_id' => $match_id,
        ];

        // Crée l'article en base de données
        $articleId = $this->articleModel->create($articleData);

        // Message de succès
        $_SESSION['success_message'] = "L'article a été ajouté avec succès ! Vous pouvez le corriger si nécessaire :";

        // Redirige vers la page d'édition de l'article créé
        UrlHelper::redirect("edit.html?id={$articleId}");
    }

    /**
     * Affiche le formulaire de modification d'un article
     *
     * @param int $id L'identifiant de l'article à modifier
     */
    public function edit(int $id): void
    {
        // Récupère l'article à modifier
        $article = $this->articleModel->getById($id);

        // Si l'article n'existe pas, erreur 404
        if (!$article) {
            $this->notFound();
            return;
        }

        $title = "Modifier l'article";
        $metadesc = "Modification de l'article : " . $article['titre'];

        $this->render('articles/edit', compact('article', 'title', 'metadesc'));
    }

    /**
     * Traite la soumission du formulaire de modification
     */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            UrlHelper::redirect('home.html');
            return;
        }

        $postData = $_POST;

        // Validation des données
        if (
            !isset($postData['id']) ||
            !is_numeric($postData['id']) ||
            empty($postData['titre']) ||
            empty($postData['contenu']) ||
            StringHelper::sanitize($postData['titre']) === '' ||
            StringHelper::sanitize($postData['contenu']) === ''
        ) {
            $_SESSION['error_message'] = 'Il manque des informations pour permettre l\'édition du formulaire.';
            UrlHelper::redirect('home.html');
            return;
        }

        $id = (int) $postData['id'];
        $titre = StringHelper::sanitize($postData['titre']);
        $contenu = StringHelper::sanitize($postData['contenu']);

        // Met à jour l'article
        $this->articleModel->update($id, [
            'titre' => $titre,
            'contenu' => $contenu,
        ]);

        // Si l'utilisateur a coché "modifier le match"
        if (isset($postData['modifierMatch']) && $postData['modifierMatch'] === 'on') {
            // Récupère l'ID du match associé
            $matchId = $this->articleModel->getMatchId($id);

            if ($matchId) {
                // Validation des champs du match
                if (
                    empty($postData['equipe1']) ||
                    empty($postData['equipe2']) ||
                    empty($postData['score']) ||
                    empty($postData['lieu'])
                ) {
                    $_SESSION['error_message'] = 'Tous les champs du match doivent être remplis.';
                    UrlHelper::redirect("edit.html?id={$id}");
                    return;
                }

                // Met à jour le match
                $this->matchModel->update($matchId, [
                    'equipe1' => StringHelper::sanitize($postData['equipe1']),
                    'equipe2' => StringHelper::sanitize($postData['equipe2']),
                    'score' => StringHelper::sanitize($postData['score']),
                    'lieu' => StringHelper::sanitize($postData['lieu']),
                    'resume' => StringHelper::sanitize($postData['resume'] ?? ''),
                ]);
            }
        }

        // Message de succès et redirection
        $_SESSION['success_message'] = "L'article a été modifié avec succès ! Vous pouvez le corriger une nouvelle fois si nécessaire :";
        UrlHelper::redirect("edit.html?id={$id}");
    }

    /**
     * Traite la suppression d'un article
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            UrlHelper::redirect('home.html');
            return;
        }

        $postData = $_POST;

        // Validation de l'ID
        if (!isset($postData['id']) || !is_numeric($postData['id'])) {
            $_SESSION['error_message'] = 'Il faut un identifiant valide pour supprimer un article.';
            UrlHelper::redirect('home.html');
            return;
        }

        $id = (int) $postData['id'];

        // Si l'utilisateur a demandé la suppression du match associé
        if (isset($postData['supprimerMatch']) && $postData['supprimerMatch'] === 'on') {
            $matchId = $this->articleModel->getMatchId($id);

            // Supprime le match s'il existe
            if ($matchId) {
                $this->matchModel->delete($matchId);
            }
        }

        // Supprime l'article
        $this->articleModel->delete($id);

        // Message de succès et redirection
        $_SESSION['success_message'] = "L'article a été définitivement supprimé !";
        UrlHelper::redirect('home.html');
    }

    /**
     * Affiche une page d'erreur 404
     */
    private function notFound(): void
    {
        http_response_code(404);
        $title = "Page non trouvée";
        $metadesc = "La page demandée n'existe pas";
        $this->render('errors/404', compact('title', 'metadesc'));
    }

    /**
     * Méthode utilitaire pour rendre une vue
     *
     * Cette méthode centralise le chargement des vues.
     * Elle inclut le layout (header/footer) et la vue demandée.
     *
     * @param string $view Le chemin de la vue (ex: 'articles/index')
     * @param array $data Les données à passer à la vue
     */
    private function render(string $view, array $data = []): void
    {
        // Extract transforme les clés du tableau en variables
        // ['title' => 'Test'] devient $title = 'Test'
        extract($data);

        // Démarre la capture de sortie (output buffering)
        // Tout ce qui sera affiché sera stocké en mémoire au lieu d'être envoyé
        ob_start();

        // Inclut le fichier de vue
        require_once __DIR__ . "/../Views/{$view}.php";

        // Récupère le contenu capturé et le stocke dans $content
        $content = ob_get_clean();

        // Inclut le layout qui affichera $content
        require_once __DIR__ . '/../Views/layouts/default.php';
    }
}
