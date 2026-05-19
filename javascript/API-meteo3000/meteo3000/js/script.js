/*
 * ============================================================
 *  script.js — Chargement des départements et communes
 *  API utilisée : https://geo.api.gouv.fr  (API REST publique)
 * ============================================================
 *
 *  Ce fichier orchestre le formulaire de sélection en deux étapes :
 *  1. Au chargement de la page → récupérer tous les départements français
 *  2. Quand l'utilisateur choisit un département → récupérer ses communes
 */


/*
 * ──────────────────────────────────────────────────────────────
 *  ÉTAPE 1 : APPEL API — Liste des départements
 * ──────────────────────────────────────────────────────────────
 *
 *  fetch() est une fonction native du navigateur qui envoie une
 *  REQUÊTE HTTP vers une URL distante.
 *
 *  Ici, on interroge le point d'entrée (endpoint) :
 *    GET https://geo.api.gouv.fr/departements
 *
 *  Méthode HTTP utilisée : GET (lecture seule, on ne modifie rien)
 *
 *  fetch() renvoie une PROMESSE (Promise) :
 *  une valeur qui n'est pas encore connue, mais qui le sera
 *  quand le serveur aura répondu. C'est de la programmation
 *  asynchrone — le reste du script continue sans attendre.
 */
fetch('https://geo.api.gouv.fr/departements')

    /*
     *  .then() s'exécute quand la promesse est résolue (réponse reçue).
     *  Le paramètre "response" est l'objet RÉPONSE HTTP brut.
     *
     *  ANATOMIE D'UNE RÉPONSE HTTP :
     *    - response.status  → code numérique (200 = OK, 404 = introuvable, 500 = erreur serveur...)
     *    - response.ok      → true si le code est entre 200 et 299
     *    - response.json()  → méthode qui lit le corps de la réponse et
     *                         le convertit en objet JavaScript (désérialisation JSON)
     *
     *  On vérifie response.ok avant de continuer pour gérer proprement
     *  les codes d'erreur HTTP (ex : 404, 503...).
     */
    .then((response) => {
        if (!response.ok) {
            // On lève une exception pour court-circuiter la chaîne de .then()
            // et sauter directement dans le .catch() plus bas.
            throw new Error('Problème');
        }
        // response.json() retourne elle-même une Promesse (lecture du flux réseau).
        // En la retournant, on "enchaîne" vers le .then() suivant.
        return response.json();
    })

    /*
     *  .then() suivant : ici, "myData" est le tableau JavaScript
     *  obtenu après désérialisation du JSON.
     *
     *  Exemple de structure JSON reçue :
     *  [
     *    { "code": "01", "nom": "Ain" },
     *    { "code": "02", "nom": "Aisne" },
     *    ...
     *  ]
     */
    .then((myData) => {
        /*global option:writable, Meteo:writable*/

        // On récupère l'élément <select id="departement"> dans le DOM
        const departementElt = document.getElementById('departement');

        // On parcourt le tableau de départements reçu de l'API
        for (option of myData) {
            // Pour chaque département, on crée un élément <option> en JavaScript
            let optionElt = document.createElement('option');

            // Le texte affiché dans la liste déroulante = nom du département
            optionElt.textContent = option.nom;

            // La valeur transmise lors de la sélection = code du département (ex: "75")
            // C'est cette valeur que l'on utilisera dans l'URL de la 2e requête API
            optionElt.setAttribute('value', option.code);

            // On insère l'option dans le <select>
            departementElt.appendChild(optionElt);
        }


        /*
         * ──────────────────────────────────────────────────────────────
         *  ÉTAPE 2 : ÉCOUTEUR D'ÉVÉNEMENT — Sélection d'un département
         * ──────────────────────────────────────────────────────────────
         *
         *  On "écoute" l'événement 'change' sur le <select> des départements.
         *  Cet événement se déclenche chaque fois que l'utilisateur
         *  sélectionne une nouvelle valeur dans la liste.
         */
        departementElt.addEventListener('change', (e) => {

            // e.target = l'élément qui a déclenché l'événement (le <select>)
            // e.target.value = la valeur de l'<option> sélectionnée (le code département)
            let numeroDepartement = e.target.value;

            /*
             *  DEUXIÈME APPEL API — Communes d'un département
             *
             *  On construit l'URL dynamiquement en injectant le code département.
             *  Exemple : GET https://geo.api.gouv.fr/departements/75/communes
             *
             *  C'est un exemple typique d'API REST :
             *  la ressource est identifiée par son URL (/departements/75/communes).
             */
            fetch('https://geo.api.gouv.fr/departements/' + numeroDepartement + '/communes')
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Problème');
                    }
                    return response.json();
                })
                .then((myData) => {
                    /*
                     *  myData est maintenant un tableau de communes.
                     *  Exemple :
                     *  [
                     *    { "code": "75056", "nom": "Paris" },
                     *    { "code": "75101", "nom": "..." },
                     *    ...
                     *  ]
                     */

                    // Tri alphabétique des communes (sur l'objet entier,
                    // fonctionne ici car le navigateur compare les propriétés par défaut)
                    myData.sort();

                    const communeElt = document.getElementById('commune');

                    // On vide la liste des communes précédemment affichée
                    // avant d'en injecter une nouvelle
                    communeElt.innerHTML = '';

                    // Option par défaut (invite de sélection)
                    let optionDefault = document.createElement('option');
                    optionDefault.textContent = 'Choisissez votre commune';
                    optionDefault.setAttribute('selected', 'selected');
                    communeElt.appendChild(optionDefault);

                    // Peuplement de la liste avec les communes du département choisi
                    for (option of myData) {
                        let optionElt = document.createElement('option');
                        optionElt.textContent = option.nom;

                        // La valeur = nom de la commune (utilisé dans l'URL de l'API météo)
                        optionElt.setAttribute('value', option.nom);
                        communeElt.appendChild(optionElt);
                    }


                    /*
                     * ──────────────────────────────────────────────────────────────
                     *  ÉTAPE 3 : ÉCOUTEUR D'ÉVÉNEMENT — Sélection d'une commune
                     * ──────────────────────────────────────────────────────────────
                     *
                     *  Quand l'utilisateur choisit une commune, on appelle
                     *  la fonction Meteo() définie dans meteo.js.
                     *  Cette fonction déclenchera un 3e appel API vers
                     *  le service météo (prevision-meteo.ch).
                     */
                    communeElt.addEventListener('change', (e) => {
                        Meteo(e.target.value);
                    });
                });
        });
    });
