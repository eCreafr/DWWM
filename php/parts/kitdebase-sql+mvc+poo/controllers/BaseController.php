<?php
/**
 * Classe BaseController
 * Contrôleur de base dont héritent tous les autres contrôleurs
 * Contient les méthodes communes à tous les contrôleurs
 */
class BaseController {

    /**
     * Démarre la session si elle n'est pas déjà active
     */
    protected function demarrerSession() {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Vérifie si un administrateur est connecté
     * @return bool True si connecté, false sinon
     */
    protected function estConnecte() {
        $this->demarrerSession();
        return isset($_SESSION['connecte']) && $_SESSION['connecte'] === true;
    }

    /**
     * Redirige vers la page de login si non connecté
     */
    protected function verifierAuthentification() {
        if(!$this->estConnecte()) {
            $_SESSION['erreur'] = "Vous devez être connecté pour accéder à cette page.";
            $this->rediriger('auth/login');
        }
    }

    /**
     * Charge une vue avec des données optionnelles
     * @param string $vue Nom du fichier de vue (sans extension)
     * @param array $donnees Tableau de données à passer à la vue
     */
    protected function chargerVue($vue, $donnees = []) {
        // Extrait les données du tableau pour les rendre accessibles comme variables
        // Exemple : ['nom' => 'Jean'] devient $nom = 'Jean'
        extract($donnees);

        // Chemin vers le fichier de vue
        $cheminVue = __DIR__ . '/../views/' . $vue . '.php';

        // Vérifie si la vue existe
        if(file_exists($cheminVue)) {
            // Inclusion de la vue
            require_once $cheminVue;
        } else {
            // Erreur si la vue n'existe pas
            die("La vue '$vue' n'existe pas.");
        }
    }

    /**
     * Redirige vers une autre page
     * @param string $url URL de destination (relative)
     */
    protected function rediriger($url) {
        header('Location: ' . BASE_URL . $url);
        exit();
    }

    /**
     * Retourne les données en JSON (pour les appels AJAX)
     * @param mixed $donnees Données à encoder en JSON
     */
    protected function json($donnees) {
        header('Content-Type: application/json');
        echo json_encode($donnees);
        exit();
    }
}