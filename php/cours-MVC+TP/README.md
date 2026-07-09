# Ludotheque du Bourg — Fichiers de depart du TP

A distribuer aux stagiaires en debut de journee, en complement du cours
"Le pattern MVC en PHP natif".

## Contenu

- `schema.sql` — a executer dans phpMyAdmin/MySQL avant de commencer (cree la base
  `ludotheque_bourg`, les tables `jeux`, `adherents`, `emprunts`, et un jeu de donnees
  de test).
- `maquette/liste_jeux.html` — maquette Bootstrap statique : c'est le rendu visuel
  cible de la vue `liste.php` (a ouvrir directement dans un navigateur, aucun
  serveur PHP requis).
- `data-test-jeux.php` — tableau PHP en dur a copier-coller dans `JeuModel::tousLesJeux()`
  pour le Niveau 1 (avant tout branchement PDO).
- `mvc-ludotheque/` — squelette de projet a completer :
  - `public/index.php` — front controller, routeur a trous
  - `app/controllers/JeuController.php` — methodes a completer (TODO)
  - `app/models/JeuModel.php` — methodes a completer (TODO)
  - `app/views/jeux/liste.php` et `formulaire.php` — vues vides (TODO)
  - `config/database.php` — connexion PDO a decommenter/completer au Niveau 2

## Deroule

1. Executer `schema.sql`.
2. Niveau 1 : completer `index.php`, `JeuController::liste()` et
   `JeuModel::tousLesJeux()` avec les donnees de `data-test-jeux.php`, puis
   `liste.php` en s'inspirant de `maquette/liste_jeux.html`.
3. Niveau 2 : brancher `config/database.php`, completer le CRUD dans
   `JeuModel`, les actions `ajouter`/`modifier`/`supprimer` dans
   `JeuController`, et `formulaire.php`.
4. Niveau 3 (optionnel) : gestion des emprunts (tables deja prevues dans
   `schema.sql`) via une couche Service dediee.

Voir le support de cours (`Cours_MVC_DWWM.docx`) pour le detail des criteres
de validation de chaque niveau.
