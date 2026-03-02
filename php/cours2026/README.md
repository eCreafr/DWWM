# 📚 Formation PHP - Plan de cours sur 4 semaines

## 🎯 Objectif
Formation complète PHP pour adultes débutants, du niveau zéro jusqu'à la maîtrise de la Programmation Orientée Objet (POO) et du pattern MVC (Modèle-Vue-Contrôleur).

## 📋 Structure du cours

### **Semaine 1 : Fondamentaux PHP & Syntaxe de base** ✅ COMPLET
1. [Introduction à PHP](semaine1-jour1-introduction-php.html) - Installation, syntaxe de base, echo, var_dump
2. [Variables et types de données](semaine1-jour2-variables-types.html) - Variables, types (string, int, float, bool, array), conversion
3. [Opérateurs et expressions](semaine1-jour3-operateurs-expressions.html) - Arithmétiques, comparaison, logiques, concaténation
4. [Structures conditionnelles](semaine1-jour4-structures-conditionnelles.html) - if/else, switch, ternaire, match (PHP 8)
5. [Boucles](semaine1-jour5-boucles.html) - for, while, foreach, break, continue

**📦 Projet Semaine 1 :** Générateur de tables de multiplication avec mise en forme HTML

---

### **Semaine 2 : Fonctions, Tableaux & Formulaires** ✅ COMPLET
1. [Fonctions](semaine2-jour1-fonctions.html) - Déclaration, paramètres, return, portée (scope), fonctions anonymes
2. [Tableaux - Partie 1](semaine2-jour2-tableaux-base.html) - Tableaux indexés, associatifs, multidimensionnels, count()
3. [Tableaux - Partie 2](semaine2-jour3-tableaux-methodes.html) - array_map, array_filter, array_reduce, sort, implode, explode
4. [Formulaires GET & POST](semaine2-jour4-formulaires-get-post.html) - $_GET, $_POST, $_REQUEST, traitement données formulaire
5. [Validation et sécurité](semaine2-jour5-validation-securite.html) - Validation, sanitization, htmlspecialchars, filter_var, CSRF

**📦 Projet Semaine 2 :** Formulaire de contact complet avec validation et envoi d'email

---

