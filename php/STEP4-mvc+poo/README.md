# Application Sport 2000 - Architecture MVC avec POO

## Description du projet

Application de gestion d'articles de presse sportifs avec résultats de matchs, entièrement reconstruite selon une architecture **MVC (Modèle-Vue-Contrôleur)** avec une approche **orientée objet (POO)**.

Cette refonte transforme une application procédurale en une application moderne, maintenable et évolutive.

---

## Architecture MVC - Explications pédagogiques

### Qu'est-ce que le pattern MVC ?

Le **MVC** est un patron de conception (design pattern) qui sépare l'application en trois composants distincts :

#### 1. **Modèle (Model)** - La couche des données
- **Rôle** : Gère les données et la logique métier
- **Responsabilités** :
  - Interagit avec la base de données (requêtes SQL)
  - Effectue les opérations CRUD (Create, Read, Update, Delete)
  - Valide les données selon les règles métier
- **Localisation** : `src/Models/`
- **Exemple** : `Article.php`, `Match.php`

#### 2. **Vue (View)** - La couche de présentation
- **Rôle** : Gère l'affichage et l'interface utilisateur
- **Responsabilités** :
  - Affiche les données reçues du contrôleur
  - Contient le code HTML/CSS
  - NE contient PAS de logique métier ni de requêtes SQL
- **Localisation** : `src/Views/`
- **Exemple** : `articles/index.php`, `articles/show.php`

#### 3. **Contrôleur (Controller)** - Le chef d'orchestre
- **Rôle** : Fait le lien entre les Modèles et les Vues
- **Responsabilités** :
  - Reçoit les requêtes de l'utilisateur
  - Appelle les méthodes des Modèles pour récupérer/modifier les données
  - Prépare les données pour les Vues
  - Appelle les Vues pour l'affichage
- **Localisation** : `src/Controllers/`
- **Exemple** : `ArticleController.php`

### Flux de traitement d'une requête

```
Utilisateur → URL (ex: articles/123-titre.html)
    ↓
.htaccess (redirige vers index.php)
    ↓
index.php (Front Controller)
    ↓
Router (analyse l'URL)
    ↓
Contrôleur (ex: ArticleController::show())
    ↓
Modèle (ex: Article::getById())
    ↓
Base de données (requête SQL)
    ↓
Modèle (retourne les données)
    ↓
Contrôleur (prépare les données)
    ↓
Vue (affiche les données en HTML)
    ↓
Navigateur de l'utilisateur
```

---

## Structure des dossiers

```
php105-mvc/
│
├── config/                      # Configuration de l'application
│   ├── config.php              # Configuration générale (session, constantes)
│   └── database.php            # Paramètres de connexion à la base de données
│
├── public/                      # Dossier public accessible par le web
│   ├── index.php               # Point d'entrée unique (Front Controller)
│   ├── .htaccess               # Règles de réécriture d'URL
│   └── assets/                 # Ressources statiques (CSS, JS, images)
│
├── src/                         # Code source de l'application
│   ├── Autoloader.php          # Chargement automatique des classes (PSR-4)
│   ├── Database.php            # Gestion de la connexion PDO (Singleton)
│   ├── Router.php              # Routeur de l'application
│   │
│   ├── Models/                 # Modèles (logique métier et données)
│   │   ├── Article.php         # Modèle Article (CRUD articles)
│   │   └── Match.php           # Modèle Match (CRUD résultats sportifs)
│   │
│   ├── Controllers/            # Contrôleurs (logique applicative)
│   │   └── ArticleController.php
│   │
│   ├── Views/                  # Vues (templates HTML)
│   │   ├── layouts/
│   │   │   └── default.php    # Layout principal (header/footer)
│   │   ├── articles/
│   │   │   ├── index.php      # Liste des articles
│   │   │   ├── show.php       # Affichage d'un article
│   │   │   ├── create.php     # Formulaire d'ajout
│   │   │   └── edit.php       # Formulaire de modification
│   │   └── errors/
│   │       └── 404.php        # Page d'erreur 404
│   │
│   └── Helpers/                # Classes utilitaires
│       ├── StringHelper.php    # Manipulation de chaînes
│       └── UrlHelper.php       # Gestion des URLs
│
└── README.md                    # Documentation du projet
```

---

## Concepts POO utilisés

