<!--
    Page de contact - php102-contact.php
    =====================================

    Cette page affiche un formulaire permettant aux utilisateurs de contacter le site.
    Si un utilisateur est connecté, ses informations sont pré-remplies.

    📚 Concepts abordés :
    - Formulaire HTML avec méthode POST
    - Pré-remplissage de champs avec des données de session
    - Utilisation de placeholder pour améliorer l'UX
    - Structure de page avec inclusion de composants
-->

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php
    // Chargement des éléments communs : session, DB, CSS
    require_once(__DIR__ . '/head.php');
    ?>
    <title>structure site PHP par section / bloc</title>
</head>

<body>
    <div style="background: #F8F9F9;" class="container-fluid d-flex flex-column justify-content-center ">

        <!-- En-tête du site -->
        <?php require_once(__DIR__ . '/header.php'); ?>

        <!-- === CORPS DE LA PAGE === -->

        <div class="container text-center">
            <h1>Contactez-nous</h1>
            <h2>formulaire :</h2>
        </div>

        <!--
            Formulaire de contact
            =====================

            - method="post" : Les données sont envoyées de manière sécurisée
            - action="submit-contact.php" : Le fichier qui traitera les données
        -->
        <div class="container">
            <form method="post" action="submit-contact.php">

                <!-- Champ Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <!--
                        Pré-remplissage du champ email
                        Si l'utilisateur est connecté, on affiche son email dans le placeholder
                        Cela améliore l'expérience utilisateur (pas besoin de retaper l'email)
                    -->
                    <input type="email"
                        placeholder="<?php if (isset($_SESSION['LOGGED_USER'])) : echo $_SESSION['LOGGED_USER']['email'];
                                        endif; ?>"
                        class="form-control"
                        id="email"
                        name="email"
                        aria-describedby="email-help">
                    <div id="email-help" class="form-text">Nous ne revendrons pas votre email.</div>
                </div>

                <!-- Champ Message -->
                <div class="mb-3">
                    <label for="message" class="form-label">Votre message</label>
                    <!--
                        Textarea pour un message long
                        Le placeholder inclut le nom de l'utilisateur si connecté
                    -->
                    <textarea class="form-control"
                        placeholder="    <?php if (isset($_SESSION['LOGGED_USER'])) : echo $_SESSION['LOGGED_USER']['nom'];
                                            endif; ?>
 que pensez vous du classement du PSG en ce moment ?"
                        id="message"
                        name="message"></textarea>
                </div>

                <!-- Bouton de soumission -->
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>

        <!-- Pied de page -->
        <?php require_once(__DIR__ . '/footer.php'); ?>
    </div>

</body>

</html>
