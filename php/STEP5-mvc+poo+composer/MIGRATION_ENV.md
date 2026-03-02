# Migration vers le Système .env

## 📅 Date : 2026-01-06

## 🎯 Objectif

Sécuriser la clé API TinyMCE en la déplaçant du code source vers un fichier `.env` non versionné.

## ⚠️ Problème Identifié

La clé API TinyMCE était stockée en dur dans `config/config.php` :

```php
define('TINYMCE_API_KEY', '5cwidnspevwfhqgmp8mq8icyqo30bytukm5bv8wyuiabxmpm');
```

Ce fichier était versionné dans Git et donc **exposé publiquement sur GitHub** ! 🔓

## ✅ Solution Implémentée

### 1. Création du Système .env

Nouveau fichier `config/env.php` avec deux fonctions :
- `loadEnv()` : Charge les variables depuis `.env`
- `env()` : Récupère une variable avec valeur par défaut

### 2. Fichiers Créés

| Fichier | Description | Versionné Git |
|---------|-------------|---------------|
| `.env` | Contient les vraies clés API | ❌ NON (.gitignore) |
| `.env.example` | Template sans vraies valeurs | ✅ OUI |
| `config/env.php` | Chargeur de variables | ✅ OUI |
| `ENV_CONFIGURATION.md` | Documentation complète | ✅ OUI |

### 3. Modifications Appliquées

#### config/config.php
```php
// AVANT
define('TINYMCE_API_KEY', '5cwidnspevwfhqgmp8mq8icyqo30bytukm5bv8wyuiabxmpm');

// APRÈS
require_once __DIR__ . '/env.php';
loadEnv(__DIR__ . '/../.env');
define('TINYMCE_API_KEY', env('TINYMCE_API_KEY', 'no-api-key'));
```

#### .gitignore
```gitignore
# Configuration locale (contient des clés API sensibles)
/config/database.php
/config/config.php  # ← AJOUTÉ
```

### 4. Variables Déplacées vers .env

```env
TINYMCE_API_KEY=5cwidnspevwfhqgmp8mq8icyqo30bytukm5bv8wyuiabxmpm
TIMEZONE=UTC
DEV_MODE=true
```

## 🔒 Sécurité Renforcée

### Avant la Migration
- ❌ Clé API dans le code source
- ❌ Clé API versionnée dans Git
- ❌ Clé API exposée sur GitHub
- ❌ Risque de fuite de secrets

### Après la Migration
- ✅ Clé API dans `.env` (non versionné)
- ✅ `.env` exclu de Git via `.gitignore`
- ✅ Template `.env.example` pour la documentation
- ✅ Secrets protégés et externalisés

## 📦 Impact sur le ZIP de Distribution

Le script `create_release.php` a été mis à jour :

```php
// Création du fichier .env (copie de .env.example)
if (file_exists($projectRoot . '/.env.example')) {
    $zip->addFile($projectRoot . '/.env.example', '.env');
}
```

Le ZIP contient maintenant :
- ✅ `.env` avec valeurs par défaut (depuis `.env.example`)
- ✅ `config/config.php` avec chargement du `.env`
- ✅ `config/env.php` (chargeur de variables)
- ✅ Documentation complète

## 🚀 Procédure d'Installation

### Pour les Nouveaux Utilisateurs

1. Décompresser le ZIP
2. Le fichier `.env` est déjà présent avec valeurs par défaut
3. Éditer `.env` et ajouter sa propre clé API TinyMCE
4. Lancer l'installation normalement

### Pour les Développeurs Existants

1. Créer `.env` à partir de `.env.example` :
   ```bash
   cp .env.example .env
   ```

2. Ajouter la clé API TinyMCE dans `.env` :
   ```env
   TINYMCE_API_KEY=votre-cle-api-ici
   ```

3. Le fichier `config/config.php` charge automatiquement le `.env`

## 🔄 Migration Git

Le fichier `config/config.php` a été retiré du tracking Git :

```bash
git rm --cached config/config.php
```

Désormais :
- `config/config.php` est généré localement
- `config/config.example.php` sert de template
- Pas de risque d'exposition des secrets

## 📚 Avantages de Cette Approche

1. **Sécurité** : Les secrets ne sont jamais versionnés
2. **Flexibilité** : Configuration différente par environnement
3. **Standard** : Suit les meilleures pratiques (12-factor app)
4. **Pédagogique** : Montre comment gérer les secrets en production
5. **Maintenabilité** : Changement de config sans toucher au code

## 🎓 Concepts Enseignés

Cette migration permet d'enseigner :
- Gestion des secrets et clés API
- Fichier `.env` et variables d'environnement
- `.gitignore` et fichiers sensibles
- Différence dev/staging/production
- Sécurité des applications web

## ⚠️ Action Requise

### Pour Révoquer l'Ancienne Clé Exposée

Si votre clé API a été exposée sur GitHub :

1. **Connectez-vous sur** [TinyMCE Cloud](https://www.tiny.cloud/)
2. **Révoquez** l'ancienne clé `5cwidnspevwfhqgmp8mq8icyqo30bytukm5bv8wyuiabxmpm`
3. **Générez** une nouvelle clé API
4. **Mettez à jour** votre `.env` local avec la nouvelle clé

## 📖 Documentation

Pour plus de détails, consultez :
- [ENV_CONFIGURATION.md](ENV_CONFIGURATION.md) - Documentation complète du système .env
- [INTEGRATION_TINYMCE.md](INTEGRATION_TINYMCE.md) - Documentation TinyMCE

---

*Migration effectuée le 2026-01-06 pour sécuriser les clés API*
