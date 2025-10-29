<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de PDF</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">📄 Upload de PDF</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        // Vérifie si le formulaire a été soumis
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Vérifie si un fichier a été uploadé sans erreur
                            if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {

                                // Récupère les informations du fichier
                                $fileTmpPath = $_FILES['pdf']['tmp_name'];
                                $fileName = $_FILES['pdf']['name'];
                                $fileSize = $_FILES['pdf']['size'];
                                $fileType = $_FILES['pdf']['type'];

                                // Extrait l'extension du fichier et la met en minuscule
                                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                                // Vérifie si c'est bien un PDF
                                if ($fileExtension === 'pdf') {

                                    // Définit le dossier de destination
                                    $uploadDir = 'upload/pdf/';

                                    // Crée le dossier s'il n'existe pas
                                    if (!is_dir($uploadDir)) {
                                        mkdir($uploadDir, 0755, true);
                                    }

                                    // Génère le nom du fichier avec la date du jour et le préfixe AFPA-
                                    // Format: AFPA-AAAA-MM-JJ.pdf (exemple: AFPA-2025-10-29.pdf)
                                    $dateAujourdhui = date('Y-m-d');
                                    $baseFileName = 'AFPA-' . $dateAujourdhui;
                                    $newFileName = $baseFileName . '.pdf';
                                    $destination = $uploadDir . $newFileName;

                                    // Vérifie si un fichier avec ce nom existe déjà
                                    // Si oui, ajoute un suffixe numérique (2, 3, 4, etc.)
                                    $counter = 2;
                                    while (file_exists($destination)) {
                                        $newFileName = $baseFileName . '-' . $counter . '.pdf';
                                        $destination = $uploadDir . $newFileName;
                                        $counter++;
                                    }

                                    // Déplace le fichier du dossier temporaire vers la destination
                                    if (move_uploaded_file($fileTmpPath, $destination)) {
                                        echo '<div class="alert alert-success" role="alert">';
                                        echo '✅ PDF uploadé avec succès !<br>';
                                        echo 'Nom du fichier : <strong>' . htmlspecialchars($newFileName) . '</strong><br>';
                                        echo 'Fichier original : <em>' . htmlspecialchars($fileName) . '</em>';
                                        echo '</div>';

                                        // Affiche un lien de téléchargement
                                        echo '<div class="mt-3 text-center">';
                                        echo '<a href="' . $destination . '" class="btn btn-success" target="_blank">';
                                        echo '📥 Ouvrir le PDF';
                                        echo '</a>';
                                        echo '</div>';
                                    } else {
                                        echo '<div class="alert alert-danger" role="alert">';
                                        echo '❌ Erreur lors du déplacement du fichier.';
                                        echo '</div>';
                                    }
                                } else {
                                    echo '<div class="alert alert-warning" role="alert">';
                                    echo '⚠️ Seuls les fichiers PDF sont acceptés.';
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
                                <label for="pdf" class="form-label">Sélectionner un fichier PDF</label>
                                <input type="file"
                                    class="form-control"
                                    id="pdf"
                                    name="pdf"
                                    accept=".pdf"
                                    required>
                                <div class="form-text">
                                    Le fichier sera automatiquement renommé avec le préfixe AFPA- et la date du jour (format: AFPA-AAAA-MM-JJ.pdf)
                                </div>
                            </div>

                            <div class="alert alert-info" role="alert">
                                <small>
                                    <strong>ℹ️ Information :</strong><br>
                                    Le fichier sera renommé : <strong>AFPA-<?php echo date('Y-m-d'); ?>.pdf</strong><br>
                                    Si un fichier existe déjà, un suffixe numérique sera ajouté (ex: AFPA-<?php echo date('Y-m-d'); ?>-2.pdf)
                                </small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    📤 Uploader le PDF
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