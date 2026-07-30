Niveau 1 : routeur, JeuModel::tousLesJeux(), liste.php conforme à la maquette — flux complet sans BDD.

Niveau 2 : PDO branché, CRUD complet en requêtes préparées, validation serveur, formulaire unique ajout/modification, échappement systématique. Testé avec injection SQL (' OR '1'='1) et XSS (<script>alert(1)</script>) — les deux sont neutralisées comme attendu.

Niveau 3 (optionnel) : EmpruntService isole la règle métier "un jeu disponible uniquement" entre JeuModel et EmpruntModel, sans polluer le contrôleur. Testé : emprunt, refus de ré-emprunt, retour.

# Correction — Ludotheque du Bourg (TP MVC PHP natif)

Correction de reference pour `../cours-MVC+TP/`, redigee et testee de bout en
bout comme si un stagiaire DWWM realisait le TP (Niveaux 1, 2 et 3). Ce dossier
n'est **pas** verse au depot (voir `.gitignore`) : c'est un support de
correction pour le formateur, pas un livrable a distribuer tel quel.

## Lancer le projet

1. Executer `schema.sql` (identique a celui fourni dans `cours-MVC+TP/`).
2. Adapter si besoin les identifiants dans `mvc-ludotheque/config/database.php`
   (par defaut : `root` sans mot de passe, DSN `localhost`/`ludotheque_bourg`).
3. Servir le dossier `mvc-ludotheque/public/` :
    ```
    php -S localhost:8000 -t mvc-ludotheque/public
    ```
4. Ouvrir `http://localhost:8000/index.php?action=liste`.

Teste avec PHP 8.5 (CLI) + MySQL 9.6 en local, CRUD, injections SQL/XSS et
regle metier des emprunts verifies via `curl` et requetes directes en base.

## Ecarts assumes par rapport au squelette a trous

Le squelette fourni aux stagiaires laisse volontairement des zones d'ombre
que j'ai du trancher pour produire un TP cohérent de bout en bout :

-   **Champ `editeur` et `duree_minutes` dans `JeuModel::creer()`/`modifier()`** :
    les signatures TODO du squelette (`creer(string $titre, int $joueursMin, int $joueursMax)`)
    n'incluaient pas `editeur` ni `duree_minutes`, pourtant presents dans le
    schema SQL, la maquette et les criteres de validation ("duree numerique
    positive"). Je les ai ajoutes aux deux methodes — un stagiaire attentif a
    la maquette devrait faire le meme constat.
-   **Suppression en `POST`, pas en lien `GET`** : la maquette montre un simple
    `<a href="#">Supprimer</a>`, mais une suppression est une action qui modifie
    l'etat serveur ; je l'ai implementee comme un petit formulaire POST (bouton
    stylé pour ressembler a un lien) avec confirmation JS. C'est le choix
    attendu a la certification (CP4 : recommandations de securite) plutot que
    la fidelite pixel-perfect a la maquette.
-   **Messages d'erreur d'emprunt** : passes en query string (`?erreur=...`)
    apres redirection plutot que via `$_SESSION`, pour rester dans l'esprit
    "PHP natif minimal" du cours sans introduire un systeme de flash messages
    non demande par l'enonce.

## Niveau 1 — Rampe d'acces

-   `JeuModel::tousLesJeux()` renvoie le tableau en dur (5 jeux), conserve tel
    quel dans le code final comme trace du parcours pedagogique — il n'est
    plus appele une fois `lister()` branchee en Niveau 2 (`liste()` du
    controleur utilise `lister()`).
-   `index.php` route `?action=liste` vers `JeuController::liste()`.
-   `liste.php` reproduit la maquette Bootstrap (`maquette/liste_jeux.html`).

Verification : `?action=liste` s'affiche sans erreur, zero SQL en dehors du
Modele, zero HTML en dur dans le Controleur.

## Niveau 2 — Socle certif

-   `config/database.php` : connexion PDO, `ERRMODE_EXCEPTION` +
    `FETCH_ASSOC`.
-   `JeuModel` : `lister()`, `trouverParId()`, `creer()`, `modifier()`,
    `supprimer()` — exclusivement des requetes preparees avec `bindValue`.
-   `JeuController` : validation serveur (titre non vide, joueurs min ≥ 1,
    max ≥ min, duree numerique positive ou vide) dans une methode privee
    partagee `validerFormulaire()`, pattern Post/Redirect/Get sur les 3
    actions d'ecriture.
-   `formulaire.php` : un seul template pour ajout et modification (bascule
    sur la presence de `$jeu['id']`), erreurs affichees proprement, jamais de
    `var_dump`/`die()`.
-   `liste.php` : toute sortie passe par une fonction `e()` (wrapper
    `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`).

Tests rejoues (voir details plus haut, executes en local) :

-   Injection SQL sur `titre` (`' OR '1'='1`) : inseree telle quelle comme
    simple chaine, aucune requete detournee, table `jeux` non affectee au-dela
    de la ligne creee.
-   Injection XSS sur `titre` (`<script>alert(1)</script>`) : affichee comme
    texte brut (`&lt;script&gt;...`), aucune execution.
-   `supprimer` refuse les requetes GET (405).
-   `modifier` sur un id inexistant renvoie 404.

## Niveau 3 — Extension emprunts

-   `EmpruntModel` : acces bruts a la table `emprunts` (creer/cloturer un
    emprunt), aucune regle metier.
-   `EmpruntService` : orchestre `JeuModel` + `EmpruntModel` pour la regle
    "un jeu ne peut etre emprunte que s'il est disponible" — verifiee cote
    serveur (`RuntimeException` si `disponible = 0`), pas seulement a
    l'affichage. Bascule automatiquement `jeux.disponible` a l'emprunt et au
    retour.
-   `EmpruntController` : routes `emprunter`/`retourner`, delegue toute la
    logique metier au Service (aucun SQL, aucune regle metier dans le
    Controleur).
-   `AdherentModel` ajoute pour alimenter le `<select>` d'emprunteur dans
    `liste.php` (necessaire cote vue, pas demande explicitement par l'enonce
    mais indispensable pour tester le flux).

Testé : emprunt d'un jeu disponible (bascule `disponible=0`, ligne creee
dans `emprunts`), tentative de re-emprunt d'un jeu deja emprunte (rejetee
avec message d'erreur), retour (bascule `disponible=1`, `date_retour`
renseignee). Le CRUD du Niveau 2 reste intact apres ajout du Niveau 3.

## Verdict : le TP est réalisable tel quel

Avec les deux ajustements de signature ci-dessus (editeur, duree_minutes),
un stagiaire qui suit l'enonce et s'inspire de la maquette et du support de
cours (`Cours_MVC_DWWM.docx`) peut completer les trois niveaux dans le
temps imparti (Niveau 1+2 en journee, Niveau 3 en bonus). Rien dans le
squelette ne bloque la progression.
