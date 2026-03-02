# 🏆 STEP 2 - Site Sportif avec Authentification

## 📖 Vue d'ensemble du projet

Bienvenue dans le **STEP 2** ! Ce projet est une application web PHP permettant de gérer un site d'actualités sportives avec un système d'authentification basique. Les utilisateurs peuvent se connecter pour accéder à du contenu premium (articles et résultats de matchs).

### 🎯 Objectifs pédagogiques

Ce projet vous permettra de maîtriser :
- ✅ La structure modulaire d'un site PHP
- ✅ Le système de sessions PHP pour gérer l'authentification
- ✅ L'inclusion de fichiers partiels (header, footer, etc.)
- ✅ La connexion et les requêtes à une base de données MySQL avec PDO
- ✅ Les formulaires HTML et leur traitement en PHP
- ✅ L'affichage conditionnel de contenu
- ✅ Les boucles pour afficher des données dynamiques
- ✅ Les bonnes pratiques de sécurité (htmlspecialchars, validation, etc.)

---

## 📁 Structure du projet

```
STEP2-/
│
├── index.php                  # Page d'accueil avec aperçu des articles
├── php102-resultats.php       # Page des résultats sportifs (réservée aux abonnés)
├── php102-contact.php         # Page de contact avec formulaire
│
├── login.php                  # Composant : Formulaire de connexion / Modale de bienvenue
├── submit-login.php           # Traitement du formulaire de connexion
├── submit-contact.php         # Traitement du formulaire de contact (simulation)
│
├── head.php                   # Composant : Balises <head> communes
├── header.php                 # Composant : En-tête du site (logo + menu)
├── footer.php                 # Composant : Pied de page avec déconnexion
│
├── db.php                     # Configuration et connexion à la base de données
├── functions.php              # Fonctions utilitaires et requêtes SQL
│
└── README.md                  # Ce fichier
```

---

## 🗄️ Base de données

### Configuration requise

Le projet utilise une base de données MySQL nommée `sport_2000` avec les tables suivantes :

#### Table `s2_abonnes`
Stocke les utilisateurs inscrits au site.

```sql
CREATE TABLE s2_abonnes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    mail VARCHAR(255) UNIQUE,
    pswd VARCHAR(255)
);
```

**Exemples de données :**
```sql
INSERT INTO s2_abonnes (nom, prenom, mail, pswd) VALUES
('Dupont', 'Jean', 'jean.dupont@example.com', 'password123'),
('Martin', 'Sophie', 'sophie.martin@example.com', 'password456');
```

⚠️ **Note de sécurité** : Dans ce projet pédagogique, les mots de passe sont stockés en clair. **EN PRODUCTION, utilisez TOUJOURS `password_hash()` et `password_verify()` !**

#### Table `s2_resultats_sportifs`
Stocke les résultats des matchs de football.

```sql
CREATE TABLE s2_resultats_sportifs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    equipe1 VARCHAR(100),
    equipe2 VARCHAR(100),
    score VARCHAR(10),
    resume TEXT,
    lieu VARCHAR(255),
    date_match DATE
);
```

**Exemples de données :**
```sql
INSERT INTO s2_resultats_sportifs (equipe1, equipe2, score, resume, lieu, date_match) VALUES
('PSG', 'Lyon', '3-1', 'Victoire éclatante du PSG !', 'Parc des Princes', '2024-10-15'),
('Marseille', 'Monaco', '2-2', 'Match nul spectaculaire.', 'Vélodrome', '2024-10-14');
```

#### Table `s2_articles_presse`
Stocke les articles de presse liés aux matchs.

```sql
CREATE TABLE s2_articles_presse (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(255),
    contenu TEXT,
    date_publication DATETIME,
    match_id INT,
    FOREIGN KEY (match_id) REFERENCES s2_resultats_sportifs(id)
);
```

---

## 🚀 Installation et configuration

### 1. Prérequis

- **Serveur web** avec PHP 7.4+ (XAMPP, WAMP, MAMP, ou serveur local)
- **MySQL** 5.7+ ou MariaDB
- **Navigateur web** moderne

### 2. Configuration de la base de données

1. Créez la base de données `sport_2000`
2. Exécutez les scripts SQL ci-dessus pour créer les tables
3. Insérez quelques données de test

### 3. Configuration du projet

Modifiez le fichier `db.php` avec vos identifiants MySQL :

