# Configuration des Variables d'Environnement (.env)

## 📝 Description

Ce projet utilise un fichier `.env` pour stocker les **variables d'environnement sensibles** comme les clés API, mots de passe et paramètres de configuration.

## 🔒 Pourquoi Utiliser un Fichier .env ?

### Sécurité
- ✅ **Pas de clés API dans Git** - Le fichier `.env` est exclu du dépôt Git via `.gitignore`
- ✅ **Protection des secrets** - Les clés API, mots de passe et tokens restent privés
- ✅ **Meilleure pratique** - Standard de l'industrie pour la gestion des secrets

### Flexibilité
- ✅ **Configuration par environnement** - Différents paramètres pour dev/staging/production
- ✅ **Partage facile** - Le fichier `.env.example` montre quelles variables sont nécessaires
- ✅ **Pas de modification du code** - Changez la configuration sans toucher au code

## 📦 Installation

### 1. Copier le Fichier Exemple

```bash
cp .env.example .env
```

### 2. Configurer Vos Variables

Éditez le fichier `.env` et remplacez les valeurs par défaut :

```env
# Clé API TinyMCE (éditeur de texte riche)
# Obtenez une clé gratuite sur https://www.tiny.cloud/auth/signup/
TINYMCE_API_KEY=votre-vraie-cle-api-ici

# Fuseau horaire de l'application
# Exemples: UTC, Europe/Paris, America/New_York
TIMEZONE=Europe/Paris

# Mode de développement (true = affiche les erreurs, false = mode production)
DEV_MODE=true
```

## 🔧 Variables Disponibles

### TINYMCE_API_KEY
- **Description** : Clé API pour l'éditeur TinyMCE
- **Valeur par défaut** : `no-api-key`
- **Où obtenir** : [https://www.tiny.cloud/auth/signup/](https://www.tiny.cloud/auth/signup/)
- **Requis** : Non (fonctionne en mode limité sans clé)

### TIMEZONE
- **Description** : Fuseau horaire de l'application pour les dates
- **Valeur par défaut** : `UTC`
- **Exemples** : `Europe/Paris`, `America/New_York`, `Asia/Tokyo`
- **Liste complète** : [Liste des fuseaux horaires PHP](https://www.php.net/manual/fr/timezones.php)

### DEV_MODE
- **Description** : Active le mode développement avec affichage des erreurs
- **Valeur par défaut** : `true`
- **Valeurs possibles** : `true` ou `false`
- **Production** : Mettez à `false` en production pour cacher les erreurs

## 🛠️ Comment ça Fonctionne ?

### 1. Chargement du .env

Le fichier `config/env.php` contient deux fonctions :

```php
// Charge le fichier .env et définit les variables
loadEnv(__DIR__ . '/../.env');

// Récupère une variable avec valeur par défaut
$apiKey = env('TINYMCE_API_KEY', 'no-api-key');
```

### 2. Utilisation dans config.php

```php
// Charge les variables d'environnement
require_once __DIR__ . '/env.php';
loadEnv(__DIR__ . '/../.env');

// Utilise les variables
define('TINYMCE_API_KEY', env('TINYMCE_API_KEY', 'no-api-key'));
date_default_timezone_set(env('TIMEZONE', 'UTC'));
```

## 🚨 Sécurité - Points Importants

### ⚠️ JAMAIS Commiter le .env

Le fichier `.env` contenant vos vraies clés API **NE DOIT JAMAIS** être ajouté à Git :

```bash
# Le .gitignore contient déjà cette ligne :
.env
.env.local
```

### ✅ Toujours Commiter le .env.example

Le fichier `.env.example` sans les vraies valeurs **DOIT** être versionné :

```bash
git add .env.example
git commit -m "Add .env.example configuration template"
```

### 🔐 Si Vous Avez Exposé une Clé API

Si vous avez accidentellement commit un fichier `.env` avec une vraie clé API :

1. **Révoquez immédiatement la clé** sur le site du fournisseur (TinyMCE, etc.)
2. **Générez une nouvelle clé**
3. **Mettez à jour votre `.env` local** avec la nouvelle clé
4. **Nettoyez l'historique Git** :

```bash
# Supprimez le fichier de l'historique Git
git filter-branch --force --index-filter \
  "git rm --cached --ignore-unmatch .env" \
  --prune-empty --tag-name-filter cat -- --all

# Force push (ATTENTION : opération destructive)
git push origin --force --all
```

## 📚 Exemples d'Utilisation

### Ajouter une Nouvelle Variable

1. **Ajoutez la variable dans `.env.example`** :
```env
# Ma nouvelle variable
MA_VARIABLE=valeur-par-defaut
```

2. **Ajoutez la variable dans votre `.env` local** :
```env
MA_VARIABLE=ma-vraie-valeur
```

3. **Utilisez-la dans le code** :
```php
$maVariable = env('MA_VARIABLE', 'valeur-par-defaut');
```

### Différentes Configurations par Environnement

#### Développement (.env)
```env
DEV_MODE=true
TIMEZONE=UTC
```

#### Production (.env sur le serveur)
```env
DEV_MODE=false
TIMEZONE=Europe/Paris
```

## 🎓 Pour les Stagiaires

Ce système de fichier `.env` illustre plusieurs concepts importants :

1. **Séparation des préoccupations** : Configuration vs Code
2. **Sécurité** : Ne jamais exposer les secrets
3. **Portabilité** : Même code, différentes configurations
4. **Bonnes pratiques DevOps** : Configuration externalisée
5. **12-Factor App** : [https://12factor.net/config](https://12factor.net/config)

## 📖 Références

- [PHP dotenv (alternative avec package Composer)](https://github.com/vlucas/phpdotenv)
- [12-Factor App - Configuration](https://12factor.net/config)
- [OWASP - Secure Configuration](https://owasp.org/www-project-top-ten/)

---

*Mis en place le 2026-01-06 pour sécuriser la clé API TinyMCE*
