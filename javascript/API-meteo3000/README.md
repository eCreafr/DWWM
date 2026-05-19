# Meteo 3000 — Exemple pédagogique d'API REST en JavaScript

Projet de démonstration pour une formation **DWWM** (Développeur Web et Web Mobile).  
Il illustre comment consommer plusieurs API REST publiques depuis le navigateur avec `fetch()`.

---

## Sommaire

1. [Présentation du projet](#1-présentation-du-projet)
2. [Qu'est-ce qu'une API ?](#2-quest-ce-quune-api-)
3. [Qu'est-ce qu'une API REST ?](#3-quest-ce-quune-api-rest-)
4. [Le protocole HTTP — bases](#4-le-protocole-http--bases)
5. [Le format JSON](#5-le-format-json)
6. [fetch() et les Promesses](#6-fetch-et-les-promesses)
7. [Architecture du projet](#7-architecture-du-projet)
8. [Les APIs utilisées](#8-les-apis-utilisées)
9. [Flux d'exécution complet](#9-flux-dexécution-complet)
10. [Pour aller plus loin](#10-pour-aller-plus-loin)

---

## 1. Présentation du projet

**Meteo 3000** est une application web qui :

1. Charge la liste des **départements français** depuis l'API officielle du gouvernement.
2. Charge la liste des **communes** d'un département sélectionné.
3. Affiche les **prévisions météo sur 3 jours** pour la commune choisie.

Tout se passe côté navigateur, sans rechargement de page, grâce à **JavaScript asynchrone**.

```
Navigateur
    │
    ├─── GET https://geo.api.gouv.fr/departements         ← liste des depts
    ├─── GET https://geo.api.gouv.fr/departements/69/communes ← communes
    └─── GET https://www.prevision-meteo.ch/services/json/lyon ← météo
```

---

## 2. Qu'est-ce qu'une API ?

**API** = *Application Programming Interface* (Interface de Programmation d'Application)

> Une API est un **contrat** entre deux logiciels : elle définit comment un programme peut demander des services ou des données à un autre programme, sans connaître son fonctionnement interne.

### Analogie du restaurant

| Rôle | Restaurant | API |
|------|-----------|-----|
| Le client | Vous | Votre code JavaScript |
| Le serveur | Le garçon | L'API |
| La cuisine | Les cuisiniers | Le serveur distant |
| La carte | Le menu | La documentation de l'API |

Le client **ne sait pas** comment la cuisine prépare les plats. Il passe simplement sa commande (requête) et reçoit son plat (réponse). L'API joue le rôle d'intermédiaire standardisé.

### Types d'APIs courants

| Type | Description | Exemple |
|------|-------------|---------|
| **REST** | Le plus répandu. Utilise HTTP, retourne du JSON. | API Météo, API Géo |
| **SOAP** | Ancien, utilise XML. Surtout dans les SI d'entreprise. | Services bancaires |
| **GraphQL** | Le client choisit exactement les données qu'il veut. | GitHub API v4 |
| **WebSocket** | Connexion permanente bidirectionnelle. | Messagerie en temps réel |

---

## 3. Qu'est-ce qu'une API REST ?

**REST** = *Representational State Transfer*

REST est un **style d'architecture** (pas un protocole) défini en 2000 par Roy Fielding. Une API est dite "RESTful" si elle respecte ses principes.

### Les 6 principes REST

| Principe | Signification |
|----------|--------------|
| **Client-Serveur** | Le client et le serveur sont indépendants. Le serveur ne connaît pas l'état du client. |
| **Sans état (Stateless)** | Chaque requête est autonome. Le serveur ne mémorise pas les requêtes précédentes. |
| **Mise en cache** | Les réponses peuvent être mises en cache pour réduire la charge réseau. |
| **Interface uniforme** | Les ressources sont identifiées par des URLs cohérentes. |
| **Système en couches** | Le client ne sait pas s'il parle directement au serveur ou à un intermédiaire (proxy, CDN). |
| **Code à la demande** *(optionnel)* | Le serveur peut envoyer du code exécutable (ex : du JavaScript). |

### Les ressources et leurs URLs

En REST, tout est une **ressource** identifiée par une URL unique.

```
GET https://geo.api.gouv.fr/departements
                              └── ressource : "tous les départements"

GET https://geo.api.gouv.fr/departements/69
                              └── ressource : "le département 69"

GET https://geo.api.gouv.fr/departements/69/communes
                              └── ressource : "toutes les communes du département 69"
```

Les URL REST sont **hiérarchiques** et **lisibles par un humain** : on comprend ce qu'on demande rien qu'en lisant l'URL.

### Les méthodes HTTP (verbes REST)

| Méthode | Action | Exemple |
|---------|--------|---------|
| **GET** | Lire une ressource | Afficher la fiche d'un utilisateur |
| **POST** | Créer une ressource | Créer un nouveau compte |
| **PUT** | Remplacer une ressource | Modifier un profil complet |
| **PATCH** | Modifier partiellement | Changer juste le mot de passe |
| **DELETE** | Supprimer une ressource | Supprimer un commentaire |

> Dans ce projet, on n'utilise que **GET** : on lit des données, on ne modifie rien.

---

## 4. Le protocole HTTP — bases

HTTP (*HyperText Transfer Protocol*) est le protocole de communication entre navigateurs et serveurs web.

### Anatomie d'une requête HTTP

```
GET /departements/69/communes HTTP/1.1
Host: geo.api.gouv.fr
Accept: application/json
```

- **Méthode** : GET (lecture)
- **Chemin** : `/departements/69/communes`
- **En-têtes (Headers)** : métadonnées de la requête

### Anatomie d'une réponse HTTP

```
HTTP/1.1 200 OK
Content-Type: application/json

[{ "code": "69123", "nom": "Lyon" }, ...]
```

- **Code de statut** : indique le résultat
- **En-têtes** : type du contenu retourné
- **Corps (Body)** : les données (ici du JSON)

### Codes de statut HTTP courants

| Code | Signification |
|------|--------------|
| `200 OK` | Succès |
| `201 Created` | Ressource créée avec succès |
| `400 Bad Request` | La requête est mal formée |
| `401 Unauthorized` | Authentification requise |
| `403 Forbidden` | Accès refusé |
| `404 Not Found` | Ressource introuvable |
| `500 Internal Server Error` | Erreur côté serveur |

> Dans le code, `response.ok` est `true` si le code est entre **200 et 299**.

---

## 5. Le format JSON

**JSON** = *JavaScript Object Notation*

JSON est le format d'échange de données le plus utilisé avec les API REST. C'est du texte structuré, lisible par un humain et facilement parseable par une machine.

### Structure JSON

```json
{
  "city_info": {
    "name": "Lyon",
    "latitude": 45.7485,
    "longitude": 4.8467
  },
  "fcst_day_0": {
    "day_long": "Mardi",
    "tmin": 12,
    "tmax": 22,
    "condition": "Ensoleillé",
    "icon_big": "https://www.prevision-meteo.ch/style/images/icon/soleil.png"
  }
}
```

### Types de données JSON

| Type | Exemple |
|------|---------|
| Chaîne (string) | `"Lyon"` |
| Nombre (number) | `22`, `45.7` |
| Booléen (boolean) | `true`, `false` |
| Tableau (array) | `["Lyon", "Paris"]` |
| Objet (object) | `{ "nom": "Lyon" }` |
| Null | `null` |

### JSON → JavaScript

La méthode `response.json()` désérialise automatiquement le texte JSON en objet JavaScript :

```js
// JSON reçu (texte brut du serveur)
'{"nom": "Lyon", "tmax": 22}'

// Après response.json() — objet JavaScript utilisable directement
{ nom: "Lyon", tmax: 22 }

// Accès aux propriétés
myData.nom   // → "Lyon"
myData.tmax  // → 22
```

---

## 6. `fetch()` et les Promesses

### Programmation asynchrone

JavaScript est **mono-thread** : il ne peut faire qu'une chose à la fois. Si on attendait la réponse d'un serveur de façon bloquante, toute la page se figerait.

La solution : l'**asynchronisme** avec les **Promesses** (Promises).

### Qu'est-ce qu'une Promesse ?

Une Promise est un objet représentant une valeur qui sera disponible **dans le futur**.

```
Promesse créée
      │
      ├── En attente (pending)  → la requête est en cours
      │
      ├── Résolue (fulfilled)   → réponse reçue, on entre dans .then()
      │
      └── Rejetée (rejected)   → erreur survenue, on entre dans .catch()
```

### La chaîne fetch().then().then().catch()

```js
fetch('https://api.example.com/data')   // 1. Envoie la requête

  .then((response) => {                  // 2. Réponse HTTP reçue
      if (!response.ok) {
          throw new Error('Erreur');     //    Déclenche le .catch()
      }
      return response.json();           //    Désérialise le JSON (autre promesse)
  })

  .then((myData) => {                   // 3. Données JS prêtes à utiliser
      console.log(myData);
  })

  .catch((error) => {                   // 4. Gère toute erreur de la chaîne
      console.error(error);
  });
```

> Les `.then()` se chaînent : chaque `.then()` reçoit ce que le précédent a `return`é.

### Alternative moderne : async/await

`async/await` est du sucre syntaxique par-dessus les Promesses. Le code est plus lisible, mais fait exactement la même chose :

```js
// Avec fetch + .then()
fetch(url)
  .then(r => r.json())
  .then(data => console.log(data))
  .catch(err => console.error(err));

// Avec async/await — équivalent
async function getData() {
    try {
        const response = await fetch(url);
        const data = await response.json();
        console.log(data);
    } catch (err) {
        console.error(err);
    }
}
```

---

## 7. Architecture du projet

```
API-meteo3000/
└── meteo3000/
    ├── index.html       ← Structure de la page (squelette HTML)
    └── js/
        ├── script.js    ← Appels API géographique + gestion des listes déroulantes
        └── meteo.js     ← Fonction Meteo() : appel API météo + affichage des résultats
```

### Séparation des responsabilités

| Fichier | Responsabilité |
|---------|---------------|
| `index.html` | Structure et mise en page. Ne contient aucune logique. |
| `script.js` | Orchestration : départements → communes → appel Meteo() |
| `meteo.js` | Affichage météo : une seule fonction, un seul rôle |

---

## 8. Les APIs utilisées

### API Géo — Gouvernement français

| Propriété | Valeur |
|-----------|--------|
| URL de base | `https://geo.api.gouv.fr` |
| Authentification | Aucune (API publique et gratuite) |
| Format de réponse | JSON |
| Documentation | [geo.api.gouv.fr](https://geo.api.gouv.fr) |

**Endpoints utilisés :**

```
GET /departements
    → Retourne la liste de tous les départements français

GET /departements/{code}/communes
    → Retourne toutes les communes d'un département
```

### API Prévision Météo

| Propriété | Valeur |
|-----------|--------|
| URL de base | `https://www.prevision-meteo.ch/services/json/` |
| Authentification | Aucune (API publique et gratuite) |
| Format de réponse | JSON |
| Site | [prevision-meteo.ch](https://prevision-meteo.ch) |

**Endpoint utilisé :**

```
GET /services/json/{nom-ville}
    → Retourne les prévisions météo sur 5 jours pour la ville
    → Le nom de ville doit être en minuscules, sans accents
```

**Exemple de réponse (extrait) :**

```json
{
  "city_info": { "name": "Lyon" },
  "fcst_day_0": { "day_long": "Mardi", "tmin": 12, "tmax": 22, "icon_big": "..." },
  "fcst_day_1": { "day_long": "Mercredi", "tmin": 10, "tmax": 19, "icon_big": "..." },
  "fcst_day_2": { "day_long": "Jeudi", "tmin": 11, "tmax": 21, "icon_big": "..." }
}
```

---

## 9. Flux d'exécution complet

```
Chargement de la page
        │
        ▼
[script.js] GET /departements
        │
        ▼
Remplissage du <select> département
        │
        │  (l'utilisateur choisit "Rhône")
        ▼
[script.js] GET /departements/69/communes
        │
        ▼
Remplissage du <select> commune
        │
        │  (l'utilisateur choisit "Lyon")
        ▼
[meteo.js] Meteo("Lyon")
    Normalisation : "Lyon" → "lyon"
        │
        ▼
[meteo.js] GET /services/json/lyon
        │
        ▼
Affichage des 3 jours de prévision dans <main>
```

---

## 10. Pour aller plus loin

### Concepts à explorer ensuite

- **API avec authentification** : clé API (API Key), OAuth 2.0, JWT
- **CORS** (*Cross-Origin Resource Sharing*) : pourquoi certaines APIs bloquent les appels depuis un navigateur
- **async/await** : syntaxe moderne équivalente aux `.then()`
- **Axios** : bibliothèque JavaScript alternative à `fetch()`, plus riche en fonctionnalités
- **Variables d'environnement** : comment ne pas mettre ses clés API dans le code source
- **API côté serveur** : créer sa propre API REST avec Node.js + Express, ou PHP + Laravel

### Ressources

- [MDN Web Docs — fetch()](https://developer.mozilla.org/fr/docs/Web/API/fetch)
- [MDN Web Docs — Utiliser les Promesses](https://developer.mozilla.org/fr/docs/Web/JavaScript/Guide/Using_promises)
- [Documentation API Géo](https://geo.api.gouv.fr/decoupage-administratif)
- [HTTP Status Codes — httpstatuses.com](https://httpstatuses.com)
- [JSONPlaceholder](https://jsonplaceholder.typicode.com) — API REST de test pour s'entraîner

---

*Projet réalisé dans le cadre de la formation DWWM — Exemple pédagogique d'utilisation d'APIs REST publiques.*