```php
$mysqlClient = new PDO(
    'mysql:host=localhost;dbname=sport_2000;charset=utf8',
    'VOTRE_UTILISATEUR',    // Remplacez par votre nom d'utilisateur MySQL
    'VOTRE_MOT_DE_PASSE'    // Remplacez par votre mot de passe MySQL
);
```

### 4. Lancement du projet

1. Placez le dossier `STEP2-` dans votre répertoire web (ex: `htdocs` pour XAMPP)
2. Démarrez votre serveur web et MySQL
3. Accédez à `http://localhost/STEP2-/index.php` dans votre navigateur

---

## 🧩 Architecture du code

### Principe de modularité

Ce projet utilise une **architecture modulaire** où chaque composant a une responsabilité unique :

```
┌─────────────────────────────────────────┐
│           Page principale               │
│          (index.php, etc.)              │
│                                         │
│  ┌─────────────────────────────────┐   │
│  │       head.php                  │   │
│  │  - Session start               │   │
│  │  - DB connection               │   │
│  │  - CSS imports                 │   │
│  └─────────────────────────────────┘   │
│                                         │
│  ┌─────────────────────────────────┐   │
│  │       header.php                │   │
│  │  - Logo                        │   │
│  │  - Navigation menu             │   │
│  └─────────────────────────────────┘   │
│                                         │
│  ┌─────────────────────────────────┐   │
│  │    Contenu spécifique          │   │
│  │    de la page                  │   │
│  └─────────────────────────────────┘   │
│                                         │
│  ┌─────────────────────────────────┐   │
│  │       footer.php                │   │
│  │  - Copyright                   │   │
│  │  - Logout link                 │   │
│  └─────────────────────────────────┘   │
└─────────────────────────────────────────┘
```

### Flux d'authentification

```
┌──────────────────┐
│  Utilisateur     │
│  visite le site  │
└────────┬─────────┘
         │
         v
┌──────────────────┐      Non connecté     ┌──────────────────┐
│   index.php      ├────────────────────────>│   login.php      │
│                  │                         │  (formulaire)    │
└────────┬─────────┘                         └────────┬─────────┘
         │                                            │
         │ Connecté                                   │ Soumission
         │                                            v
         │                                   ┌──────────────────┐
         │                                   │ submit-login.php │
         │                                   │  - Validation    │
         │                                   │  - Auth check    │
         │                                   │  - Session save  │
         │                                   └────────┬─────────┘
         │                                            │
         │ ◄──────────────────────────────────────────┘
         │            Redirection après login
         v
┌──────────────────┐
│  Contenu premium │
│  visible         │
└──────────────────┘
```

---

## 📄 Description détaillée des fichiers

### 🌐 Pages principales

#### `index.php`
**Page d'accueil du site**

- Affiche un message de bienvenue personnalisé si l'utilisateur est connecté
- Montre 3 cartes d'aperçu visibles par tous
- Intègre le formulaire de connexion pour les non-connectés
- Affiche 6 cartes supplémentaires pour les abonnés connectés

**Concepts clés :**
```php
// Affichage conditionnel
<?php if (isset($_SESSION['LOGGED_USER'])) : ?>
    <!-- Contenu réservé -->
<?php endif; ?>
```

#### `php102-resultats.php`
**Page des résultats sportifs (accès réservé)**

- Affiche tous les résultats de matchs depuis la base de données
- Utilise une boucle `foreach` pour parcourir les matchs
- Bloque l'accès aux utilisateurs non connectés
- Affiche les équipes, scores et résumés des matchs

**Concepts clés :**
```php
// Boucle d'affichage
<?php foreach ($Matches as $match) : ?>
    <div><?php echo $match['equipe1']; ?> vs <?php echo $match['equipe2']; ?></div>
<?php endforeach; ?>
```

#### `php102-contact.php`
**Page de contact avec formulaire**

- Formulaire avec champs email et message
- Pré-remplit l'email de l'utilisateur connecté
- Envoie les données vers `submit-contact.php`

---

### 🔐 Système d'authentification

#### `login.php`
**Composant intelligent qui affiche soit le formulaire, soit une modale**

**Cas 1 - Non connecté :**
- Affiche un formulaire de connexion
- Gère les messages d'erreur de connexion
- Valide l'email côté client (HTML5)

**Cas 2 - Connecté :**
- Affiche une modale de bienvenue (une seule fois par session)
- Utilise Bootstrap JavaScript pour l'animation

