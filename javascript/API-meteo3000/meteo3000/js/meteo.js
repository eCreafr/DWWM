/*
 * ============================================================
 *  meteo.js — Récupération et affichage des prévisions météo
 *  API utilisée : https://www.prevision-meteo.ch/services/json/
 * ============================================================
 *
 *  Ce fichier expose une seule fonction : Meteo(commune)
 *  Elle est appelée depuis script.js quand l'utilisateur
 *  a sélectionné une commune dans la liste déroulante.
 */

/*
 *  FONCTION Meteo(commune)
 *
 *  Paramètre : commune (string) — le nom de la ville sélectionnée
 *
 *  Rôle :
 *    1. Normaliser le nom de la commune pour l'URL
 *    2. Appeler l'API météo avec ce nom
 *    3. Afficher les prévisions sur 3 jours dans le DOM
 */
function Meteo(commune) {
    /*
     *  NORMALISATION DU NOM DE LA COMMUNE
     *
     *  L'API météo attend un nom en minuscules, sans accents ni caractères spéciaux.
     *  Exemple : "Île-de-France" → "ile-de-france"
     *
     *  Étapes :
     *  1. toLowerCase()              → tout en minuscules
     *  2. normalize('NFD')           → décompose les lettres accentuées
     *                                  en caractère de base + diacritique séparé
     *                                  Ex : "é" → "e" + "´"
     *  3. replace(/[̀-ͯ]/g, '') → supprime tous les diacritiques
     *                                       (accents, cédilles...) avec une regex Unicode
     */
    commune = commune.toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '');

    /*
     *  URL DE BASE DE L'API MÉTÉO
     *
     *  L'API de prevision-meteo.ch suit le schéma REST :
     *    https://www.prevision-meteo.ch/services/json/{nom-ville}
     *
     *  Exemple complet :
     *    https://www.prevision-meteo.ch/services/json/paris
     *
     *  Cette API renvoie un objet JSON contenant :
     *    - city_info           → infos sur la ville (nom, position GPS...)
     *    - fcst_day_0          → prévisions pour aujourd'hui
     *    - fcst_day_1          → prévisions pour demain
     *    - fcst_day_2          → prévisions pour après-demain
     *    - (+ fcst_day_3, fcst_day_4...)
     *
     *  Chaque objet fcst_day_X contient :
     *    - day_long   → nom du jour ("Lundi", "Mardi"...)
     *    - tmin / tmax → températures min et max en °C
     *    - icon_big    → URL vers l'icône météo (image PNG)
     *    - condition   → description texte ("Ensoleillé", "Nuageux"...)
     */
    const urlAPI = 'https://www.prevision-meteo.ch/services/json/';

    /*
     *  REQUÊTE HTTP vers l'API météo
     *
     *  On concatène l'URL de base + le nom de la commune normalisé.
     *  Exemple : "https://www.prevision-meteo.ch/services/json/bordeaux"
     *
     *  fetch() déclenche une requête HTTP GET asynchrone.
     */
    fetch(urlAPI + commune)
        /*
         *  TRAITEMENT DE LA RÉPONSE HTTP
         *
         *  Comme dans script.js, on vérifie d'abord que la réponse est OK
         *  (code 200-299) avant de lire le corps JSON.
         *
         *  Si la commune n'existe pas dans l'API, le serveur peut renvoyer
         *  un code 404 (Not Found) — l'erreur sera capturée par .catch().
         */
        .then((response) => {
            if (!response.ok) {
                throw new Error('Ya un souci');
            }
            return response.json();
        })

        /*
         *  EXPLOITATION DES DONNÉES JSON
         *
         *  "myData" est l'objet JavaScript construit à partir du JSON reçu.
         *  On y accède comme à n'importe quel objet JS : myData.city_info.name, etc.
         */
        .then((myData) => {
            /*
             *  On extrait un tableau avec les 3 premiers jours de prévision.
             *  (L'API en fournit jusqu'à 5, on n'en utilise que 3 ici.)
             */
            const jours = [myData.fcst_day_0, myData.fcst_day_1, myData.fcst_day_2];
            console.log(jours); // Outil de débogage : visible dans la console du navigateur (F12)

            // On cible la zone d'affichage dans le DOM
            const mainElt = document.getElementById('main');

            // On vide son contenu pour éviter d'empiler les résultats à chaque appel
            mainElt.innerHTML = '';

            /*
             *  CONSTRUCTION DYNAMIQUE DU DOM
             *
             *  Plutôt que d'écrire du HTML statique, on crée les éléments
             *  directement en JavaScript, puis on les insère dans la page.
             *  C'est ce qu'on appelle la "manipulation du DOM".
             */

            // Titre avec le nom de la ville retourné par l'API
            const h2Elt = document.createElement('h2');
            h2Elt.classList.add('subtitle', 'my-5');
            h2Elt.textContent = 'Météo pour la ville de ' + myData.city_info.name;
            mainElt.appendChild(h2Elt);

            // Conteneur Bootstrap "row" pour aligner les cartes météo côte à côte
            const rowElt = document.createElement('div');
            rowElt.classList.add('row', 'my-3');
            mainElt.appendChild(rowElt);

            /*
             *  BOUCLE sur les 3 jours de prévision
             *
             *  Pour chaque jour, on crée une "carte" avec :
             *    - le nom du jour
             *    - une icône météo (image chargée depuis l'URL fournie par l'API)
             *    - les températures min/max
             *
             *  Les classes Bootstrap col-12 / col-sm-6 / col-md-4 assurent
             *  un affichage responsive :
             *    - Mobile   (< 576px) : 1 carte par ligne (col-12)
             *    - Tablette (≥ 576px) : 2 cartes par ligne (col-sm-6)
             *    - Desktop  (≥ 768px) : 3 cartes par ligne (col-md-4)
             */
            for (let jour of jours) {
                // Colonne Bootstrap / section sémantique pour chaque jour
                let colElt = document.createElement('section');
                colElt.classList.add('col-12', 'col-sm-6', 'col-md-4');
                colElt.style.textAlign = 'center';
                rowElt.appendChild(colElt);

                // Nom du jour (ex: "Lundi")
                let h3Elt = document.createElement('h3');
                h3Elt.textContent = jour.day_long;
                colElt.appendChild(h3Elt);

                /*
                 *  Icône météo
                 *  L'API nous fournit directement une URL vers une image PNG.
                 *  On l'assigne comme source de notre balise <img>.
                 *  Exemple : "https://www.prevision-meteo.ch/style/images/icon/soleil.png"
                 */
                let imageElt = document.createElement('img');
                imageElt.setAttribute('src', jour.icon_big);
                colElt.appendChild(imageElt);

                // Températures min / max
                let tempsElt = document.createElement('p');
                tempsElt.textContent = 'Min : ' + jour.tmin + '°C - Max : ' + jour.tmax + '°C';
                colElt.appendChild(tempsElt);
            }
        })

        /*
         *  GESTION DES ERREURS
         *
         *  .catch() est appelé si :
         *    - la requête réseau échoue (pas de connexion)
         *    - response.ok est false (code HTTP 4xx ou 5xx)
         *    - le JSON est invalide
         *    - n'importe quelle exception levée dans les .then() précédents
         *
         *  Ici, on affiche un message d'erreur à l'utilisateur
         *  directement dans la zone d'affichage.
         */
        .catch((error) => {
            const mainElt = document.getElementById('main');
            mainElt.innerHTML = '';
            mainElt.textContent = 'Pas de météo pour la commune de ' + commune + ' !';
        });
}
