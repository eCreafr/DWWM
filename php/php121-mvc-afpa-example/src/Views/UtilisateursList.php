<?php

use Afpa\Models\Utilisateur;

// définition du titre de la page
$title = "Liste des utilisateurs";
?>

<h2>Utilisateurs</h2>

<?php foreach ($utilisateurs as $utilisateur) { ?>

    <div>
        <h3><?= $utilisateur->getPrenom().' '.$utilisateur->getNom() ?></h3>
        <p><?= $utilisateur->getEmail() ?></p>
    </div>
<?php
} // fin du foreach 
?>