#### `submit-login.php`
**Traitement de la connexion**

Étapes du processus :
1. Récupération des données POST (`email` et `mdp`)
2. Validation du format de l'email avec `filter_var()`
3. Recherche de l'utilisateur dans la base de données
4. Comparaison du mot de passe (⚠️ en clair, pédagogique uniquement)
5. Stockage des infos utilisateur en session si succès
6. Redirection vers l'accueil dans tous les cas

**Concepts clés :**
```php
// Stockage en session après authentification réussie
$_SESSION['LOGGED_USER'] = [
    'email' => $user['mail'],
    'nom' => $user['nom'],
    'prenom' => $user['prenom'],
];
```

#### `submit-contact.php`
**Traitement du formulaire de contact**

- Récupère les données du formulaire
- Affiche une page de confirmation stylisée
- Utilise `htmlspecialchars()` pour la sécurité XSS
- Utilise `nl2br()` pour formater les retours à la ligne

⚠️ **Note :** Ce fichier simule uniquement l'envoi. En production, utilisez `mail()` ou PHPMailer.

---

### 🧱 Composants réutilisables

#### `head.php`
**Initialisation commune à toutes les pages**

- Démarre la session PHP avec `session_start()`
- Inclut la connexion à la base de données
- Charge les fonctions utilitaires
- Importe Bootstrap CSS

#### `header.php`
**En-tête du site**

- Logo cliquable vers l'accueil
- Affichage du prénom de l'utilisateur si connecté
- Date du jour
- Menu de navigation horizontal

#### `footer.php`
**Pied de page du site**

- Logo
- Lien de déconnexion (visible uniquement si connecté)
- Informations de copyright

---

### ⚙️ Configuration et utilitaires

#### `db.php`
**Connexion à la base de données**

- Utilise PDO (PHP Data Objects) pour la connexion MySQL
- Gère les erreurs de connexion avec try/catch
- Configure l'encodage UTF-8

**Structure :**
```php
try {
    $mysqlClient = new PDO('mysql:host=...;dbname=...;charset=utf8', 'user', 'pass');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
```

#### `functions.php`
**Fonctions et requêtes SQL**

Contient :
1. **Fonctions utilitaires** pour les matchs (`isActiveMatch`, `getActiveMatches`)
2. **Gestion des cookies** pour stocker le prénom
3. **Fonction de redirection** (`redirectToUrl`)
4. **Gestion de la déconnexion** (détection de `?action=logout`)
5. **Requêtes SQL** pour récupérer les données :
   - Articles de presse avec jointure
   - Liste des abonnés
   - Résultats sportifs

---

## 🔒 Sécurité

### ✅ Bonnes pratiques implémentées

1. **Protection XSS** : Utilisation de `htmlspecialchars()` sur toutes les données affichées
2. **Requêtes préparées** : Utilisation de `prepare()` et `execute()` pour éviter les injections SQL
3. **Validation d'email** : `filter_var($email, FILTER_VALIDATE_EMAIL)`
4. **Sessions sécurisées** : Stockage des infos utilisateur sans le mot de passe

### ⚠️ Points d'amélioration pour la production

Ce projet est **pédagogique**. En production, vous devriez :

1. **Hasher les mots de passe** :
```php
// Lors de l'inscription
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Lors de la connexion
if (password_verify($inputPassword, $hashedPassword)) {
    // OK
}
```

2. **Protéger contre les attaques par force brute** :
   - Limiter le nombre de tentatives de connexion
   - Implémenter un système de CAPTCHA
   - Ajouter un délai entre les tentatives

3. **Utiliser HTTPS** pour chiffrer les communications

4. **Valider toutes les données côté serveur** (ne jamais faire confiance au client)

5. **Stocker les identifiants DB dans un fichier de configuration sécurisé** :
```php
// config.php (à exclure du dépôt Git)
define('DB_HOST', 'localhost');
define('DB_NAME', 'sport_2000');
define('DB_USER', 'username');
define('DB_PASS', 'password');
```

6. **Implémenter la régénération d'ID de session** :
```php
session_start();
session_regenerate_id(true); // Après connexion
```

---

## 🎓 Concepts PHP à retenir

### 1. Sessions PHP

Les sessions permettent de conserver des informations entre les pages :

