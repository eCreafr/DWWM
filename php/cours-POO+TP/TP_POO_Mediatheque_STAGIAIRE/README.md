# TP POO — Médiathèque « La Grande Ourse »

Formation DWWM — AFPA Nouvelle-Aquitaine — Raphaël Lang

---

## Le contexte

La médiathèque associative **La Grande Ourse** gère un fonds de livres, DVD et jeux vidéo.
**Claire**, bibliothécaire, a besoin d'un outil pour suivre le catalogue et la disponibilité des documents.
**Karim**, adhérent, consulte le catalogue en ligne.

Vous construisez le cœur de cette application en trois étapes, réparties sur la journée.

---

## Installation (5 minutes, à faire avant l'étape 1)

1. Copiez ce dossier dans la racine web de votre stack locale, par exemple :

    - MAMP : `/Applications/MAMP/htdocs/tp-poo-mediatheque/`
    - XAMPP : `C:\xampp\htdocs\tp-poo-mediatheque\`
    - Homebrew : `/opt/homebrew/var/www/tp-poo-mediatheque/`

2. Importez la base de données :

    ```bash
    mysql -u root -p < sql/mediatheque.sql
    ```

    Ou via phpMyAdmin : onglet **Importer** → choisir `sql/mediatheque.sql`.

3. Vérifiez l'import :

    ```sql
    SELECT type, COUNT(*) AS nb FROM documents GROUP BY type;
    -- Attendu : livre 4, dvd 4, jeu_video 4
    ```

4. Adaptez `etape3/src/config.php` à votre configuration MySQL.

5. Initialisez votre dépôt Git :
    ```bash
    git init && git add . && git commit -m "Init TP POO médiathèque"
    ```

---

## Déroulé de la journée

| Étape | Moment        | Sujet                  | Dossier   |
| ----- | ------------- | ---------------------- | --------- |
| 1     | 9h40 – 10h45  | Premières classes      | `etape1/` |
| 2     | 11h25 – 12h30 | Héritage et interfaces | `etape2/` |
| 3     | 13h55 – 15h30 | Repository PDO         | `etape3/` |

Chaque étape se joue sur **trois niveaux** :

-   **Niveau 1 — Rampe d'accès** : guidé, code à trous. Personne ne doit rester bloqué ici.
-   **Niveau 2 — Socle certif** : le niveau réellement attendu à la certification. En autonomie.
-   **Niveau 3 — Extension** : pour ceux qui terminent en avance.

**Vous n'avez pas à finir le niveau 3.** Vous devez impérativement finir le niveau 2.

---

## Comment travailler

### Lancer les tests

Chaque étape a son `index.php`. Deux façons de l'exécuter :

```bash
# En ligne de commande (étapes 1 et 2)
cd etape1 && php index.php
```

Ou dans le navigateur (obligatoire pour l'étape 3) :
`http://localhost/tp-poo-mediatheque/etape3/index.php`

### Les fichiers à trous

Cherchez les marqueurs `TODO` — ce sont vos zones de travail.
Tout ce qui n'est pas marqué `TODO` est fourni et **ne doit pas être modifié** :
les fichiers de test s'appuient sur ces signatures.

### Les commits

Un commit à chaque niveau validé, avec un message explicite :

```bash
git commit -m "Etape 1 niveau 2 : setter avec validation du titre"
```

Ce n'est pas de la coquetterie : votre historique Git fait partie de ce que
le jury peut consulter, et un dépôt avec un seul commit « final » est mal perçu.

---

## Règles techniques valables sur tout le TP

Ces règles ne sont pas des préférences de style. Ce sont des **critères d'évaluation
du REAC** (CP6 et CP7), et le jury les vérifie.

1. **Typage strict partout** — `declare(strict_types=1);` en tête de chaque fichier,
   et un type sur chaque paramètre et chaque retour de méthode.
2. **Aucune propriété en `public`** — `private` par défaut, `protected` si une
   classe fille en a besoin.
3. **Aucun `echo` dans une classe métier** — une classe retourne des données,
   elle n'affiche pas. C'est l'appelant qui décide.
4. **Toutes les requêtes SQL avec paramètre en requête préparée** — marqueurs
   nommés (`:id`), jamais de concaténation.
5. **Toute donnée affichée passe par `htmlspecialchars()`** — parade XSS.
6. **Aucun message d'erreur technique affiché à l'utilisateur** — `error_log()`
   pour la trace, message générique à l'écran.
7. **Nommage** — classes en `PascalCase`, méthodes et propriétés en `camelCase`,
   un fichier par classe portant exactement le nom de la classe.

---

## Ce que vous devez savoir expliquer à l'oral de restitution

Préparez-vous à répondre sans votre IDE :

-   Pourquoi vos propriétés sont en `private` plutôt qu'en `public`.
-   La différence entre la classe abstraite `Document` et l'interface `Empruntable`.
-   Ce qui se passerait si vous écriviez `new Document('Titre', 2024)`.
-   Comment une seule boucle `foreach` peut afficher trois formats différents.
-   Ce qui se passerait si vous concaténiez `$_GET['id']` dans votre requête SQL.
-   Pourquoi le SQL est centralisé dans le Repository plutôt qu'écrit dans chaque page.

---

## En cas de blocage

1. Lisez le message d'erreur **en entier** — PHP indique le fichier et la ligne.
2. Relisez les commentaires du fichier : la réponse y est presque toujours.
3. Comparez avec un fichier fourni qui fait quelque chose de similaire
   (`Livre.php` de l'étape 2 est le modèle pour `Dvd.php` et `JeuVideo.php`).
4. Cherchez sur php.net avant de demander — c'est la compétence transversale
   « apprendre en continu » du REAC.
5. Puis appelez le formateur.

---

## Ressources

-   PHP — Classes et objets : https://www.php.net/manual/fr/language.oop5.php
-   PHP — PDO : https://www.php.net/manual/fr/book.pdo.php
-   OWASP — Injection SQL : https://cheatsheetseries.owasp.org
