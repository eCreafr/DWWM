# Application Sport 2000 - MVC + POO + Composer + 2FA

## Description du projet

Application web de gestion d'articles de presse sportifs avec résultats de matchs, construite selon une architecture **MVC (Modèle-Vue-Contrôleur)** moderne avec **programmation orientée objet (POO)**.

Ce projet pédagogique démontre les bonnes pratiques de développement PHP moderne, incluant l'utilisation de Composer, l'autoloading PSR-4, et l'authentification à deux facteurs (2FA).

---

## Présentation du projet

### Architecture et Stack technique

**Pattern architectural** : MVC (Modèle-Vue-Contrôleur)

**Langage** : PHP 8.0+

**Base de données** : MySQL / MariaDB

**Frontend** : Bootstrap 5, HTML5, CSS3

**Gestionnaire de dépendances** : Composer

### Dépendances Composer utilisées

Le projet utilise les packages suivants (définis dans `composer.json`) :

**Production** :
- `egulias/email-validator` (^4.0) - Validation avancée des adresses email
- `pragmarx/google2fa` (^9.0) - Authentification à deux facteurs (2FA) compatible Google Authenticator

**Développement** :
- `phpunit/phpunit` (^12.5) - Framework de tests unitaires

**Autoloading** : PSR-4 (`App\` → `src/`)

### Structure des dossiers

```
STEP5-mvc+poo+composer/
│
├── config/                      # Configuration de l'application
│   ├── config.php              # Configuration générale (session, timezone)
│   ├── database.php            # Paramètres de connexion BDD
│   └── database.example.php    # Exemple de configuration BDD
│
├── public/                      # Dossier public accessible par le web
│   ├── index.php               # Point d'entrée unique (Front Controller)
│   ├── .htaccess               # Règles de réécriture d'URL
│   └── assets/                 # Ressources statiques
│       ├── css/                # Styles (Bootstrap, custom)
│       ├── js/                 # Scripts JavaScript
│       └── img/                # Images
│
├── src/                         # Code source de l'application
│   ├── Autoloader.php          # Chargement automatique des classes (PSR-4)
│   ├── Database.php            # Connexion PDO (Pattern Singleton)
│   ├── Router.php              # Routeur de l'application
│   │
│   ├── Controllers/            # Contrôleurs (logique applicative)
│   │   ├── ArticleController.php  # CRUD articles
│   │   └── AuthController.php     # Authentification et 2FA
│   │
│   ├── Models/                 # Modèles (accès aux données)
│   │   ├── Article.php         # Modèle Article
│   │   ├── Match.php           # Modèle Match (résultats sportifs)
│   │   └── User.php            # Modèle User (avec méthodes 2FA)
│   │
│   ├── Views/                  # Vues (templates HTML)
│   │   ├── layouts/
│   │   │   └── default.php    # Layout principal
│   │   ├── articles/          # Vues articles
│   │   ├── auth/              # Vues authentification et 2FA
│   │   └── errors/            # Pages d'erreur
│   │
│   └── Helpers/                # Classes utilitaires
│       ├── StringHelper.php    # Manipulation de chaînes
│       ├── UrlHelper.php       # Gestion des URLs
│       └── TwoFactorHelper.php # Gestion 2FA
│
├── tests/                       # Tests unitaires PHPUnit
│
├── vendor/                      # Dépendances Composer (auto-généré)
│
├── install.php                  # Script d'installation automatique
├── composer.json                # Configuration Composer
├── composer.phar                # Exécutable Composer (inclus pour OVH)
├── sport_2000.sql              # Script SQL de la base de données
└── README.md                    # Ce fichier
```

### Fonctionnalités principales

**Gestion des articles** :
- Création, lecture, modification, suppression (CRUD)
- Association avec des résultats de matchs sportifs
- URLs SEO-friendly (ex: `articles/123-titre-du-match.html`)

**Gestion des résultats sportifs** :
- Création et modification de résultats de matchs
- Affichage du score, équipes, lieu, résumé
- Liaison avec les articles de presse

**Authentification sécurisée** :
- Système de connexion/déconnexion
- Validation des emails
- Authentification à deux facteurs (2FA)
- Compatible Google Authenticator, Authy, Microsoft Authenticator

**Fonctionnalités techniques** :
- Architecture MVC avec séparation des responsabilités
- Autoloading PSR-4 avec Composer
- Requêtes préparées PDO (sécurité anti-injection SQL)
- Messages flash via sessions
- Interface responsive avec Bootstrap 5
- Gestion des erreurs 404

---

## Installation sur WAMP (développement local)

### Prérequis

- WAMP/XAMPP avec PHP 8.0 minimum
- MySQL/MariaDB
- Extension PDO et PDO_MySQL activées

### Important : Limitation connue de WAMP

Sur WAMP/XAMPP en local, Apache a une limitation qui empêche l'exécution de processus longs (comme `composer install`) via l'interface web.

**Solution recommandée** : Installer Composer en ligne de commande avant de lancer le script d'installation web.

### Étapes d'installation

**Étape 1 : Copier les fichiers**

```bash
# Décompresser le projet dans
C:\wamp64\www\sport2000\
```

**Étape 2 : Installer les dépendances Composer**

Ouvrir un terminal (PowerShell ou CMD) :

```bash
cd C:\wamp64\www\sport2000

# Installer les dépendances
php composer.phar install
```

Cela va créer le dossier `vendor/` avec :
- `egulias/email-validator`
- `pragmarx/google2fa`
- `phpunit/phpunit`
- L'autoloader PSR-4

**Étape 3 : Lancer l'installation automatique**

Ouvrir le navigateur et accéder à :

```
http://localhost/sport2000/install.php
```

Le script va :
1. ✅ Détecter que `vendor/` existe déjà (installation Composer ignorée)
2. ✅ Vérifier les prérequis système
3. ✅ Demander les paramètres MySQL
4. ✅ Créer et importer la base de données `sport_2000`
5. ✅ Exécuter les tests PHPUnit
6. ✅ Nettoyer les fichiers d'installation

**Étape 4 : Accéder à l'application**

```
http://localhost/sport2000/public/
```

### Configuration manuelle de la base de données (optionnel)

Si vous préférez configurer manuellement :

1. Créer la base de données :
   ```sql
   CREATE DATABASE sport_2000 CHARACTER SET utf8 COLLATE utf8_general_ci;
   ```

2. Importer le fichier SQL :
   ```bash
   mysql -u root -p sport_2000 < sport_2000.sql
   ```

3. Configurer `config/database.php` :
   ```php
   return [
       'host' => 'localhost',
       'dbname' => 'sport_2000',
       'username' => 'root',
       'password' => '',
       'charset' => 'utf8',
   ];
   ```

---

## Installation sur OVH (production)

### Prérequis

- Hébergement OVH avec PHP 8.0 minimum
- Accès FTP (FileZilla, Cyberduck, etc.)
- Base de données MySQL créée dans le Manager OVH

### Installation automatique (recommandée)

**Étape 1 : Upload des fichiers**

1. Connectez-vous en FTP à votre hébergement OVH
2. Uploadez tous les fichiers dans `www/` ou `public_html/`
3. Assurez-vous que `composer.phar` est bien présent

**Étape 2 : Lancer l'installation**

Accédez à :
```
https://votre-domaine.com/install.php
```

Le script va automatiquement :
1. ✅ Vérifier les prérequis
2. ✅ Installer Composer automatiquement (téléchargement si nécessaire)
3. ✅ Installer les dépendances (email-validator, google2fa)
4. ✅ Créer et importer la base de données
5. ✅ Finaliser l'installation

**Important** : Sur OVH, l'installation est **100% automatique** sans intervention manuelle !

**Étape 3 : Accéder au site**

```
https://votre-domaine.com/public/
```

### Informations importantes pour OVH

**PHPUnit et tests** :

Sur OVH, l'installation des dépendances se fait avec l'option `--no-dev`, ce qui signifie que **PHPUnit ne sera pas installé** en production. C'est normal et recommandé pour :
- Réduire la taille du projet
- Améliorer les performances
- Éviter d'installer des outils de développement en production

Les tests peuvent être exécutés en local avant le déploiement.

**Version PHP** :

Vérifiez dans le Manager OVH que PHP 8.0 (ou supérieur) est activé pour votre hébergement.

**Permissions** :

Les permissions sont généralement correctes par défaut sur OVH. Si nécessaire :
```bash
chmod 755 public/assets/img/articles
chmod 644 config/database.php
```

### Configuration manuelle sur OVH (si l'auto-install échoue)

**1. Créer la base de données**

Dans le Manager OVH :
- Section **Hébergements** → **Bases de données**
- Créer une nouvelle base MySQL
- Notez : hôte, nom de la base, utilisateur, mot de passe

**2. Configurer `config/database.php`**

```php
return [
    'host' => 'mysql51-xx.perso',  // Fourni par OVH
    'dbname' => 'votre_base',
    'username' => 'votre_user',
    'password' => 'votre_mdp',
    'charset' => 'utf8',
];
```

**3. Importer la base de données**

Via phpMyAdmin (accessible depuis le Manager OVH) :
- Sélectionner votre base
- Onglet **Importer**
- Choisir `sport_2000.sql`
- Cliquer **Exécuter**

**4. Installer Composer via SSH** (si accès SSH disponible)

```bash
ssh votre_user@ssh.cluster0XX.hosting.ovh.net
cd www
curl -sS https://getcomposer.org/installer | php
php composer.phar install --no-dev --optimize-autoloader
```

---

## Utilisation de l'authentification 2FA

L'application inclut un système d'authentification à deux facteurs (2FA) pour renforcer la sécurité des comptes utilisateurs.

### Qu'est-ce que le 2FA ?

Le 2FA (Two-Factor Authentication) ajoute une couche de sécurité supplémentaire en demandant, en plus du mot de passe :
- Un code à 6 chiffres généré par une application d'authentification
- Ce code change toutes les 30 secondes

### Applications compatibles

- Google Authenticator (Android/iOS)
- Microsoft Authenticator (Android/iOS)
- Authy (Android/iOS/Desktop)
- 1Password (Multi-plateforme)

### Comment activer le 2FA

**Étape 1 : Se connecter**

Connectez-vous avec vos identifiants classiques :
- Email : `raphael.lang@gmail.com`
- Mot de passe : `123`

**Étape 2 : Accéder aux paramètres 2FA**

Dans le menu, cliquez sur **Sécurité 2FA**

**Étape 3 : Scanner le QR Code**

1. Ouvrez votre application d'authentification (ex: Google Authenticator)
2. Scannez le QR code affiché à l'écran
3. L'application va générer un code à 6 chiffres

**Étape 4 : Vérifier le code**

1. Entrez le code à 6 chiffres affiché dans votre application
2. Cliquez sur **Activer le 2FA**

**Étape 5 : Confirmation**

Le 2FA est maintenant activé ! Lors de votre prochaine connexion, vous devrez :
1. Entrer votre email et mot de passe
2. Puis entrer le code 2FA généré par votre application

### Comment se connecter avec le 2FA activé

**Étape 1 : Connexion classique**

Entrez votre email et mot de passe comme d'habitude.

**Étape 2 : Vérification 2FA**

Vous êtes redirigé vers une page demandant le code 2FA :
1. Ouvrez votre application d'authentification
2. Regardez le code à 6 chiffres pour ce compte
3. Entrez-le dans le formulaire
4. Validez

Si le code est correct, vous êtes connecté !

### Comment désactiver le 2FA

1. Connectez-vous (avec le 2FA si activé)
2. Allez dans **Sécurité 2FA**
3. Cliquez sur **Désactiver le 2FA**

Le 2FA est désactivé, vous pouvez maintenant vous connecter uniquement avec email/mot de passe.

### Dépannage 2FA

**Le code ne fonctionne pas** :
- Vérifiez que l'heure de votre téléphone est synchronisée automatiquement
- Le code change toutes les 30 secondes, assurez-vous d'utiliser le code actuel
- Vérifiez que le fuseau horaire du serveur est correct

**Le QR code ne s'affiche pas** :
- Vérifiez votre connexion Internet
- Le QR code est généré via l'API qrserver.com
- Alternative : copiez manuellement la clé secrète dans votre application

**J'ai perdu mon téléphone** :
- Contactez l'administrateur pour désactiver le 2FA sur votre compte
- À l'avenir, notez la clé secrète dans un endroit sûr lors de l'activation

---

## Comptes de test

L'application est livrée avec des comptes de démonstration :

**Compte administrateur** :
- Email : `raphael.lang@gmail.com`
- Mot de passe : `123`

**Compte utilisateur** :
- Email : `jane@example.com`
- Mot de passe : `123`

**Note** : Changez ces mots de passe en production !

---

## Architecture MVC - Concepts pédagogiques

### Qu'est-ce que le MVC ?

Le **MVC** est un patron de conception qui sépare l'application en trois composants :

**1. Modèle (Model)** - `src/Models/`
- Gère les données et la logique métier
- Interagit avec la base de données
- Exemples : `Article.php`, `User.php`, `Match.php`

**2. Vue (View)** - `src/Views/`
- Gère l'affichage et l'interface utilisateur
- Contient le HTML/CSS
- Exemples : `articles/index.php`, `auth/login.php`

**3. Contrôleur (Controller)** - `src/Controllers/`
- Fait le lien entre Modèles et Vues
- Traite les requêtes utilisateur
- Exemples : `ArticleController.php`, `AuthController.php`

### Flux de traitement d'une requête

```
1. Utilisateur → URL (ex: articles/123-titre.html)
   ↓
2. .htaccess → Redirige vers public/index.php
   ↓
3. index.php → Front Controller
   ↓
4. Router → Analyse l'URL
   ↓
5. Contrôleur → ArticleController::show()
   ↓
6. Modèle → Article::getById(123)
   ↓
7. Base de données → Requête SQL
   ↓
8. Modèle → Retourne les données
   ↓
9. Contrôleur → Prépare les données
   ↓
10. Vue → Affiche en HTML
   ↓
11. Navigateur → Page affichée
```

### Principes POO appliqués

**Namespace** : Organisation logique (`App\Models\Article`)

**Autoloading PSR-4** : Chargement automatique des classes via Composer

**Singleton** : Une seule connexion à la base de données (`Database::getInstance()`)

**Encapsulation** : Propriétés privées, accès via méthodes publiques

**Séparation des responsabilités** : Chaque classe a un rôle unique et précis

---

## Sécurité

### Mesures de sécurité implémentées

- ✅ Requêtes préparées PDO (anti-injection SQL)
- ✅ Échappement des données avec `htmlspecialchars()` (anti-XSS)
- ✅ Nettoyage des entrées avec `strip_tags()`
- ✅ Validation des emails avec `egulias/email-validator`
- ✅ Authentification à deux facteurs (2FA)
- ✅ Protection des dossiers sensibles via `.htaccess`
- ✅ Sessions sécurisées

### Recommandations pour la production

**Désactiver l'affichage des erreurs** dans `config/config.php` :
```php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
```

**Changer les mots de passe** des comptes de test

**Activer HTTPS** sur votre hébergement

**Sauvegarder régulièrement** la base de données

---

## Tests

### Exécuter les tests PHPUnit

En local (après `composer install` avec les dépendances de dev) :

```bash
# Exécuter tous les tests
php vendor/bin/phpunit

# Avec couverture de code
php vendor/bin/phpunit --coverage-html coverage
```

Les tests sont dans le dossier `tests/`.

**Note** : Les tests ne sont pas disponibles en production (OVH) car PHPUnit n'est pas installé avec `--no-dev`.

---

## Support et Documentation

### Structure de la base de données

**Table `articles`** :
- Stocke les articles de presse sportifs
- Champs : id, titre, contenu, date_creation, etc.

**Table `matchs`** :
- Stocke les résultats sportifs
- Champs : id, equipe1, equipe2, score1, score2, lieu, resume, etc.

**Table `users`** :
- Stocke les utilisateurs
- Champs : id, nom, email, password, two_factor_secret, two_factor_enabled

### Routes disponibles

| URL | Méthode | Description |
|-----|---------|-------------|
| `/home.html` | GET | Liste des articles |
| `/articles/123-titre.html` | GET | Affichage d'un article |
| `/add.html` | GET | Formulaire d'ajout |
| `/addpost.html` | POST | Traitement de l'ajout |
| `/edit.html?id=123` | GET | Formulaire de modification |
| `/editpost.html` | POST | Traitement de la modification |
| `/deletepost.html` | POST | Suppression d'un article |
| `/login.html` | GET/POST | Connexion |
| `/logout.html` | GET | Déconnexion |
| `/2fa-settings.html` | GET | Paramètres 2FA |
| `/verify-2fa.html` | GET/POST | Vérification code 2FA |

---

## Évolutions possibles

- [ ] Système de rôles et permissions (admin/user)
- [ ] Pagination des articles
- [ ] Recherche et filtres avancés
- [ ] Upload d'images pour les articles
- [ ] Système de commentaires
- [ ] API REST
- [ ] Codes de secours pour le 2FA
- [ ] Logs d'activité 2FA
- [ ] Rate limiting sur les connexions

---

## Ressources pédagogiques

### Documentation PHP
- [PHP.net - POO](https://www.php.net/manual/fr/language.oop5.php)
- [PHP.net - PDO](https://www.php.net/manual/fr/book.pdo.php)
- [PSR-4 Autoloading](https://www.php-fig.org/psr/psr-4/)

### Composer
- [Getcomposer.org](https://getcomposer.org/)
- [Packagist](https://packagist.org/)

### Authentification 2FA
- [PragmaRX Google2FA](https://github.com/antonioribeiro/google2fa)
- [RFC 6238 (TOTP)](https://tools.ietf.org/html/rfc6238)

---

## Licence

Projet pédagogique - Formation PHP

---

## Auteur

Projet développé à des fins d'apprentissage de l'architecture MVC, de la POO, et de l'utilisation de Composer en PHP moderne.

**Version** : 2.0.0 - Avec authentification 2FA