```php
// Démarrer une session (à faire sur chaque page)
session_start();

// Stocker une donnée
$_SESSION['username'] = 'Alice';

// Lire une donnée
echo $_SESSION['username'];

// Vérifier l'existence
if (isset($_SESSION['username'])) {
    // Faire quelque chose
}

// Détruire la session
session_unset();
session_destroy();
```

### 2. Inclusion de fichiers

```php
// Include : continue même si le fichier n'existe pas
include('fichier.php');

// Require : arrête le script si le fichier n'existe pas
require('fichier.php');

// _once : inclut une seule fois (évite les duplications)
require_once('fichier.php');
```

### 3. PDO (PHP Data Objects)

```php
// Connexion
$pdo = new PDO('mysql:host=localhost;dbname=test', 'user', 'pass');

// Requête préparée (sécurisée)
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
$stmt->execute(['email' => $email]);
$result = $stmt->fetchAll();
```

### 4. Affichage conditionnel

```php
// Syntaxe alternative (recommandée dans le HTML)
<?php if ($condition) : ?>
    <p>HTML à afficher</p>
<?php else : ?>
    <p>Autre HTML</p>
<?php endif; ?>
```

### 5. Sécurisation de l'affichage

```php
// Toujours échapper les données utilisateur
echo htmlspecialchars($userInput);

// Opérateur null coalescing (PHP 7+)
$value = $_POST['field'] ?? 'valeur par défaut';
```

---

## 📝 Exercices pour aller plus loin

### Niveau débutant

1. **Modifier le style** : Changez les couleurs Bootstrap pour personnaliser le site
2. **Ajouter un champ** : Ajoutez un numéro de téléphone dans le formulaire de contact
3. **Créer une page** : Créez une page "À propos" accessible depuis le menu

### Niveau intermédiaire

4. **Pagination** : Limitez l'affichage à 3 matchs par page avec navigation
5. **Système de rôles** : Ajoutez un champ `role` (admin/user) dans la table abonnés
6. **Recherche** : Créez un formulaire de recherche pour filtrer les matchs par équipe
7. **Hachage de mots de passe** : Implémentez `password_hash()` et `password_verify()`

### Niveau avancé

8. **Système de favoris** : Permettre aux utilisateurs de marquer des matchs en favoris
9. **Upload d'images** : Permettre l'upload d'un logo d'équipe pour les matchs
10. **API REST** : Créez une API JSON pour récupérer les résultats (GET `/api/matches`)
11. **Commentaires** : Ajoutez un système de commentaires sur les articles
12. **Notifications** : Envoyez un vrai email de confirmation avec PHPMailer

---

## 🐛 Débogage et problèmes courants

### Erreur : "Session already started"
**Solution :** Vérifiez que `session_start()` n'est appelé qu'une seule fois par page.

### Erreur de connexion MySQL
**Solution :** Vérifiez que :
- MySQL est démarré
- Les identifiants dans `db.php` sont corrects
- La base de données existe

### Formulaire de connexion ne fonctionne pas
**Solution :** Vérifiez que :
- Les attributs `name` des inputs correspondent aux clés utilisées dans `submit-login.php`
- Un utilisateur existe dans la table `s2_abonnes`

### Bootstrap ne charge pas
**Solution :** Vérifiez le chemin vers `bootstrap.min.css` dans `head.php`

---

## 📚 Ressources supplémentaires

### Documentation officielle

- [PHP Manual](https://www.php.net/manual/fr/)
- [PDO Tutorial](https://www.php.net/manual/fr/book.pdo.php)
- [Bootstrap Documentation](https://getbootstrap.com/docs/)

### Tutoriels recommandés

- [W3Schools PHP](https://www.w3schools.com/php/)
- [MDN Web Docs - Formulaires](https://developer.mozilla.org/fr/docs/Learn/Forms)
- [OWASP - Sécurité Web](https://owasp.org/www-project-top-ten/)

---

## 👨‍💻 Auteur et licence

**Projet pédagogique** - Formation développement web PHP

**Objectif :** Apprendre les bases de PHP avec un projet concret et commenté.

---

## 🎉 Félicitations !

Vous avez maintenant toutes les clés pour comprendre et modifier ce projet. N'hésitez pas à :
- Lire attentivement les commentaires dans chaque fichier
- Expérimenter en modifiant le code
- Créer vos propres fonctionnalités
- Poser des questions à votre formateur

**Bon codage !** 🚀