### **Semaine 3 : Base de données & Sessions** ✅ COMPLET
1. [Introduction MySQL](semaine3-jour1-introduction-mysql.html) - Bases SQL, CREATE, INSERT, SELECT, UPDATE, DELETE
2. [PDO & Connexion BDD](semaine3-jour2-pdo-connexion.html) - PDO, connexion sécurisée, requêtes préparées, gestion erreurs
3. [CRUD complet](semaine3-jour3-crud-complet.html) - Create, Read, Update, Delete avec PDO
4. [Sessions & Cookies](semaine3-jour4-sessions-cookies.html) - session_start(), $_SESSION, $_COOKIE, authentification
5. [Système d'authentification](semaine3-jour5-authentification.html) - Login, logout, protection pages, password_hash()

**📦 Projet Semaine 3 :** Application CRUD complète avec authentification (gestion d'articles)

---

### **Semaine 4 : POO & Architecture MVC** ✅ COMPLET
1. [Introduction POO](semaine4-jour1-introduction-poo.html) - Classes, objets, propriétés, méthodes, constructeur
2. [POO avancée](semaine4-jour2-poo-avancee.html) - Héritage, visibilité (public/private/protected), static, constantes
3. [Namespaces & Autoload](semaine4-jour3-namespaces-autoload.html) - Namespaces, use, autoload PSR-4, Composer
4. [Introduction MVC](semaine4-jour4-introduction-mvc.html) - Pattern MVC, routing, contrôleurs, vues, modèles
5. [Projet MVC complet](semaine4-jour5-projet-mvc.html) - Application MVC complète avec POO, BDD, authentification

**📦 Projet Final Semaine 4 :**
- Application MVC complète (Blog ou TODO List)
- Architecture propre avec séparation des responsabilités
- CRUD avec base de données
- Authentification sécurisée
- Routing personnalisé
- POO et bonnes pratiques

---

## 🚀 Comment utiliser ce cours

### 1. Prérequis
- **Serveur local** : XAMPP, WAMP, MAMP ou Laragon installé
- **Éditeur de code** : VS Code recommandé
- **Navigateur** : Chrome ou Firefox avec DevTools

### 2. Suivez l'ordre recommandé
Les cours sont conçus pour être suivis dans l'ordre, chaque leçon s'appuyant sur les précédentes.

### 3. Pratiquez avec les exemples
Chaque cours contient des **exemples de code à tester** directement sur votre serveur local.

### 4. Testez le code
- Placez les fichiers PHP dans votre dossier `htdocs` (XAMPP) ou `www` (WAMP)
- Accédez via `http://localhost/nom-du-fichier.php`

### 5. Consultez la documentation
- [PHP.net](https://www.php.net/manual/fr/) - Documentation officielle PHP
- [W3Schools PHP](https://www.w3schools.com/php/) - Tutoriels et exemples
- [PHP The Right Way](https://phptherightway.com/) - Bonnes pratiques PHP
- [Composer](https://getcomposer.org/) - Gestionnaire de dépendances

---

## 📊 Progression recommandée

| Semaine | Temps estimé | Niveau |
|---------|-------------|--------|
| Semaine 1 | 10-12h | Débutant |
| Semaine 2 | 12-15h | Intermédiaire |
| Semaine 3 | 15-18h | Intermédiaire+ |
| Semaine 4 | 18-20h | Avancé |

**Total :** 55-65 heures de formation

---

## 💡 Conseils pour réussir

### ✅ À faire
- **Installer un serveur local** (XAMPP/WAMP/Laragon)
- **Tester chaque exemple** sur votre machine
- **Créer une base de données** MySQL pour les exercices
- **Lire les messages d'erreur** : PHP est explicite sur les erreurs
- **Utiliser var_dump()** pour déboguer vos variables
- **Consulter la documentation** PHP.net régulièrement
- **Pratiquer les projets** de fin de semaine

### ❌ À éviter
- Lire passivement sans coder
- Copier-coller sans comprendre
- Négliger la sécurité (sanitization, requêtes préparées)
- Utiliser mysql_* (obsolète) au lieu de PDO
- Mélanger logique métier et affichage (d'où l'importance du MVC)
- Oublier de démarrer les sessions (session_start())

---

## 🎓 Compétences acquises

À la fin de cette formation, vous serez capable de :

✅ Maîtriser la syntaxe PHP moderne (PHP 8+)
✅ Manipuler les tableaux et structures de données complexes
✅ Créer et traiter des formulaires sécurisés
✅ Interagir avec une base de données MySQL via PDO
✅ Gérer l'authentification et les sessions utilisateurs
✅ Programmer en Orienté Objet (classes, héritage, encapsulation)
✅ Structurer une application avec le pattern MVC
✅ Utiliser Composer et l'autoloading PSR-4
✅ Appliquer les bonnes pratiques de sécurité PHP
✅ **Créer des applications web complètes et professionnelles**

---

## 🛠️ Outils nécessaires

1. **Serveur local PHP + MySQL**
   - [XAMPP](https://www.apachefriends.org/) (Windows/Mac/Linux) - Recommandé
   - [Laragon](https://laragon.org/) (Windows) - Moderne et rapide
   - [MAMP](https://www.mamp.info/) (Mac/Windows)

2. **Éditeur de code**
   - [Visual Studio Code](https://code.visualstudio.com/) (recommandé)
   - Extensions utiles : PHP Intelephense, PHP Debug, MySQL

3. **Gestionnaire de base de données**
   - phpMyAdmin (inclus avec XAMPP)
   - [MySQL Workbench](https://www.mysql.com/products/workbench/) - Alternative

4. **Optionnel mais utile**
   - [Composer](https://getcomposer.org/) - Gestionnaire de dépendances PHP
   - [Git](https://git-scm.com/) pour versionner vos projets
   - [Postman](https://www.postman.com/) pour tester les APIs

---

## 📁 Structure des fichiers

```
cours2026/
├── index.html                                    # Page d'accueil du cours
├── README.md                                     # Ce fichier
├── semaine1-jour1-introduction-php.html
├── semaine1-jour2-variables-types.html
├── semaine1-jour3-operateurs-expressions.html
├── semaine1-jour4-structures-conditionnelles.html
├── semaine1-jour5-boucles.html
├── semaine2-jour1-fonctions.html
├── semaine2-jour2-tableaux-base.html
├── semaine2-jour3-tableaux-methodes.html
├── semaine2-jour4-formulaires-get-post.html
├── semaine2-jour5-validation-securite.html
├── semaine3-jour1-introduction-mysql.html
├── semaine3-jour2-pdo-connexion.html
├── semaine3-jour3-crud-complet.html
├── semaine3-jour4-sessions-cookies.html
├── semaine3-jour5-authentification.html
├── semaine4-jour1-introduction-poo.html
├── semaine4-jour2-poo-avancee.html
├── semaine4-jour3-namespaces-autoload.html
├── semaine4-jour4-introduction-mvc.html
└── semaine4-jour5-projet-mvc.html
```

---

## 🎯 Roadmap d'apprentissage

### Phase 1 : Fondations (Semaines 1-2)
- Maîtriser la syntaxe PHP de base
- Manipuler les tableaux et structures de données
- Créer et traiter des formulaires sécurisés
- Comprendre GET vs POST

### Phase 2 : Bases de données (Semaine 3)
- Apprendre SQL et MySQL
- Se connecter à une base de données avec PDO
- Créer des applications CRUD complètes
- Gérer l'authentification utilisateur

### Phase 3 : Architecture professionnelle (Semaine 4)
- Maîtriser la Programmation Orientée Objet
- Comprendre et implémenter le pattern MVC
- Organiser le code de manière professionnelle
- Créer des applications maintenables et évolutives

---

## 🔒 Sécurité PHP - Points essentiels

### Toujours appliquer :
- ✅ **Requêtes préparées** (PDO) pour éviter les injections SQL
- ✅ **htmlspecialchars()** pour afficher du contenu utilisateur
- ✅ **password_hash()** et **password_verify()** pour les mots de passe
- ✅ **filter_var()** pour valider les entrées
- ✅ **Validation côté serveur** (jamais uniquement côté client)
- ✅ **Sessions sécurisées** avec session_regenerate_id()
- ✅ **Protection CSRF** pour les formulaires sensibles

### À ne jamais faire :
- ❌ Utiliser mysql_* (obsolète depuis PHP 7)
- ❌ Concaténer des variables dans les requêtes SQL
- ❌ Stocker des mots de passe en clair
- ❌ Faire confiance aux données utilisateur sans validation
- ❌ Afficher les erreurs PHP en production

---

## 🤝 Contribuer

Ce cours est en constante amélioration. Si vous trouvez des erreurs ou avez des suggestions :

1. Ouvrez une issue sur [GitHub](https://github.com/eCreafr/DWWM)
2. Proposez des améliorations
3. Partagez vos projets réalisés avec ce cours !

---

## 📝 Licence

Ce cours est open source et gratuit. Vous êtes libre de l'utiliser, le modifier et le partager.

---

## 🐸 À propos

Cours créé avec ❤️ par **eCrea** pour la formation DWWM (Développeur Web et Web Mobile).

**Bonne formation et bon code ! 🚀**

---

## 🔗 Ressources complémentaires

### Documentation officielle
- [PHP.net](https://www.php.net/manual/fr/) - Documentation complète
- [PHP The Right Way](https://phptherightway.com/) - Guide des bonnes pratiques
- [PSR Standards](https://www.php-fig.org/psr/) - Standards PHP

### Pratique
- [PHP Exercises](https://www.w3resource.com/php-exercises/) - Exercices progressifs
- [Codecademy PHP](https://www.codecademy.com/learn/learn-php) - Cours interactif

### Frameworks (après cette formation)
- [Laravel](https://laravel.com/) - Framework PHP le plus populaire
- [Symfony](https://symfony.com/) - Framework entreprise
- [Slim](https://www.slimframework.com/) - Micro-framework léger

### Outils
- [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) - Vérifier la qualité du code
- [PHPStan](https://phpstan.org/) - Analyse statique du code
- [Composer Packagist](https://packagist.org/) - Dépôt de packages PHP

---

**Dernière mise à jour :** Janvier 2026
