<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP 000 | index</title>
    <link rel="stylesheet" href="../html/css/froggie.css" />
</head>

<body>
    <?php
    // Chemin du dossier contenant les fichiers (dossier actuel)
    $folderPath = __DIR__; // Dossier actuel (où se trouve ce script)

    // Vérifier si le dossier existe et est lisible
    if (is_dir($folderPath) && $handle = opendir($folderPath)) {
        echo "<h1>Liste des pages disponibles</h1>";

        $files = []; // Tableau pour stocker les noms de fichiers

        // Lire chaque fichier dans le dossier
        while (($file = readdir($handle)) !== false) {
            // Exclure 'index.php' et les fichiers non .html ou .php
            if ($file !== 'index.php' && in_array(pathinfo($file, PATHINFO_EXTENSION), ['html', 'php'])) {
                $files[] = $file; // Ajouter au tableau
            }
        }

        closedir($handle);

        // Trier les fichiers de manière naturelle (prend en compte les numéros)
        natsort($files);

        // Afficher les fichiers triés
        echo "<ul>";
        foreach ($files as $file) {
            echo "<li><a href='$file'>$file</a></li>";
        }
        echo "</ul>";
    } else {
        echo "Impossible d'accéder au dossier.";
    }
    ?>

    <div class="froggiesplaining">
        <span> Froggiesplaining :</span>
        <img src="../html/img/froggie-300.png" alt="Froggie" class="overfrog" />
    </div>
</body>

</html>