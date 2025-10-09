// ==================== GESTION DU FORMULAIRE DE CONTACT ==================== //
/*
 * Ce script gère l'envoi sécurisé du formulaire de contact avec :
 * - Validation côté client
 * - Protection reCAPTCHA v3 contre les robots
 * - Envoi asynchrone (AJAX) sans rechargement de page
 * - Gestion des messages de succès/erreur
 */

// Écoute l'événement de soumission du formulaire
document.getElementById('contactForm').addEventListener('submit', function (event) {
    // Empêche le rechargement de la page (comportement par défaut du formulaire)
    // On va gérer l'envoi en AJAX pour une meilleure expérience utilisateur
    event.preventDefault();

    // ==================== RÉCUPÉRATION DES DONNÉES DU FORMULAIRE ==================== //
    // .trim() : supprime les espaces au début et à la fin
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const message = document.getElementById('message').value.trim();
    // Élément où afficher les messages de retour
    const formMessage = document.getElementById('formMessage');

    // ==================== VALIDATION CÔTÉ CLIENT ==================== //
    // Vérifie que tous les champs obligatoires sont remplis
    if (name === '' || email === '' || phone === '' || message === '') {
        formMessage.textContent = 'Tous les champs sont obligatoires.';
        formMessage.style.color = 'red';
        return; // Stoppe l'exécution de la fonction
    }

    // ==================== GÉNÉRATION DU TOKEN RECAPTCHA V3 ==================== //
    /*
     * reCAPTCHA v3 fonctionne de manière invisible :
     * - Analyse le comportement de l'utilisateur
     * - Génère un score de 0 (bot) à 1 (humain)
     * - Pas de challenge visuel (contrairement à v2)
     */
    grecaptcha.execute('votre-clef-pub', { action: 'submit' }).then(function (token) {
        // Le token est généré et sera vérifié côté serveur par formulaire.php

        // ==================== ENVOI ASYNCHRONE DES DONNÉES (AJAX/FETCH) ==================== //
        /*
         * fetch() : API moderne pour les requêtes HTTP
         * Permet d'envoyer des données sans recharger la page
         */
        fetch('https://votresite.fr/formulaire.php', {
            method: 'POST', // Méthode HTTP POST pour envoyer des données
            headers: {
                // Indique au serveur le format des données envoyées
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            // Corps de la requête : données du formulaire encodées
            // encodeURIComponent() : encode les caractères spéciaux pour une URL
            body: `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&phone=${encodeURIComponent(phone)}&message=${encodeURIComponent(message)}&recaptcha_token=${encodeURIComponent(token)}`,
        })
            // ==================== TRAITEMENT DE LA RÉPONSE ==================== //
            // Première promesse : convertit la réponse en texte
            .then((response) => response.text())
            // Deuxième promesse : traite le texte reçu
            .then((data) => {
                // Vérifie si le message de succès est présent dans la réponse
                // Opérateur ternaire : condition ? valeur_si_vrai : valeur_si_faux
                formMessage.textContent = data.includes('succes')
                    ? 'Votre message a été envoyé avec succes !'
                    : "Erreur lors de l'envoi du message.";

                // Change la couleur selon le succès ou l'échec
                formMessage.style.color = data.includes('succes') ? 'green' : 'red';

                // Si l'envoi a réussi, réinitialise tous les champs du formulaire
                if (data.includes('succes')) {
                    document.getElementById('contactForm').reset();
                }
            })
            // ==================== GESTION DES ERREURS RÉSEAU ==================== //
            // .catch() : attrape les erreurs (problème de connexion, serveur inaccessible, etc.)
            .catch((error) => {
                formMessage.textContent = 'Erreur réseau. Veuillez réessayer.';
                formMessage.style.color = 'red';
                // Affiche l'erreur dans la console du navigateur (F12) pour le débogage
                console.error('Erreur :', error);
            });
    });
});
