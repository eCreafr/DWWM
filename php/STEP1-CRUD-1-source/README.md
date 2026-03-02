# CRUD en PHP avec MySQL

## Qu'est-ce que le CRUD ?

**CRUD** est un acronyme qui désigne les quatre opérations de base pour gérer des données dans une base de données :

- **C**reate : **Créer** de nouvelles données
- **R**ead : **Lire** / Afficher des données existantes
- **U**pdate : **Modifier** des données existantes
- **D**elete : **Supprimer** des données

Ces quatre opérations constituent le socle de toute application qui manipule des données (blog, e-commerce, réseaux sociaux, etc.).

---

## Architecture du projet

Ce dossier contient un exemple complet de CRUD appliqué à une base de données d'articles sportifs.

### Structure des fichiers

```
php050-CRUD/
├── php050.sql              # Script de création de la base de données
├── php050-connect.php      # Connexion à la base de données (réutilisée partout)
│
├── php050-C.php            # CREATE - Formulaire d'ajout
├── php050-C-post.php       # CREATE - Traitement de l'ajout
│
├── php050-R.php            # READ - Affichage de tous les articles
│
├── php050-U.php            # UPDATE - Formulaire de modification
├── php050-U-post.php       # UPDATE - Traitement de la modification
│
├── php050-D.php            # DELETE - Page de confirmation
└── php050-D-post.php       # DELETE - Traitement de la suppression
```

### Pourquoi séparer formulaire et traitement ?

Chaque opération est divisée en **2 fichiers** :

1. **Fichier d'affichage** (`.php`) : Affiche le formulaire HTML
2. **Fichier de traitement** (`-post.php`) : Traite les données et modifie la BDD

**Avantages** :
- Séparation des responsabilités (affichage vs logique)
- Évite de retraiter les données si l'utilisateur rafraîchit la page
- Meilleure organisation du code

---

## 📌 C - CREATE : Créer un article

### Fichiers concernés
- [php050-C.php](php050-C.php) : Formulaire vide
- [php050-C-post.php](php050-C-post.php) : Insertion en BDD

### Fonctionnement

1. **L'utilisateur accède à [php050-C.php](php050-C.php)**
   - Un formulaire HTML s'affiche avec des champs vides : `auteur`, `titre`, `contenu`

2. **L'utilisateur remplit et soumet le formulaire**
   - Les données sont envoyées en **POST** vers `php050-C-post.php`

3. **Le fichier `php050-C-post.php` traite les données**
   - Récupère les données avec `$_POST`
   - Valide et nettoie les données (sécurité)
   - Exécute une requête SQL `INSERT INTO`
   - Redirige vers la page de liste

### Exemple SQL

```sql
INSERT INTO s2_articles_presse (titre, contenu, auteur, date_publication)
VALUES (:titre, :contenu, :auteur, NOW());
```

### Points clés
- **Méthode POST** : les données ne sont pas visibles dans l'URL
- **Requêtes préparées** : protection contre les injections SQL
- **Validation** : toujours vérifier les données utilisateur

---

## 📖 R - READ : Lire les articles

### Fichier concerné
- [php050-R.php](php050-R.php) : Affiche tous les articles

### Fonctionnement

1. **Connexion à la base de données**

2. **Exécution d'une requête SQL SELECT**
   ```sql
   SELECT a.id, a.titre, a.contenu, a.date_publication, r.score, r.lieu
   FROM s2_articles_presse a
   LEFT JOIN s2_resultats_sportifs r ON a.match_id = r.id
   ORDER BY a.date_publication DESC;
   ```

3. **Récupération de tous les résultats**
   ```php
   $news = $newsFraiches->fetchAll();
   ```

4. **Boucle d'affichage**
   ```php
   foreach ($news as $new) {
       echo $new['titre'];
       echo $new['contenu'];
       // etc.
   }
   ```

### Points clés
- **LEFT JOIN** : récupère les articles même s'ils n'ont pas de match associé
- **fetchAll()** : récupère TOUTES les lignes d'un coup (tableau de tableaux)
- **Fonction de troncature** : limite l'affichage des textes longs
- **ORDER BY DESC** : trie du plus récent au plus ancien

