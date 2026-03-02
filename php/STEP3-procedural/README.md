# PHP105 - Site d'actualités sportives (Programmation Procédurale)

## Description du projet

PHP105 est un site d'actualités sportives développé en **PHP procédural**. Il permet de gérer des articles de presse liés à des résultats sportifs avec les fonctionnalités CRUD (Create, Read, Update, Delete).

Ce projet est conçu comme un exemple pédagogique pour apprendre les fondamentaux du développement web en PHP avant de passer à la programmation orientée objet (POO) et aux frameworks MVC.

---

## Table des matières

1. [Architecture du projet](#architecture-du-projet)
2. [Qu'est-ce que la programmation procédurale ?](#quest-ce-que-la-programmation-procédurale-)
3. [Structure des dossiers](#structure-des-dossiers)
4. [Fonctionnement détaillé](#fonctionnement-détaillé)
5. [Technologies utilisées](#technologies-utilisées)
6. [Installation](#installation)
7. [Base de données](#base-de-données)
8. [Points pédagogiques clés](#points-pédagogiques-clés)

---

## Architecture du projet

```
php105/
├── common/                 # Fichiers communs partagés
│   ├── config.php         # Configuration globale
│   ├── db.php             # Connexion à la base de données
│   ├── dbArticle.php      # Récupération d'un article spécifique
│   ├── functions.php      # Fonctions réutilisables
│   ├── variables.php      # Variables globales (whitelist)
│   ├── header.php         # En-tête HTML commun
│   └── footer.php         # Pied de page HTML commun
├── pages/                  # Pages de l'application
│   ├── home.php           # Page d'accueil (liste des articles)
│   ├── article.php        # Affichage d'un article complet
│   ├── add.php            # Formulaire d'ajout
│   ├── addpost.php        # Traitement de l'ajout
│   ├── edit.php           # Formulaire de modification
│   ├── editpost.php       # Traitement de la modification
│   ├── deletepost.php     # Traitement de la suppression
│   └── ...
├── public/                 # Point d'entrée et ressources publiques
│   ├── index.php          # POINT D'ENTRÉE UNIQUE (Front Controller)
│   ├── .htaccess          # Réécriture d'URL (URL Rewriting)
│   └── assets/            # CSS, JS, images, fonts
└── README.md              # Ce fichier
```

---

## Qu'est-ce que la programmation procédurale ?

### Définition

La **programmation procédurale** est un paradigme de programmation qui structure le code en **séquences d'instructions** et en **fonctions** (aussi appelées procédures). C'est une approche linéaire où le code s'exécute de haut en bas.

### Caractéristiques principales

1. **Instructions séquentielles** : Le code s'exécute ligne par ligne
2. **Fonctions réutilisables** : On crée des fonctions pour éviter la répétition
3. **Variables globales** : Les données peuvent être partagées via des variables globales
4. **Includes** : On organise le code en fichiers séparés avec `require_once()` et `include()`
5. **Pas de classes ni d'objets** : Contrairement à la POO, tout est basé sur des fonctions

### Exemple dans ce projet

```php
// functions.php - Définition de fonctions réutilisables
function slugify($text) {
    // Transformation du texte en URL friendly
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text), '-'));
}

function truncateString($string, $length = 20) {
    // Coupe une chaîne à la longueur voulue
    if (strlen($string) > $length) {
        return substr($string, 0, $length) . '...';
    }
    return $string;
}
```

```php
// index.php - Utilisation de ces fonctions
require_once('../common/functions.php');

$titre = "Match de Football PSG - OM";
$slug = slugify($titre); // Retourne : "match-de-football-psg-om"
$description = truncateString($contenu, 100); // Coupe à 100 caractères
```

### Avantages pédagogiques

- Simple à comprendre pour les débutants
- Logique linéaire facile à suivre
- Moins d'abstractions qu'en POO
- Introduction aux concepts de base (fonctions, includes, sessions)

### Limites (pourquoi évoluer vers la POO ensuite)

- Difficile à maintenir sur de gros projets
- Code moins organisé et plus répétitif
- Variables globales peuvent créer des conflits
- Pas de réutilisation via l'héritage ou les interfaces

---

## Structure des dossiers

### 📁 `common/` - Fichiers partagés

Ce dossier contient tous les éléments **communs** utilisés dans plusieurs pages.

| Fichier | Rôle |
|---------|------|
| `config.php` | Configuration globale (BASE_URL, constantes) |
| `db.php` | Connexion PDO à la base de données MySQL |
| `dbArticle.php` | Requête SQL pour récupérer un article par ID |
| `functions.php` | Fonctions utilitaires réutilisables |
| `variables.php` | Whitelist des pages autorisées |
| `header.php` | Partie haute HTML (head, navbar) |
| `footer.php` | Partie basse HTML (scripts, fermeture body) |

**Principe** : Au lieu de répéter le même code HTML du header dans chaque page, on le place dans `header.php` et on l'inclut avec `include()`.

### 📁 `pages/` - Pages de l'application

Chaque fichier correspond à une **fonctionnalité** spécifique.

| Fichier | Description |
|---------|-------------|
| `home.php` | Liste tous les articles avec un résumé tronqué |
| `article.php` | Affiche un article complet avec son contenu entier |
| `add.php` | Formulaire HTML pour créer un nouvel article |
| `addpost.php` | Traite les données POST et insère en base de données |
| `edit.php` | Formulaire pré-rempli pour modifier un article existant |
| `editpost.php` | Traite les modifications et met à jour la BDD |
| `deletepost.php` | Supprime un article (et optionnellement le match associé) |

**Convention de nommage** :
- Fichiers sans suffixe (`add.php`, `edit.php`) : Affichent le **formulaire**
- Fichiers avec `post` (`addpost.php`, `editpost.php`) : **Traitent** les données

### 📁 `public/` - Point d'entrée public

C'est le **seul dossier accessible** depuis le navigateur (document root du serveur web).

| Fichier/Dossier | Rôle |
|-----------------|------|
| `index.php` | **Front Controller** - Point d'entrée unique de l'application |
| `.htaccess` | Réécriture d'URL pour des URLs SEO-friendly |
| `assets/` | Ressources statiques (CSS, JS, images, fonts) |

---

## Fonctionnement détaillé

### 1. Point d'entrée unique (Front Controller)

**Fichier : [public/index.php](public/index.php)**

Toutes les requêtes HTTP passent par ce fichier, qui agit comme un **routeur manuel**.

```php
session_start(); // Démarrage de la session

// Inclusion des fichiers communs
require_once('../common/db.php');         // Connexion BDD
require_once('../common/config.php');     // Configuration
require_once('../common/functions.php');  // Fonctions utilitaires
require_once('../common/variables.php');  // Whitelist des pages

// 1. Gestion des articles (avec ID)
if (isset($_GET['page']) && $_GET['page'] === 'articles' && isset($_GET['id'])) {
    include('../common/dbArticle.php');   // Récupère l'article
    include('../common/header.php');
    include("../pages/article.php");      // Affiche l'article
}
// 2. Pages whitelistées
elseif (isset($_GET['page']) && array_key_exists($_GET['page'], $whitelist)) {
    include('../common/header.php');
    include("../pages/" . $_GET['page'] . '.php');
}
// 3. Page d'accueil par défaut
elseif (!isset($_GET['page'])) {
    include('../common/header.php');
    include('../pages/home.php');
}
// 4. Erreur 404
else {
    include('../common/header.php');
    echo "<div class='alert alert-danger'>Vous êtes perdu ?</div>";
}

include('../common/footer.php'); // Footer commun à toutes les pages
```

**Principe pédagogique** :
- Une seule porte d'entrée pour toute l'application
- Structure conditionnelle simple (`if/elseif/else`)
- Inclusion dynamique des bonnes pages selon la requête

### 2. Réécriture d'URL (URL Rewriting)

**Fichier : [public/.htaccess](public/.htaccess)**

Apache transforme les URLs jolies en paramètres GET compréhensibles par PHP.

```apache
RewriteEngine On

# Articles : articles/12-psg-gagne-3-2.html → index.php?page=articles&id=12
RewriteRule ^articles/([0-9]+)-([a-zA-Z0-9-]+)\.html$ index.php?page=articles&id=$1 [L,QSA]

# Autres pages : add.html → index.php?page=add
RewriteCond %{REQUEST_URI} !index.php
RewriteRule (.*).html index.php?page=$1 [L,QSA]
```

**Exemple de transformation** :
- URL tapée : `http://localhost/articles/5-psg-victoire.html`
- URL réelle : `http://localhost/index.php?page=articles&id=5`

**Avantages** :
- URLs lisibles et mémorisables
- Meilleur référencement SEO
- Masque la structure interne PHP

### 3. Système de whitelist

**Fichier : [common/variables.php](common/variables.php)**

```php
$whitelist = array(
    'home' => "L'EQUIPE",
    'add' => "Ajouter un nouvel article",
    'edit' => "Modifier",
    'deletepost' => "Supprimer",
    'articles' => "Article"
);
```

**Sécurité** : Seules les pages listées dans ce tableau sont autorisées. Cela empêche l'inclusion de fichiers arbitraires (protection contre les attaques de type **Local File Inclusion**).

**Usage dans index.php** :
```php
if (array_key_exists($_GET['page'], $whitelist)) {
    // Page autorisée, on l'inclut
    include("../pages/" . $_GET['page'] . '.php');
}
```

### 4. Connexion à la base de données

**Fichier : [common/db.php](common/db.php)**

```php
try {
    // Connexion PDO (PHP Data Objects)
    $mysqlClient = new PDO(
        'mysql:host=localhost;dbname=sport_2000;charset=utf8',
        'root',
        ''
    );
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
```

**Points importants** :
- Utilise **PDO** (plus moderne et sécurisé que mysql_* ou mysqli)
- Variable `$mysqlClient` est **globale** (accessible partout après inclusion)
- Gestion des erreurs avec try/catch

### 5. Fonctions utilitaires

**Fichier : [common/functions.php](common/functions.php)**

#### `slugify($text)`

Transforme un titre en URL-friendly slug.

```php
slugify("PSG bat l'OM 3-2 !");
// Retourne : "psg-bat-l-om-3-2"
```

**Étapes** :
1. Remplace les caractères spéciaux par des tirets
2. Translitération (é → e, à → a)
3. Supprime tout ce qui n'est pas alphanumérique ou tiret
4. Convertit en minuscules

#### `createArticleUrl($id, $titre, $score = null)`

Génère une URL SEO-friendly pour un article.

```php
createArticleUrl(12, "PSG gagne", "3-2");
// Retourne : "articles/12-psg-gagne-3-2.html"
```

#### `truncateString($string, $length = 20)`

Coupe une chaîne à une longueur donnée.

```php
truncateString("Un très long texte...", 10);
// Retourne : "Un très lo..."
```

### 6. Inclusion des headers et footers

**Fichier : [common/header.php](common/header.php)**

Contient le début du HTML (doctype, head, navbar).

**Fichier : [common/footer.php](common/footer.php)**

Contient la fin du HTML (scripts JS, fermeture body).

**Utilisation** :
```php
include('../common/header.php');
// ... contenu de la page ...
include('../common/footer.php');
```

**Avantage** : Code HTML non répété, modification centralisée.

### 7. Opérations CRUD

#### CREATE - Ajouter un article

1. **[pages/add.php](pages/add.php)** : Affiche le formulaire
2. **[pages/addpost.php](pages/addpost.php)** : Traite le POST
   ```php
   $titre = $_POST['titre'];
   $contenu = $_POST['contenu'];

   $sqlQuery = 'INSERT INTO s2_articles_presse (titre, contenu, date_publication)
                VALUES (:titre, :contenu, NOW())';
   $statement = $mysqlClient->prepare($sqlQuery);
   $statement->execute([
       'titre' => $titre,
       'contenu' => $contenu
   ]);

   header('Location: index.php'); // Redirection
   ```

#### READ - Lire les articles

**[pages/home.php](pages/home.php)** : Liste tous les articles
```php
$sqlQuery = 'SELECT a.id, a.titre, a.contenu, a.date_publication, r.score
             FROM s2_articles_presse a
             LEFT JOIN s2_resultats_sportifs r ON a.match_id = r.id
             ORDER BY a.date_publication DESC';
$statement = $mysqlClient->prepare($sqlQuery);
$statement->execute();
$news = $statement->fetchAll();

foreach ($news as $new) {
    // Affichage de chaque article
}
```

**[pages/article.php](pages/article.php)** : Article complet (via `dbArticle.php`)

#### UPDATE - Modifier un article

1. **[pages/edit.php](pages/edit.php)** : Formulaire pré-rempli
2. **[pages/editpost.php](pages/editpost.php)** : Traite la modification
   ```php
   $sqlQuery = 'UPDATE s2_articles_presse
                SET titre = :titre, contenu = :contenu
                WHERE id = :id';
   $statement->execute([...]);
   ```

#### DELETE - Supprimer un article

**[pages/deletepost.php](pages/deletepost.php)** :
```php
$id = $_POST['id'];

// Suppression de l'article
$sqlQuery = 'DELETE FROM s2_articles_presse WHERE id = :id';
$statement = $mysqlClient->prepare($sqlQuery);
$statement->execute(['id' => $id]);

// Optionnel : suppression du match associé
if (isset($_POST['supprimerMatch'])) {
    // DELETE sur s2_resultats_sportifs
}

$_SESSION['success_message'] = "Article supprimé avec succès !";
header('Location: index.php');
```

---

## Technologies utilisées

- **PHP 7+** (langage procédural)
- **MySQL** (base de données)
- **PDO** (connexion sécurisée à la BDD)
- **Bootstrap 5** (framework CSS)
- **Apache** (.htaccess pour URL rewriting)
- **Sessions PHP** (messages de succès/erreur)

---

## Installation

### Prérequis

- Serveur Apache avec PHP 7+ (XAMPP, WAMP, MAMP...)
- MySQL
- mod_rewrite activé dans Apache

### Étapes

1. **Cloner ou télécharger le projet** dans le dossier du serveur web

2. **Configurer Apache** : Le document root doit pointer vers `php105/public/`

   Exemple de configuration (httpd.conf ou vhost) :
   ```apache
   DocumentRoot "C:/xampp/htdocs/php105/public"
   <Directory "C:/xampp/htdocs/php105/public">
       AllowOverride All
       Require all granted
   </Directory>
   ```

3. **Créer la base de données** : Importer le schéma SQL (voir section suivante)

4. **Configurer la connexion BDD** dans [common/db.php](common/db.php) :
   ```php
   $mysqlClient = new PDO(
       'mysql:host=localhost;dbname=sport_2000;charset=utf8',
       'votre_utilisateur',
       'votre_mot_de_passe'
   );
   ```

5. **Configurer l'URL de base** dans [common/config.php](common/config.php) :
   ```php
   define('BASE_URL', 'http://localhost');
   ```

6. **Accéder au site** : `http://localhost` (ou votre domaine local)

---

## Base de données

### Structure

**Table : `s2_articles_presse`**

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | INT (PK) | Identifiant unique |
| `titre` | VARCHAR(255) | Titre de l'article |
| `contenu` | TEXT | Contenu complet |
| `date_publication` | DATETIME | Date de publication |
| `match_id` | INT (FK) | ID du match lié (nullable) |

**Table : `s2_resultats_sportifs`**

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | INT (PK) | Identifiant unique |
| `score` | VARCHAR(10) | Score du match (ex: "3-2") |
| `lieu` | VARCHAR(255) | Lieu du match |
| ... | ... | Autres informations du match |

**Relation** : `LEFT JOIN` entre articles et résultats sportifs (un article peut être lié à un match).

---

## Points pédagogiques clés

### 1. Séparation des préoccupations

Même en procédural, on sépare la logique :
- **Connexion BDD** → `db.php`
- **Fonctions utilitaires** → `functions.php`
- **Affichage** → fichiers dans `pages/`
- **Template commun** → `header.php`, `footer.php`

### 2. Pattern Post-Redirect-Get (PRG)

Après un traitement POST (ajout, modification, suppression), on fait une **redirection** :
```php
// Traitement du formulaire
$statement->execute([...]);

// Redirection pour éviter le double-submit
header('Location: index.php');
exit;
```

### 3. Requêtes préparées (sécurité)

**Toujours** utiliser des requêtes préparées pour éviter les injections SQL :
```php
// MAUVAIS (vulnérable)
$query = "SELECT * FROM articles WHERE id = " . $_GET['id'];

// BON (sécurisé)
$query = "SELECT * FROM articles WHERE id = :id";
$statement = $mysqlClient->prepare($query);
$statement->execute(['id' => $_GET['id']]);
```

### 4. Validation côté serveur

Toujours valider les données reçues :
```php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Traitement
}
```

### 5. Sessions pour les messages

Utilisation de `$_SESSION` pour passer des messages entre requêtes :
```php
// Dans deletepost.php
$_SESSION['success_message'] = "Article supprimé !";

// Dans home.php
if (isset($_SESSION['success_message'])) {
    echo $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Suppression après affichage
}
```

### 6. Échappement des données (XSS)

Protection contre les attaques XSS lors de l'affichage :
```php
// htmlspecialchars() convertit les caractères spéciaux
echo htmlspecialchars($_SESSION['success_message']);
```

---

## Évolution vers le MVC

Ce projet est une excellente base pour comprendre les fondamentaux. L'étape suivante consiste à passer à une architecture **MVC** (Modèle-Vue-Contrôleur) avec la programmation orientée objet.

**Voir le projet `php105-mvc/`** pour la version MVC de cette application.

### Différences principales

| Procédural (php105) | MVC (php105-mvc) |
|---------------------|------------------|
| Fonctions | Classes et méthodes |
| index.php avec if/else | Routeur avec contrôleurs |
| Variables globales | Propriétés d'objets |
| Includes manuels | Autoloading |
| Requêtes SQL éparpillées | Modèles centralisés |

---

## Ressources

- [Documentation PHP officielle](https://www.php.net/)
- [PDO : PHP Data Objects](https://www.php.net/manual/fr/book.pdo.php)
- [Bootstrap 5](https://getbootstrap.com/)
- [mod_rewrite Apache](https://httpd.apache.org/docs/current/mod/mod_rewrite.html)

---

## Auteur

Projet pédagogique pour l'apprentissage du PHP procédural.

Pour toute question ou amélioration, n'hésitez pas à contribuer !
