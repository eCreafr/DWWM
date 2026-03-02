# Intégration TinyMCE - Éditeur de Texte Riche

## 📝 Description

TinyMCE est un éditeur WYSIWYG (What You See Is What You Get) qui permet aux utilisateurs de créer du contenu riche avec mise en forme (gras, italique, couleurs, listes, etc.) lors de l'édition des articles.

## ✨ Fonctionnalités Disponibles

L'éditeur TinyMCE intégré dans ce projet offre :

- ✅ **Mise en forme du texte** : gras, italique, souligné
- ✅ **Couleurs** : couleur du texte et couleur de fond
- ✅ **Alignement** : gauche, centre, droite, justifié
- ✅ **Listes** : puces et numérotées
- ✅ **Titres et paragraphes** : différents niveaux de titres (H1, H2, H3, etc.)
- ✅ **Liens et images**
- ✅ **Tableaux**
- ✅ **Annuler/Refaire**
- ✅ **Code source** : possibilité de voir et modifier le HTML
- ✅ **Plein écran** : mode d'édition plein écran
- ✅ **Compte de mots**

## 🔧 Implémentation Technique

### 1. Chargement Conditionnel

Le script TinyMCE est chargé uniquement sur les pages qui en ont besoin (création et édition d'articles) :

```php
// Dans src/Views/articles/create.php et edit.php
<?php
$useTinyMCE = true;
?>
```

Le layout vérifie cette variable et charge le CDN TinyMCE :

```php
// Dans src/Views/layouts/default.php
<?php if (isset($useTinyMCE) && $useTinyMCE === true): ?>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<?php endif; ?>
```

### 2. Configuration de l'Éditeur

L'éditeur est initialisé sur le champ `#contenu` avec la configuration suivante :

```javascript
tinymce.init({
    selector: '#contenu',
    language: 'fr_FR',
    height: 400,
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | bold italic forecolor backcolor | ' +
        'alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist outdent indent | removeformat | help'
});
```

### 3. Affichage du Contenu Riche

#### Page Article Complet (show.php)

Le contenu HTML est affiché directement sans échappement :

```php
<div class="article-content">
    <?= $article['contenu']; ?>
</div>
```

#### Page Liste des Articles (index.php)

Pour l'extrait, le HTML est d'abord converti en texte brut puis tronqué :

```php
<?= htmlspecialchars(StringHelper::truncate(StringHelper::stripHtml($article['contenu']), 200)); ?>
```

La méthode `StringHelper::stripHtml()` :
- Retire toutes les balises HTML
- Décode les entités HTML
- Supprime les espaces multiples
- Retourne du texte brut propre

## 🔒 Sécurité

### Note Importante sur la Sécurité XSS

⚠️ **ATTENTION** : L'affichage direct du HTML utilisateur (sans `htmlspecialchars()`) peut présenter un risque de faille XSS (Cross-Site Scripting).

### Recommandations pour la Production

Pour un environnement de production, il est recommandé d'implémenter une **purification HTML** côté serveur. Voici comment :

#### 1. Installer HTML Purifier via Composer

```bash
composer require ezyang/htmlpurifier
```

#### 2. Créer un Helper de Purification

Créer `src/Helpers/HtmlPurifier.php` :

```php
<?php

namespace App\Helpers;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlSanitizer
{
    public static function purify(string $html): string
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Cache.SerializerPath', __DIR__ . '/../../cache');

        // Autoriser uniquement les balises sûres
        $config->set('HTML.Allowed', 'p,br,strong,em,u,h1,h2,h3,h4,ul,ol,li,a[href],img[src|alt],table,tr,td,th');

        $purifier = new HTMLPurifier($config);
        return $purifier->purify($html);
    }
}
```

#### 3. Utiliser le Purifier dans les Contrôleurs

```php
// Dans ArticleController::addArticle() et editArticle()
use App\Helpers\HtmlSanitizer;

$contenu = HtmlSanitizer::purify($_POST['contenu']);
```

### Solution Actuelle (Développement)

Pour ce projet pédagogique en développement local :
- ✅ Les utilisateurs doivent être authentifiés pour créer/modifier des articles
- ✅ Le risque XSS est limité aux comptes autorisés
- ⚠️ Pour la production, implémentez HTML Purifier comme décrit ci-dessus

## 📦 Pas de Dépendance Composer

TinyMCE est chargé via CDN, donc **aucune modification du composer.json n'est nécessaire**. Cela garde le projet léger et facilite les mises à jour de TinyMCE.

### Avantages du CDN

- ✅ Pas de fichiers à télécharger
- ✅ Mises à jour automatiques des fonctionnalités
- ✅ Rapidité de chargement (cache navigateur)
- ✅ Pas d'impact sur la taille du projet

### Configuration de la Clé API

Pour activer toutes les fonctionnalités de TinyMCE :

1. **Obtenez une clé API gratuite** sur [TinyMCE Cloud](https://www.tiny.cloud/auth/signup/)
2. **Configurez la clé** dans `config/config.php` :

```php
// Remplacez 'no-api-key' par votre vraie clé
define('TINYMCE_API_KEY', 'votre-cle-api-ici-123abc456def');
```

3. **Avantages avec une clé API** :
   - ✅ Accès complet aux fonctionnalités premium
   - ✅ Pas de limite de domaine en développement
   - ✅ Support et mises à jour automatiques
   - ✅ Plugins avancés (correcteur orthographique, outils d'image, etc.)

**Note** : La version gratuite fonctionne sans clé API mais avec des limitations. Voir `config/tinymce.example.php` pour plus de détails.

## 🧪 Tests

Pour tester l'intégration :

1. Connectez-vous en tant qu'utilisateur ou admin
2. Accédez à "Ajouter un article" (`/add.html`)
3. Vous devriez voir l'éditeur TinyMCE à la place du textarea simple
4. Testez les fonctionnalités : gras, italique, couleurs, listes, etc.
5. Créez un article avec du contenu formaté
6. Vérifiez que le formatage est bien conservé dans la vue complète de l'article

## 📚 Ressources

- [Documentation officielle TinyMCE](https://www.tiny.cloud/docs/)
- [Configuration de TinyMCE](https://www.tiny.cloud/docs/tinymce/latest/basic-setup/)
- [HTML Purifier pour la sécurité](http://htmlpurifier.org/)

## 🎓 Pédagogie

Cette intégration permet de montrer à vos stagiaires :

1. **Chargement conditionnel de ressources** : optimisation des performances
2. **CDN vs packages locaux** : quand utiliser l'un ou l'autre
3. **Sécurité web** : comprendre les risques XSS et comment les mitiger
4. **Intégration JavaScript** : comment faire interagir JS et PHP
5. **UX/UI** : améliorer l'expérience utilisateur avec des outils professionnels

---

*Intégration réalisée le 2026-01-06 pour le projet STEP5-mvc+poo+composer*