---

## ✏️ U - UPDATE : Modifier un article

### Fichiers concernés
- [php050-U.php](php050-U.php) : Formulaire pré-rempli
- [php050-U-post.php](php050-U-post.php) : Mise à jour en BDD

### Fonctionnement

1. **L'utilisateur accède à l'URL avec un ID** : `php050-U.php?id=5`

2. **Le fichier [php050-U.php](php050-U.php) charge l'article**
   ```php
   // Validation de l'ID
   if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
       echo "ID invalide";
       return;
   }

   // Récupération de l'article
   $retrieveArticleStatement = $mysqlClient->prepare(
       'SELECT titre, contenu FROM s2_articles_presse WHERE id = :id'
   );
   $retrieveArticleStatement->execute(['id' => (int)$_GET['id']]);
   $article = $retrieveArticleStatement->fetch();
   ```

3. **Affichage du formulaire pré-rempli**
   ```html
   <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
   <input type="text" name="titre" value="<?php echo $article['titre']; ?>">
   <textarea name="contenu"><?php echo $article['contenu']; ?></textarea>
   ```

4. **L'utilisateur modifie et soumet**
   - Les données sont envoyées vers `php050-U-post.php`

5. **Le fichier `php050-U-post.php` met à jour la BDD**
   ```sql
   UPDATE s2_articles_presse
   SET titre = :titre, contenu = :contenu
   WHERE id = :id;
   ```

### Points clés
- **ID dans l'URL** : permet de savoir quel article modifier
- **Champ hidden** : transmet l'ID du formulaire (GET → POST)
- **fetch()** : récupère UNE SEULE ligne (contrairement à `fetchAll()`)
- **Validation** : vérifier que l'article existe avant d'afficher le formulaire

---

## 🗑️ D - DELETE : Supprimer un article

### Fichiers concernés
- [php050-D.php](php050-D.php) : Page de confirmation
- [php050-D-post.php](php050-D-post.php) : Suppression en BDD

### Fonctionnement

1. **L'utilisateur accède à l'URL avec un ID** : `php050-D.php?id=9`

2. **Le fichier [php050-D.php](php050-D.php) affiche une confirmation**
   ```html
   <h1>Supprimer l'article, c'est sûr ???</h1>
   <form action="php050-D-post.php" method="POST">
       <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
       <button type="submit" class="btn btn-danger">Oui !</button>
       <a href="php050-R.php" class="btn btn-primary">Non, RETOUR</a>
   </form>
   ```

3. **Si l'utilisateur confirme**
   - L'ID est envoyé en POST vers `php050-D-post.php`

4. **Le fichier `php050-D-post.php` supprime l'article**
   ```sql
   DELETE FROM s2_articles_presse WHERE id = :id;
   ```

### Points clés
- **Page de confirmation** : évite les suppressions accidentelles
- **Action irréversible** : les données sont perdues définitivement
- **Bouton danger** (rouge) : signale visuellement le risque
- **Processus en 2 étapes** : confirmation obligatoire avant suppression

### ⚠️ Pourquoi une page de confirmation ?

- La suppression est **définitive** (pas de retour en arrière)
- Évite les suppressions accidentelles (clic par erreur)
- Bonne pratique UX (expérience utilisateur)
- Sécurité : empêche les suppressions automatiques via un simple lien

---

## 🔐 Sécurité : Les bonnes pratiques

### 1. Requêtes préparées (PDO)

**❌ MAUVAIS** (injection SQL possible) :
```php
$query = "SELECT * FROM users WHERE id = " . $_GET['id'];
```

**✅ BON** (requête préparée) :
```php
$statement = $mysqlClient->prepare('SELECT * FROM users WHERE id = :id');
$statement->execute(['id' => $_GET['id']]);
```

### 2. Validation des données

