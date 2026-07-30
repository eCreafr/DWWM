# TP POO « La Grande Ourse » — PACK FORMATEUR

**Document interne — ne pas diffuser aux stagiaires.**

Formation DWWM — AFPA Nouvelle-Aquitaine — Raphaël Lang

---

## Contenu du pack

```
formateur/
├── README.md                    ← ce fichier
├── sql/mediatheque.sql          ← identique au pack stagiaire
├── etape1/                      ← corrigé niveaux 1 et 2
├── etape2/                      ← corrigé niveaux 1 et 2
├── etape3/                      ← corrigé niveaux 1 et 2
├── niveau3/                     ← corrigés des extensions, PAR ÉTAPE
│   ├── etape1/
│   │   ├── Livre.php            ← isbn validé + __toString()
│   │   └── jeu-essai.php        ← 3 cas passants / 2 cas d'erreur
│   ├── etape2/
│   │   ├── Reservable.php       ← interface du multi-contrat
│   │   ├── TraitReservation.php ← bonus : le trait, et pourquoi
│   │   ├── Livre / Dvd / JeuVideo / Document / Empruntable
│   │   ├── TypeDocument.php     ← enum PHP 8.1
│   │   └── index.php            ← démonstration instanceof
│   └── etape3/
│       ├── DocumentRepositoryHydrate.php  ← hydratation + agrégations
│       └── EmpruntService.php             ← transaction + rollback
└── amorce-mvc/                  ← code de référence de la démo 15h45
```

Le dossier `niveau3/` est organisé **par étape** et non à plat : chaque corrigé
se trouve là où on le cherche. La section 6.1 du document
`Corrige_Formateur_TP_POO_DWWM.docx` contient la matrice de traçabilité
défi annoncé → fichier corrigé → commande de vérification.

Le document `Corrige_Formateur_TP_POO_DWWM.docx` complète ce pack :
erreurs fréquentes, points de vigilance, barème et grille de suivi.

---

## Vérifier les corrigés

Tous les fichiers passent `php -l`. Les étapes 1 et 2 s'exécutent sans base de données :

```bash
php etape1/index.php                  # 4 blocs de test, tous OK attendus
php etape2/index.php                  # test progressif, niveaux 1 puis 2
php niveau3/etape1/jeu-essai.php      # 5/5 tests réussis
php niveau3/etape2/index.php          # Reservable, instanceof, enum
```

L'étape 3, l'amorce MVC et les deux corrigés de `niveau3/etape3/` nécessitent
la base importée et un `config.php` adapté à la stack de la salle.

---

## Point d'attention sur le SQL

Le `mediatheque.sql` fourni utilise un **héritage sur table unique** :
les colonnes `isbn`, `duree_minutes`, `plateforme` et `pegi` sont nullables
et ne concernent qu'un type de document.

C'est un choix assumé, sans lequel l'extension « hydratation » de l'étape 3
serait irréalisable — un `Dvd` a besoin de sa durée, un `JeuVideo` de sa
plateforme et de son PEGI.

C'est aussi une bonne question à poser en fin de journée :

> « Pourquoi une seule table avec des colonnes NULL plutôt que trois tables ?
> Quels sont les avantages et les inconvénients ? »

Réponse attendue : simplicité de requêtage et hydratation directe d'un côté,
dénormalisation partielle et colonnes inutilisées de l'autre. L'alternative
(table mère + une table par type, *class table inheritance*) est plus propre
en modélisation mais impose des jointures. Les deux se défendent — ce qui
compte devant le jury, c'est de savoir que le choix a été fait consciemment.

---

## Rappel de posture

Les niveaux 1 sont conçus pour que **personne ne reste bloqué**.
Si un stagiaire cale plus de 10 minutes sur un niveau 1, le problème n'est
pas le TP : c'est un prérequis manquant. Traitez-le individuellement, ne
laissez pas la frustration s'installer.

Le niveau 2 est le **seul niveau réellement obligatoire**. Le niveau 3 est
une soupape pour les rapides, pas un objectif collectif.
