<?php
/**
 * ============================================
 * CREATE (CRUD) - FORMULAIRE D'AJOUT D'ARTICLE
 * ============================================
 *
 * Ce fichier affiche un formulaire HTML permettant de créer un nouvel article
 * C'est la partie "CREATE" du CRUD (Create, Read, Update, Delete)
 *
 * Fonctionnement :
 * 1. L'utilisateur remplit le formulaire sur cette page
 * 2. Quand il clique sur "Envoyer", les données sont envoyées vers php050-C-post.php
 * 3. Le fichier php050-C-post.php traitera les données et les insérera en base
 */

// ============================================
// CONNEXION À LA BASE DE DONNÉES
// ============================================
// On inclut le fichier de connexion (même si on ne l'utilise pas directement ici)
// C'est une bonne pratique pour vérifier que la connexion BDD fonctionne avant d'afficher le formulaire
include('php050-connect.php');
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'article</title>
    <!-- Bootstrap pour le style du formulaire -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <h1>Ajouter un article</h1>

        <!-- ============================================
             FORMULAIRE D'AJOUT
             ============================================
             - action="php050-C-post.php" : où les données seront envoyées
             - method="POST" : méthode HTTP (POST cache les données dans l'URL, GET les affiche)
        -->
        <form action="php050-C-post.php" method="POST">

            <!-- CHAMP : AUTEUR -->
            <div class="mb-3">
                <label for="auteur" class="form-label">Auteur de l'article</label>
                <!--
                    - id="auteur" : identifiant unique pour le JavaScript/CSS
                    - name="auteur" : nom du champ qui sera récupéré en PHP avec $_POST['auteur']
                    - type="text" : champ de texte simple
                -->
                <input type="text" class="form-control" id="auteur" name="auteur">
            </div>

            <!-- CHAMP : TITRE -->
            <div class="mb-3">
                <label for="titre" class="form-label">Titre de l'article</label>
                <input type="text" class="form-control" id="titre" name="titre" aria-describedby="titre-help">
                <!-- Texte d'aide affiché sous le champ -->
                <div id="titre-help" class="form-text">Choisissez un titre percutant !</div>
            </div>

            <!-- CHAMP : CONTENU -->
            <div class="mb-3">
                <label for="contenu" class="form-label">contenu de l'article</label>
                <!--
                    textarea : champ de texte multiligne
                    placeholder : texte affiché tant que le champ est vide
                -->
                <textarea class="form-control" placeholder="Seulement du contenu vous appartenant ou libre de droits." id="contenu" name="contenu"></textarea>
            </div>

            <!-- BOUTONS D'ACTION -->
            <!-- type="submit" : déclenche l'envoi du formulaire -->
            <button type="submit" class="btn btn-primary">Envoyer</button>
            <br>
            <!-- Lien pour retourner à la liste des articles sans créer d'article -->
            <a class="btn btn-primary" role="button" href="php050-R.php">ou RETOUR</a>

        </form>
    </div>

</body>

</html>

<!--
NOTES PÉDAGOGIQUES :

1. Séparation formulaire / traitement :
   - Ce fichier (php050-C.php) : AFFICHE le formulaire
   - Le fichier php050-C-post.php : TRAITE les données

2. Attributs importants des champs :
   - name : OBLIGATOIRE pour récupérer la valeur en PHP
   - id : pour le JavaScript et les labels
   - type : définit le type de données attendu

3. Méthode POST vs GET :
   - POST : données cachées, pour modifier/créer des données
   - GET : données visibles dans l'URL, pour rechercher/lire des données

4. Sécurité :
   - Ce formulaire n'a pas de validation côté client (JavaScript)
   - Toute la validation sera faite côté serveur dans php050-C-post.php
   - JAMAIS faire confiance aux données utilisateur !

5. Framework Bootstrap :
   - Les classes comme "form-control", "mb-3", "btn btn-primary" viennent de Bootstrap
   - Elles permettent d'avoir un design propre sans écrire de CSS
-->