**Toujours vérifier** :
- Les champs obligatoires : `isset()`
- Le type de données : `is_numeric()`, `filter_var()`
- La longueur : `strlen()`
- Le format : expressions régulières

### 3. Protection contre les failles XSS

```php
// Affichage sécurisé
echo htmlspecialchars($article['titre'], ENT_QUOTES, 'UTF-8');
```

### 4. Gestion des erreurs

```php
try {
    $statement->execute();
} catch (PDOException $e) {
    // Logger l'erreur mais ne PAS l'afficher à l'utilisateur
    error_log($e->getMessage());
    echo "Une erreur est survenue.";
}
```

---

## 🗄️ Structure de la base de données

### Table principale : `s2_articles_presse`

```sql
CREATE TABLE s2_articles_presse (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    auteur VARCHAR(100) NOT NULL,
    date_publication DATE NOT NULL,
    match_id INT,
    FOREIGN KEY (match_id) REFERENCES s2_resultats_sportifs(id)
);
```

### Relation avec la table `s2_resultats_sportifs`

Un article peut être lié à un match sportif via `match_id` (clé étrangère).

---

## 🚀 Pour aller plus loin

### Améliorations possibles

1. **Pagination**
   - Limiter le nombre d'articles affichés par page
   - Utiliser `LIMIT` et `OFFSET` en SQL

2. **Recherche et filtrage**
   - Ajouter un formulaire de recherche
   - Filtrer par auteur, date, etc.

3. **Authentification**
   - Restreindre l'accès aux opérations CREATE, UPDATE, DELETE
   - Système de login/logout

4. **Upload d'images**
   - Ajouter des images aux articles
   - Gestion du stockage et de l'affichage

5. **Validation côté client**
   - JavaScript pour valider avant l'envoi
   - Améliorer l'expérience utilisateur

6. **Soft delete** (suppression douce)
   - Au lieu de supprimer définitivement, marquer comme "supprimé"
   - Ajouter un champ `deleted_at` dans la table
   - Possibilité de restaurer les données

7. **Historique des modifications**
   - Enregistrer qui a modifié quoi et quand
   - Table d'audit

---

## 📚 Concepts clés à retenir

| Opération | Méthode HTTP | SQL | Effet |
|-----------|-------------|-----|-------|
| **CREATE** | POST | `INSERT INTO` | Ajoute une nouvelle ligne |
| **READ** | GET | `SELECT` | Lit des données |
| **UPDATE** | POST | `UPDATE` | Modifie une ligne existante |
| **DELETE** | POST | `DELETE` | Supprime une ligne |

### Différence GET vs POST

| GET | POST |
|-----|------|
| Données dans l'URL | Données cachées |
| Limité en taille | Pas de limite |
| Peut être mis en favoris | Ne peut pas être mis en favoris |
| Idéal pour READ | Idéal pour CREATE, UPDATE, DELETE |

---

## 🧪 Comment tester ce projet ?

1. **Créer la base de données**
   ```bash
   mysql -u root -p < php050.sql
   ```

2. **Configurer la connexion**
   - Éditer `php050-connect.php` avec vos identifiants

3. **Démarrer un serveur local**
   ```bash
   php -S localhost:8000
   ```

4. **Tester les opérations CRUD**
   - **READ** : Accéder à `http://localhost:8000/php050-R.php`
   - **CREATE** : Accéder à `http://localhost:8000/php050-C.php`
   - **UPDATE** : Cliquer sur "Modifier" depuis la liste (ou `php050-U.php?id=1`)
   - **DELETE** : Cliquer sur "Supprimer" depuis la liste (ou `php050-D.php?id=1`)

---

## 📖 Ressources complémentaires

- [Documentation PHP PDO](https://www.php.net/manual/fr/book.pdo.php)
- [Tutoriel SQL](https://sql.sh/)
- [Sécurité des applications web (OWASP)](https://owasp.org/www-project-top-ten/)

---

**💡 Conseil** : Commencez toujours par READ, puis CREATE, puis UPDATE, et enfin DELETE. C'est l'ordre logique d'apprentissage !
