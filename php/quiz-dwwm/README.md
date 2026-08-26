# Quiz DWWM — évaluation formative back-end

Quiz de révision « esprit Kahoot », **full PHP** (JS/GSAP uniquement pour l'UI).
20 questions back-end (PHP, SQL, POO, MVC) alignées REAC DWWM 2024.

## Arborescence

```
quiz-dwwm/
├── index.php          Saisie du prénom + démarrage
├── quiz.php           Moteur : question → correction → explication
├── resultat.php       Score /20, écriture JSON, envoi mail
├── reset.php          Nouveau stagiaire (reset session)
├── data/
│   ├── questions.json   Les 20 questions (éditable à la main)
│   └── resultats.json   Journal des résultats (source de vérité)
├── includes/
│   └── functions.php    Config + logique (mail, JSON, score)
└── assets/
    ├── style.css        Habillage Kahoot
    └── quiz.js          Animations GSAP
```

## Déploiement sur OVH

1. Uploadez le dossier `quiz-dwwm/` dans votre espace web (FTP/SSH).
2. Rendez le dossier `data/` **inscriptible** : `chmod 664 data/resultats.json` (ou 775 sur le dossier `data/` si besoin).
3. Ouvrez `index.php` dans le navigateur. C'est tout.
4. PHP 8.x recommandé (compatible 7.4). Réglable dans l'espace OVH.

## Configuration mail — À FAIRE

Dans `includes/functions.php`, en haut :

```php
const QUIZ_MAIL_TO   = 'raphael@ecrea.fr';   // destinataire (vous)
const QUIZ_MAIL_FROM = 'quiz@ecrea.fr';      // DOIT être une adresse de votre domaine
```

⚠️ Le `From` **doit** appartenir à `ecrea.fr` (ou au domaine hébergé sur OVH),
sinon SPF/DKIM échouent et le mail part en spam ou est rejeté.
Le **vrai suivi de participation** reste `data/resultats.json` : le mail n'est
qu'un doublon pratique. Si un mail saute, le JSON garde la trace.

## Personnaliser les questions

Éditez `data/questions.json`. Chaque question suit ce modèle :

```json
{
  "id": 1,
  "type": "qcm|sql|php|poo|trou|fonction",   // n'affecte que l'affichage
  "cp": "CP6",                                // mapping REAC (affiché en tag)
  "theme": "SQL — Jointures",
  "question": "Énoncé de la question",
  "code": "bloc de code optionnel (monospace)",
  "options": ["A", "B", "C", "D"],            // 2 à 4 options
  "correct": 1,                               // index de la bonne réponse (0 = A)
  "explication": "Le pourquoi pédagogique affiché après réponse"
}
```

- `correct` est un **index 0-based** (0 = première option).
- 4 options → 4 tuiles colorées ; 2 ou 3 options fonctionnent aussi.
- Ajoutez/retirez des questions librement : le total /N s'adapte tout seul.

## Tester en local (sans OVH)

```bash
cd quiz-dwwm
php -S localhost:8000
# puis ouvrir http://localhost:8000
```

Note : en local, l'envoi de mail échoue silencieusement (pas de serveur SMTP),
mais le reste fonctionne et `resultats.json` se remplit normalement.

## Migration future vers MySQL

Le stockage JSON a été choisi pour faciliter la bascule : il suffira de
remplacer `enregistrer_resultat()` dans `functions.php` par un `INSERT` PDO,
et `charger_questions()` par un `SELECT`. Le reste du moteur ne bouge pas.
