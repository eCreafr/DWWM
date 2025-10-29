<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload d'Images</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">📸 Upload d'Image</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        // Vérifie si le formulaire a été soumis
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Vérifie si un fichier a été uploadé sans erreur
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

                                // Récupère les informations du fichier
                                $fileTmpPath = $_FILES['image']['tmp_name'];
                                $fileName = $_FILES['image']['name'];
                                $fileSize = $_FILES['image']['size'];
                                $fileType = $_FILES['image']['type'];

                                // Fonction pour sanitizer le nom du fichier
                                function sanitizeFilename($filename) {
                                    // Sépare le nom et l'extension
                                    $pathInfo = pathinfo($filename);
                                    $name = $pathInfo['filename'];
                                    $extension = isset($pathInfo['extension']) ? $pathInfo['extension'] : '';

                                    // Met en minuscules
                                    $name = strtolower($name);

                                    // Remplace les espaces par des underscores
                                    $name = str_replace(' ', '_', $name);

                                    // Supprime les accents
                                    $name = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name);

                                    // Supprime tous les caractères non alphanumériques sauf underscore et tiret
                                    $name = preg_replace('/[^a-z0-9_-]/', '', $name);

                                    // Reconstitue le nom avec l'extension
                                    return $extension ? $name . '.' . strtolower($extension) : $name;
                                }

                                // Extrait l'extension du fichier et la met en minuscule
                                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                                // Liste des extensions autorisées
                                $allowedExtensions = ['jpg', 'jpeg', 'webp'];

                                // Vérifie si l'extension est autorisée
                                if (in_array($fileExtension, $allowedExtensions)) {

                                    // Définit le dossier de destination
                                    $uploadDir = 'upload/img/';

                                    // Crée le dossier s'il n'existe pas
                                    if (!is_dir($uploadDir)) {
                                        mkdir($uploadDir, 0755, true);
                                    }

                                    // Sanitize le nom du fichier
                                    $sanitizedFileName = sanitizeFilename($fileName);

                                    // Génère un nom unique pour éviter les conflits
                                    // Format: timestamp_nomoriginal.extension
                                    $newFileName = time() . '_' . $sanitizedFileName;
                                    $destination = $uploadDir . $newFileName;

                                    // Déplace le fichier du dossier temporaire vers la destination
                                    if (move_uploaded_file($fileTmpPath, $destination)) {
                                        echo '<div class="alert alert-success" role="alert">';
                                        echo '✅ Image uploadée avec succès !<br>';
                                        echo 'Fichier : <strong>' . htmlspecialchars($newFileName) . '</strong>';
                                        echo '</div>';

                                        // Affiche un aperçu de l'image
                                        echo '<div class="text-center">';
                                        echo '<img src="' . $destination . '" class="img-fluid rounded" style="max-height: 300px;" alt="Aperçu">';
                                        echo '</div>';
                                    } else {
                                        echo '<div class="alert alert-danger" role="alert">';
                                        echo '❌ Erreur lors du déplacement du fichier.';
                                        echo '</div>';
                                    }
                                } else {
                                    echo '<div class="alert alert-warning" role="alert">';
                                    echo '⚠️ Extension non autorisée. Seuls les fichiers JPG, JPEG et WEBP sont acceptés.';
                                    echo '</div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger" role="alert">';
                                echo '❌ Erreur lors de l\'upload du fichier.';
                                echo '</div>';
                            }
                        }
                        ?>

                        <!-- Formulaire d'upload -->
                        <form method="POST" enctype="multipart/form-data" class="mt-3">
                            <div class="mb-3">
                                <label for="image" class="form-label">Sélectionner une image (JPG, JPEG ou WEBP)</label>
                                <input type="file"
                                    class="form-control"
                                    id="image"
                                    name="image"
                                    accept=".jpg,.jpeg,.webp"
                                    required>
                                <div class="form-text">Formats acceptés : JPG, JPEG, WEBP</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    📤 Uploader l'image
                                </button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>