### 1. **Classes et Objets**
- Chaque fichier PHP contient une classe
- Les classes regroupent des données (propriétés) et des comportements (méthodes)
- Exemple : La classe `Article` représente un article avec ses méthodes CRUD

### 2. **Namespace**
- Organisation logique des classes pour éviter les conflits de noms
- Tous nos code utilise le namespace `App`
- Exemple : `App\Models\Article`, `App\Controllers\ArticleController`

### 3. **Autoloading PSR-4**
- Chargement automatique des classes sans `require_once` partout
- Le namespace correspond à la structure de dossiers
- La classe `Autoloader` gère ce mécanisme

### 4. **Pattern Singleton** (classe Database)
- Garantit qu'une seule instance de connexion existe
- Économise les ressources
- Méthode statique `getInstance()` pour obtenir l'instance unique

### 5. **Encapsulation**
- Propriétés privées (`private`) pour protéger les données
- Accès aux données via des méthodes publiques (`public`)
- Exemple : `$db` est privé dans les modèles

### 6. **Séparation des responsabilités**
- Chaque classe a un rôle précis et unique
- Facilite la maintenance et les tests
- Exemple : `Article` gère les données, `ArticleController` gère la logique

---

## Fonctionnalités de l'application

### CRUD Articles
- ✅ **Create** : Ajouter un nouvel article (avec ou sans match)
- ✅ **Read** : Afficher la liste des articles et un article complet
- ✅ **Update** : Modifier un article et ses résultats de match
- ✅ **Delete** : Supprimer un article (avec option de supprimer le match)

### CRUD Résultats sportifs
- ✅ Créer un résultat de match (équipes, score, lieu, résumé)
- ✅ Modifier un résultat de match
- ✅ Supprimer un résultat de match
- ✅ Affichage des résultats associés aux articles

### Fonctionnalités techniques
- ✅ URLs SEO-friendly (ex: `articles/123-titre-du-match-3-2.html`)
- ✅ Messages flash (succès/erreur) via sessions
- ✅ Validation et sécurisation des données (anti-XSS)
- ✅ Gestion des erreurs 404
- ✅ Interface Bootstrap responsive

---

## Routes disponibles

| URL | Méthode | Contrôleur | Action | Description |
|-----|---------|------------|--------|-------------|
| `/home.html` | GET | ArticleController | index() | Liste tous les articles |
| `/articles/123-titre.html` | GET | ArticleController | show($id) | Affiche un article |
| `/add.html` | GET | ArticleController | create() | Formulaire d'ajout |
| `/addpost.html` | POST | ArticleController | store() | Traite l'ajout |
| `/edit.html?id=123` | GET | ArticleController | edit($id) | Formulaire de modification |
| `/editpost.html` | POST | ArticleController | update() | Traite la modification |
| `/deletepost.html` | POST | ArticleController | delete() | Traite la suppression |

---

## Installation et configuration

### Prérequis
- PHP 8.0 ou supérieur
- MySQL 5.7 ou supérieur
- Apache avec mod_rewrite activé

### Étapes d'installation

1. **Cloner ou copier le projet**
   ```bash
   cd /votre/dossier/web
   ```

2. **Configurer la base de données**
   - Ouvrir `config/database.php`
   - Modifier les paramètres de connexion si nécessaire :
     ```php
     'host' => 'localhost',
     'dbname' => 'sport_2000',
     'username' => 'root',
     'password' => '',
     ```

3. **Configurer le .htaccess**
   - Le fichier `public/.htaccess` est déjà configuré
   - Vérifie que `mod_rewrite` est activé dans Apache

4. **Accéder à l'application**
   - Ouvrir le navigateur : `http://localhost/chemin/vers/projet/public/`

---

## Comparaison : Avant (Procédural) vs Après (MVC/POO)

### Avant (Code procédural)

**Problèmes** :
- ❌ Code mélangé : HTML, PHP, SQL dans le même fichier
- ❌ Duplication de code (connexion DB, fonctions répétées)
- ❌ Difficile à maintenir et faire évoluer
- ❌ Difficile à tester
- ❌ Pas de réutilisabilité

**Exemple** :
```php
// pages/home.php (avant)
<?php
$mysqlClient = new PDO(...); // Connexion répétée partout
$query = "SELECT * FROM articles"; // SQL dans la vue
$articles = $mysqlClient->query($query);
?>
<html>
  <!-- HTML mélangé avec le PHP -->
</html>
```

