document.getElementById('contactForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Empêche le rechargement de la page

    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const message = document.getElementById('message').value.trim();
    const formMessage = document.getElementById('formMessage');

    // Vérifier que les champs sont remplis
    if (name === '' || email === '' || phone === '' || message === '') {
        formMessage.textContent = 'Tous les champs sont obligatoires.';
        formMessage.style.color = 'red';
        return;
    }

    // Générer le jeton reCAPTCHA v3
    grecaptcha.execute('votre-clef', { action: 'submit' }).then(function (token) {
        // Ajouter le token reCAPTCHA aux données
        fetch('https://votresite.fr/formulaire.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&phone=${encodeURIComponent(phone)}&message=${encodeURIComponent(message)}&recaptcha_token=${encodeURIComponent(token)}`,
        })
            .then((response) => response.text())
            .then((data) => {
                formMessage.textContent = data.includes('succes')
                    ? 'Votre message a été envoyé avec succes !'
                    : "Erreur lors de l'envoi du message.";
                formMessage.style.color = data.includes('succes') ? 'green' : 'red';

                if (data.includes('succes')) {
                    document.getElementById('contactForm').reset(); // Réinitialise le formulaire en cas de succès
                }
            })
            .catch((error) => {
                formMessage.textContent = 'Erreur réseau. Veuillez réessayer.';
                formMessage.style.color = 'red';
                console.error('Erreur :', error);
            });
    });
});
