# Projet MVC

Exemple de projet basé sur une architecture MVC.

## Démarrer le projet

1. Installer les dépendances avec composer :
```bash
composer install
```

2. Mettre en place la BDD :
```bash
cd docker-db/
docker compose up
```

3. Lancer le serveur de développement :
```bash
php -S localhost:8000 -t public/
```
Liste des paramètres :
- `-S` : adresse et port sur lequel le serveur de développement va être lancé
- `-t` : spécifie le dossier racine de l'application (là où est situé le index.php)