### Après (Architecture MVC/POO)

**Avantages** :
- ✅ Code organisé et structuré
- ✅ Séparation des responsabilités (MVC)
- ✅ Réutilisable et maintenable
- ✅ Facile à tester unitairement
- ✅ Évolutif (ajout de fonctionnalités facilité)
- ✅ Suit les standards modernes (PSR-4, POO)

**Exemple** :
```php
// Contrôleur
public function index() {
    $articles = $this->articleModel->getAll(); // Logique séparée
    $this->render('articles/index', compact('articles'));
}

// Modèle
public function getAll() {
    return $this->db->query("SELECT * FROM articles")->fetchAll();
}

// Vue (HTML pur avec variables)
<?php foreach ($articles as $article): ?>
  <div><?= $article['titre'] ?></div>
<?php endforeach; ?>
```

---

## Bonnes pratiques appliquées

### Sécurité
- ✅ Requêtes préparées (PDO) pour éviter les injections SQL
- ✅ Échappement des données avec `htmlspecialchars()`
- ✅ Nettoyage des entrées utilisateur avec `strip_tags()`
- ✅ Validation des données côté serveur

### Code propre
- ✅ Commentaires pédagogiques détaillés
- ✅ Noms de variables explicites en français
- ✅ Respect des conventions PSR (PSR-1, PSR-4)
- ✅ Une responsabilité par classe/méthode

### Performance
- ✅ Singleton pour la connexion DB (une seule connexion)
- ✅ Lazy loading des classes (autoloader)
- ✅ Requêtes optimisées avec LEFT JOIN

---

## Évolutions possibles

### Fonctionnalités
- [ ] Système d'authentification utilisateurs
- [ ] Gestion des catégories d'articles
- [ ] Upload d'images pour les articles
- [ ] Système de commentaires
- [ ] Recherche et filtres avancés
- [ ] Pagination des articles

### Technique
- [ ] Utiliser un ORM (Eloquent, Doctrine)
- [ ] Ajouter un système de cache
- [ ] Mettre en place des tests unitaires (PHPUnit)
- [ ] Utiliser un moteur de templates (Twig)
- [ ] Ajouter une API REST
- [ ] Gestion des migrations de base de données

---

## Concepts avancés pour aller plus loin

### Design Patterns
- **Singleton** : Une seule instance de Database
- **Front Controller** : Point d'entrée unique (index.php)
- **MVC** : Séparation Modèle-Vue-Contrôleur
- **Repository Pattern** : Les modèles agissent comme des repositories

### Principes SOLID
- **S** (Single Responsibility) : Une classe = une responsabilité
- **O** (Open/Closed) : Ouvert à l'extension, fermé à la modification
- **L** (Liskov Substitution) : Les classes dérivées sont substituables
- **I** (Interface Segregation) : Interfaces spécifiques plutôt que générales
- **D** (Dependency Inversion) : Dépendre des abstractions, pas des implémentations

---

## Ressources pédagogiques

### Documentation PHP
- [PHP.net - POO](https://www.php.net/manual/fr/language.oop5.php)
- [PHP.net - PDO](https://www.php.net/manual/fr/book.pdo.php)
- [PSR-4 Autoloading](https://www.php-fig.org/psr/psr-4/)

### Tutoriels MVC
- [Grafikart - MVC PHP](https://grafikart.fr)
- [OpenClassrooms - Architecture MVC](https://openclassrooms.com)

### Livres recommandés
- "PHP Objects, Patterns, and Practice" - Matt Zandstra
- "Clean Code" - Robert C. Martin

---

## Auteur et licence

**Projet pédagogique** - Refonte d'une application procédurale en MVC/POO

Code commenté de manière extensive à des fins d'apprentissage.

---

## Conclusion

Cette refonte démontre comment transformer une application procédurale en une architecture moderne et maintenable. L'architecture MVC avec POO offre :

- 🎯 Une meilleure organisation du code
- 🔧 Une maintenance facilitée
- 🚀 Une évolution plus simple
- 👥 Une collaboration d'équipe améliorée
- 📚 Un code plus lisible et compréhensible

**Bonne découverte de l'architecture MVC avec PHP orienté objet !**
