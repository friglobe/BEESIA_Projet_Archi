# ⚡ Cours JavaScript — Architecture Web ISEN-AP3

> **JUNIA — Séance 2** | Bases du langage, manipulation du DOM, événements et préparation à l'asynchrone (fetch/AJAX)
> Application directe au projet de plateforme de centralisation des CV étudiants.

---

## 📚 Sommaire

- **[Partie 1 — Introduction au JavaScript](#partie-1--introduction-au-javascript)**
- **[Partie 2 — Les bases du langage](#partie-2--les-bases-du-langage)**
- **[Partie 3 — Le DOM : manipuler la page](#partie-3--le-dom--manipuler-la-page)**
- **[Partie 4 — Les événements](#partie-4--les-événements)**
- **[Partie 5 — Application au projet CV JUNIA](#partie-5--application-au-projet-cv-junia)**
- **[Partie 6 — fetch et AJAX : préparer la suite PHP](#partie-6--fetch-et-ajax--préparer-la-suite-php)**
- **[Partie 7 — Bonnes pratiques et synthèse](#partie-7--bonnes-pratiques-et-synthèse)**

---

# Partie 1 — Introduction au JavaScript

## 🎯 Objectifs de la séance

- Comprendre le **rôle de JavaScript** dans la stack web (front-end côté navigateur)
- Maîtriser la **syntaxe de base** : variables, types, conditions, boucles, fonctions
- Manipuler le **DOM** : sélectionner, modifier, créer des éléments
- Gérer les **événements** utilisateur (clic, soumission, saisie)
- Découvrir **fetch** pour communiquer avec un serveur (préparation à PHP)
- **Appliquer** ces compétences à la plateforme CV JUNIA

> 💡 **Clé de succès :** Sans JavaScript, ta page HTML/CSS est belle mais figée. JS la rend **vivante** — c'est ce qui transforme une maquette en application.

---

## 🌐 Qu'est-ce que JavaScript ?

**JavaScript** (souvent abrégé **JS**) est le langage de programmation **du navigateur**. Il a été créé en 1995 par Brendan Eich chez Netscape, en 10 jours seulement.

### Place dans la stack web

| Couche | Langage | Rôle |
|--------|---------|------|
| **Structure** | HTML | Le squelette de la page (le **QUOI**) |
| **Présentation** | CSS | L'habillage visuel (le **COMMENT**) |
| **Comportement** | **JavaScript** | L'interactivité (le **QUAND** / le **SI**) |

### Côté client vs côté serveur

```
┌─────────────────────────┐         ┌─────────────────────────┐
│   NAVIGATEUR (client)   │         │   SERVEUR (back-end)    │
│                         │         │                         │
│   HTML + CSS + JS       │ ◄─────► │   PHP + MySQL           │
│   (s'exécute chez       │  HTTP   │   (s'exécute sur la     │
│    l'utilisateur)       │         │    machine distante)    │
└─────────────────────────┘         └─────────────────────────┘
```

> ⚠️ **À ne pas confondre :** JavaScript s'exécute **dans le navigateur de l'utilisateur**, contrairement à PHP qui s'exécute sur le serveur. Les deux peuvent communiquer entre eux (via `fetch` — voir Partie 6).

---

## 📝 Les 3 méthodes d'intégration de JavaScript

### 1️⃣ JS Inline (dans un attribut HTML)

```html
<button onclick="alert('Bouton cliqué !')">Cliquez-moi</button>
```

> ⚠️ **À éviter** : mélange HTML et JS, difficile à maintenir.

### 2️⃣ JS Interne (dans une balise `<script>`)

```html
<head>
    <script>
        console.log("Bonjour JUNIA !");
    </script>
</head>
```

> 👍 Pratique pour les petits tests ou les scripts spécifiques à une page.

### 3️⃣ JS Externe (fichier séparé) — RECOMMANDÉ ✅

```html
<!-- Dans le HTML, juste avant </body> -->
<body>
    <!-- contenu de la page -->
    <script src="js/app.js"></script>
</body>
```

```js
// Dans js/app.js
console.log("Plateforme CV JUNIA — JS chargé");
```

> ✨ **MEILLEURE PRATIQUE** : séparation des responsabilités, réutilisable, plus rapide à charger en cache.

### 💡 Pourquoi placer `<script>` juste avant `</body>` ?

Le HTML est lu **de haut en bas**. Si le script s'exécute avant que le contenu soit chargé, il ne pourra pas accéder aux éléments de la page. Placer le script à la fin garantit que tout le DOM est disponible.

**Alternative moderne :** utiliser l'attribut `defer` dans le `<head>` :

```html
<head>
    <script src="js/app.js" defer></script>
</head>
```

---

## 🛠️ La console : votre meilleur ami

La **console du navigateur** est l'outil de débogage indispensable. Pour l'ouvrir :

- **Chrome / Edge / Firefox** : `F12` ou `Ctrl + Maj + I` (Windows) / `Cmd + Opt + I` (Mac)
- Onglet **"Console"**

### Les méthodes essentielles

```js
console.log("Message simple");          // information
console.warn("Attention !");            // avertissement (jaune)
console.error("Erreur grave !");        // erreur (rouge)
console.table([{nom: "Dupont", age: 22}, {nom: "Martin", age: 23}]);  // tableau formaté
console.log("CV de", nom, "généré");    // plusieurs arguments
```

> 💡 **Astuce JUNIA :** Habituez-vous à ouvrir la console **dès l'ouverture du navigateur**. C'est là que vous verrez toutes vos erreurs JS et comprendrez pourquoi votre code ne marche pas.

---

---

# Partie 2 — Les bases du langage

## 🧱 Variables : `let`, `const`, `var`

Une **variable** est un conteneur nommé qui stocke une valeur.

### Trois mots-clés

```js
let nom = "Dupont";          // variable modifiable
const ANNEE_PROMO = 2026;    // constante (non modifiable)
var prenom = "Marie";        // ancien mot-clé (à éviter en 2026)
```

| Mot-clé | Modifiable ? | Portée | Recommandé ? |
|---------|--------------|--------|--------------|
| `let` | ✅ Oui | Bloc (`{ }`) | ✅ **Oui** |
| `const` | ❌ Non (réassignation) | Bloc (`{ }`) | ✅ **Oui (par défaut)** |
| `var` | ✅ Oui | Fonction | ❌ Non (obsolète) |

### Règles de nommage

```js
// ✅ BIEN
let nomEtudiant = "Dupont";        // camelCase recommandé
let nb_diplomes = 2;                // snake_case toléré
const MAX_CV = 100;                 // CAPS pour les vraies constantes

// ❌ MAL
let 1nom = "X";        // ne peut pas commencer par un chiffre
let mon-nom = "X";     // pas de tirets
let class = "X";       // mot réservé du langage
```

> 💡 **Convention JUNIA :** Utilisez `const` **par défaut**, et passez à `let` uniquement si vous savez que la valeur va changer.

---

## 🔤 Les types de données

### Types primitifs

```js
// String (chaîne de caractères)
let nom = "Dupont";
let prenom = 'Marie';                  // simples ou doubles guillemets
let phrase = `Bonjour ${prenom}`;      // template literal (backticks)

// Number (nombre entier ou décimal)
let age = 22;
let moyenne = 14.5;

// Boolean (vrai/faux)
let estDiplome = true;
let chercheStage = false;

// null (valeur vide volontaire)
let photo = null;

// undefined (valeur non définie)
let dateNaissance;        // automatiquement undefined
```

### Types structurés

```js
// Array (tableau)
let competences = ["HTML", "CSS", "JavaScript"];
console.log(competences[0]);        // "HTML" (index commence à 0)
console.log(competences.length);    // 3

// Object (objet)
let etudiant = {
    nom: "Dupont",
    prenom: "Marie",
    age: 22,
    competences: ["HTML", "CSS"],
    chercheStage: true
};
console.log(etudiant.nom);          // "Dupont"
console.log(etudiant["prenom"]);    // "Marie"
```

### Vérifier le type d'une variable

```js
typeof "Dupont";        // "string"
typeof 22;              // "number"
typeof true;            // "boolean"
typeof [1, 2, 3];       // "object"  (les tableaux sont des objets)
typeof null;            // "object"  (bizarrerie historique du langage)
typeof undefined;       // "undefined"
```

---

## ➕ Opérateurs

### Arithmétiques

```js
let a = 10;
let b = 3;

a + b;    // 13
a - b;    // 7
a * b;    // 30
a / b;    // 3.333...
a % b;    // 1  (modulo : reste de la division)
a ** b;   // 1000  (puissance)
```

### Comparaison (⚠️ TRÈS IMPORTANT)

```js
5 == "5";       // true   (égalité faible : convertit les types)
5 === "5";      // false  (égalité stricte : pas de conversion) ✅ RECOMMANDÉ

5 != "5";       // false
5 !== "5";      // true   ✅ RECOMMANDÉ

5 > 3;          // true
5 >= 5;         // true
5 < 10;         // true
```

> ⚠️ **Règle d'or :** Utilisez TOUJOURS `===` et `!==` (triple égal). Le double égal `==` cause des bugs subtils à cause de la conversion automatique des types.

### Logiques

```js
let a = true;
let b = false;

a && b;     // false   (ET)
a || b;     // true    (OU)
!a;         // false   (NON)
```

### Affectation composée

```js
let total = 10;
total += 5;     // équivalent à : total = total + 5;   → 15
total -= 3;     // 12
total *= 2;     // 24
total /= 4;     // 6
```

---

## 🔀 Conditions

### if / else if / else

```js
let age = 21;

if (age < 18) {
    console.log("Mineur");
} else if (age < 25) {
    console.log("Jeune adulte");
} else {
    console.log("Adulte");
}
```

### Opérateur ternaire (raccourci très utile)

```js
// Syntaxe : condition ? siVrai : siFaux
let statut = age >= 18 ? "Majeur" : "Mineur";

// Application CV : afficher un badge selon le type de recherche
let badge = etudiant.chercheStage ? "🎓 Stage" : "💼 CDI";
```

### switch (pour de multiples cas)

```js
let typeContrat = "alternance";

switch (typeContrat) {
    case "stage":
        console.log("Période d'observation");
        break;
    case "alternance":
        console.log("Contrat d'apprentissage");
        break;
    case "cdi":
        console.log("Contrat à durée indéterminée");
        break;
    default:
        console.log("Type inconnu");
}
```

---

## 🔁 Boucles

### for (boucle classique)

```js
for (let i = 0; i < 5; i++) {
    console.log("Itération", i);
}
// 0, 1, 2, 3, 4
```

### while (tant que)

```js
let compteur = 0;
while (compteur < 3) {
    console.log(compteur);
    compteur++;
}
```

### for...of (parcourir un tableau) ✅ RECOMMANDÉ

```js
const competences = ["HTML", "CSS", "JavaScript"];

for (const competence of competences) {
    console.log(competence);
}
// "HTML", "CSS", "JavaScript"
```

### for...in (parcourir les clés d'un objet)

```js
const etudiant = { nom: "Dupont", prenom: "Marie", age: 22 };

for (const cle in etudiant) {
    console.log(cle, ":", etudiant[cle]);
}
// nom: Dupont
// prenom: Marie
// age: 22
```

### Méthodes de tableau (très puissantes)

```js
const cvs = [
    { nom: "Dupont", age: 22 },
    { nom: "Martin", age: 23 },
    { nom: "Durand", age: 21 }
];

// forEach : exécuter une action sur chaque élément
cvs.forEach(cv => {
    console.log(cv.nom);
});

// map : transformer chaque élément en un nouveau tableau
const noms = cvs.map(cv => cv.nom);
// ["Dupont", "Martin", "Durand"]

// filter : ne garder que les éléments correspondant à une condition
const majeurs = cvs.filter(cv => cv.age >= 22);
// 2 étudiants

// find : trouver le premier élément correspondant
const marie = cvs.find(cv => cv.nom === "Dupont");
```

> 💡 **Astuce JUNIA :** `forEach`, `map`, `filter` et `find` seront partout dans votre code du projet CV. Maîtrisez-les !

---

## 🧰 Fonctions

### Déclaration de fonction classique

```js
function calculerAge(anneeNaissance) {
    const anneeActuelle = new Date().getFullYear();
    return anneeActuelle - anneeNaissance;
}

const age = calculerAge(2003);    // 23
```

### Expression de fonction (anonyme)

```js
const calculerAge = function(anneeNaissance) {
    return new Date().getFullYear() - anneeNaissance;
};
```

### Arrow function (fonction fléchée) — MODERNE ✅

```js
// Version longue
const calculerAge = (anneeNaissance) => {
    return new Date().getFullYear() - anneeNaissance;
};

// Version courte (une seule expression : return implicite)
const calculerAge = anneeNaissance => new Date().getFullYear() - anneeNaissance;
```

### Fonctions avec plusieurs paramètres et valeurs par défaut

```js
function genererCV(nom, prenom, age = 18) {
    return `CV de ${prenom} ${nom}, ${age} ans`;
}

genererCV("Dupont", "Marie", 22);     // "CV de Marie Dupont, 22 ans"
genererCV("Martin", "Paul");          // "CV de Paul Martin, 18 ans" (défaut)
```

### Exemple appliqué au projet CV

```js
// Fonction qui vérifie si un étudiant est éligible à un stage
const estEligibleStage = (etudiant) => {
    return etudiant.age >= 18 && etudiant.chercheStage === true;
};

const marie = { nom: "Dupont", age: 22, chercheStage: true };
console.log(estEligibleStage(marie));    // true
```

---

---

# Partie 3 — Le DOM : manipuler la page

## 🌳 Qu'est-ce que le DOM ?

**DOM** = **Document Object Model** (modèle objet de document).

C'est la **représentation en mémoire** du HTML sous forme d'**arbre**. Chaque balise HTML devient un **nœud** que JavaScript peut lire et modifier.

### Exemple de transformation HTML → DOM

```html
<body>
    <h1>Plateforme CV</h1>
    <p>Bienvenue sur <strong>JUNIA</strong></p>
</body>
```

```
       body
       /  \
      h1   p
       |   |\
   "..."  | strong
          |   |
       "..." "JUNIA"
```

> 💡 **À retenir :** JavaScript ne modifie pas directement le code HTML écrit sur le disque. Il modifie **l'arbre DOM en mémoire**, et le navigateur réaffiche la page.

---

## 🎯 Sélectionner des éléments

### Les méthodes principales

```js
// Par ID (un seul élément)
const titre = document.getElementById("titre-principal");

// Par sélecteur CSS (premier élément trouvé) ✅ RECOMMANDÉ
const bouton = document.querySelector(".btn-convoquer");
const premierLien = document.querySelector("a");
const champEmail = document.querySelector("input[type='email']");

// Par sélecteur CSS (tous les éléments correspondants)
const tousLesBoutons = document.querySelectorAll(".btn");
const tousLesProfils = document.querySelectorAll(".carte-profil");
```

### Comparaison des méthodes

| Méthode | Retourne | Cas d'usage |
|---------|----------|-------------|
| `getElementById("id")` | Un élément ou `null` | Cibler un élément unique par ID |
| `querySelector("...")` | Le **premier** élément | Sélection moderne et flexible ✅ |
| `querySelectorAll("...")` | Une **NodeList** (tous les éléments) | Manipuler plusieurs éléments |

> 💡 **Tip :** `querySelector` accepte n'importe quel sélecteur CSS — classes, IDs, attributs, pseudo-classes. C'est le plus polyvalent.

---

## ✏️ Modifier le contenu

### Modifier le texte

```js
const titre = document.querySelector("h1");

// textContent : texte brut (sécurisé) ✅ RECOMMANDÉ
titre.textContent = "Bienvenue sur JUNIA";

// innerHTML : permet d'injecter du HTML (attention aux failles XSS !)
titre.innerHTML = "Bienvenue sur <strong>JUNIA</strong>";
```

> ⚠️ **Sécurité :** `innerHTML` exécute le HTML reçu. Si vous y mettez du contenu saisi par l'utilisateur sans filtrage, vous risquez une faille **XSS** (Cross-Site Scripting). Préférez `textContent` chaque fois que possible.

### Modifier les attributs

```js
const lien = document.querySelector("a");

lien.href = "https://junia.com";
lien.setAttribute("target", "_blank");
lien.getAttribute("href");               // récupérer un attribut
lien.removeAttribute("target");          // supprimer un attribut

// Pour les images
const photo = document.querySelector("img.photo-cv");
photo.src = "photos/marie.jpg";
photo.alt = "Photo de Marie Dupont";
```

### Modifier les styles inline

```js
const bouton = document.querySelector(".btn-convoquer");

bouton.style.backgroundColor = "#F39200";    // attention : camelCase !
bouton.style.color = "white";
bouton.style.padding = "12px 24px";
bouton.style.borderRadius = "8px";
```

> ⚠️ **Attention au camelCase :** En CSS on écrit `background-color`, en JS on écrit `backgroundColor`. Tirets remplacés par majuscules.

### Modifier les classes (RECOMMANDÉ ✅)

```js
const carte = document.querySelector(".carte-profil");

carte.classList.add("convoque");                 // ajouter une classe
carte.classList.remove("nouveau");               // retirer une classe
carte.classList.toggle("favori");                // ajouter si absente, retirer si présente
carte.classList.contains("convoque");            // true / false
```

> 💡 **Pourquoi `classList` plutôt que `style` ?** Parce que tout le style reste dans le CSS (séparation fond/forme). Le JS se contente d'ajouter/retirer des classes, et le CSS fait le rendu visuel.

---

## ➕ Créer et supprimer des éléments

### Créer un nouvel élément

```js
// Créer une nouvelle carte de profil
const carte = document.createElement("div");
carte.classList.add("carte-profil");
carte.innerHTML = `
    <h3>Marie Dupont</h3>
    <p>Cherche : Stage de 5e année</p>
    <button class="btn-convoquer">Convoquer</button>
`;

// L'ajouter au DOM
const catalogue = document.querySelector(".catalogue");
catalogue.appendChild(carte);
```

### Supprimer un élément

```js
const ancienneCarte = document.querySelector(".carte-profil.archivee");
ancienneCarte.remove();
```

### Naviguer dans l'arbre DOM

```js
const carte = document.querySelector(".carte-profil");

carte.parentElement;       // élément parent
carte.children;            // enfants directs
carte.firstElementChild;   // premier enfant
carte.lastElementChild;    // dernier enfant
carte.nextElementSibling;  // frère suivant
```

---

---

# Partie 4 — Les événements

## 🎬 Réagir aux actions de l'utilisateur

Un **événement** est une action déclenchée par l'utilisateur (clic, frappe au clavier, soumission de formulaire...) ou par le navigateur (chargement de page, redimensionnement...).

### La méthode `addEventListener`

```js
const bouton = document.querySelector(".btn-convoquer");

bouton.addEventListener("click", () => {
    alert("Candidat convoqué !");
});
```

**Syntaxe :** `element.addEventListener("nom-événement", fonction-à-exécuter);`

---

## 📋 Les événements courants

### Événements de souris

```js
bouton.addEventListener("click", handler);        // clic
bouton.addEventListener("dblclick", handler);     // double-clic
bouton.addEventListener("mouseover", handler);    // survol
bouton.addEventListener("mouseout", handler);     // sortie de survol
```

### Événements de formulaire

```js
formulaire.addEventListener("submit", handler);   // soumission
champ.addEventListener("input", handler);         // saisie (à chaque frappe)
champ.addEventListener("change", handler);        // changement validé
champ.addEventListener("focus", handler);         // entrée dans le champ
champ.addEventListener("blur", handler);          // sortie du champ
```

### Événements de clavier

```js
document.addEventListener("keydown", handler);    // touche enfoncée
document.addEventListener("keyup", handler);      // touche relâchée
```

### Événements du document / fenêtre

```js
window.addEventListener("load", handler);         // page entièrement chargée
window.addEventListener("resize", handler);       // redimensionnement
window.addEventListener("scroll", handler);       // défilement
document.addEventListener("DOMContentLoaded", handler);  // DOM prêt
```

---

## 🎯 L'objet `event`

Quand un événement se déclenche, le navigateur passe automatiquement un **objet `event`** à votre fonction, contenant toutes les informations utiles.

```js
const formulaire = document.querySelector("#form-cv");

formulaire.addEventListener("submit", (event) => {
    event.preventDefault();    // ⚠️ ESSENTIEL : empêche le rechargement de la page
    console.log("Formulaire soumis !");
    console.log("Cible :", event.target);
});
```

### `event.preventDefault()` — l'incontournable

Par défaut, certains événements ont un **comportement natif** :
- Un `submit` recharge la page
- Un clic sur un lien `<a>` navigue vers l'URL
- Une touche `Entrée` dans un champ soumet le formulaire

`preventDefault()` **bloque ce comportement** pour que vous puissiez gérer l'action en JS.

### `event.target` — l'élément déclencheur

```js
document.querySelectorAll(".btn-convoquer").forEach(btn => {
    btn.addEventListener("click", (event) => {
        const carte = event.target.closest(".carte-profil");
        console.log("Profil convoqué :", carte.dataset.id);
    });
});
```

---

---

# Partie 5 — Application au projet CV JUNIA

Mettons maintenant tout en pratique sur les fonctionnalités concrètes de la plateforme.

## 💡 Démo 1 — Validation en direct du formulaire CV

**Contexte :** L'étudiant remplit son CV. On veut lui donner un feedback **immédiat** sur la validité de chaque champ, sans attendre la soumission.

### Le HTML de départ

```html
<form id="form-cv">
    <label for="email">Email JUNIA</label>
    <input type="email" id="email" required>
    <span class="erreur" id="erreur-email"></span>

    <label for="motivation">Lettre de motivation</label>
    <textarea id="motivation" maxlength="500"></textarea>
    <span class="compteur" id="compteur-motivation">0 / 500 caractères</span>

    <button type="submit">Générer mon CV</button>
</form>
```

### Le JavaScript

```js
// === Validation de l'email JUNIA en direct ===
const champEmail = document.querySelector("#email");
const erreurEmail = document.querySelector("#erreur-email");

champEmail.addEventListener("input", () => {
    const email = champEmail.value;
    const estEmailJunia = email.endsWith("@junia.com");

    if (email === "") {
        erreurEmail.textContent = "";
        champEmail.classList.remove("invalide", "valide");
    } else if (!estEmailJunia) {
        erreurEmail.textContent = "❌ Utilisez votre adresse @junia.com";
        champEmail.classList.add("invalide");
        champEmail.classList.remove("valide");
    } else {
        erreurEmail.textContent = "✅ Email valide";
        champEmail.classList.add("valide");
        champEmail.classList.remove("invalide");
    }
});

// === Compteur de caractères pour la lettre de motivation ===
const champMotivation = document.querySelector("#motivation");
const compteur = document.querySelector("#compteur-motivation");

champMotivation.addEventListener("input", () => {
    const longueur = champMotivation.value.length;
    compteur.textContent = `${longueur} / 500 caractères`;

    // Colorer en orange quand on approche de la limite
    if (longueur > 450) {
        compteur.style.color = "#F39200";
    } else {
        compteur.style.color = "#6B2C91";
    }
});

// === Validation globale à la soumission ===
const formulaire = document.querySelector("#form-cv");

formulaire.addEventListener("submit", (event) => {
    event.preventDefault();

    if (!champEmail.value.endsWith("@junia.com")) {
        alert("Merci d'utiliser votre adresse JUNIA");
        return;
    }

    console.log("✅ CV soumis :", {
        email: champEmail.value,
        motivation: champMotivation.value
    });
    // Plus tard : envoi au serveur PHP (voir Partie 6)
});
```

### Le CSS qui va avec

```css
.invalide {
    border: 2px solid #e74c3c;
    background-color: #fdf2f2;
}

.valide {
    border: 2px solid #27ae60;
    background-color: #f0faf4;
}

.erreur {
    color: #e74c3c;
    font-size: 0.9rem;
}
```

---

## 💡 Démo 2 — Bouton "Convoquer" interactif (avec modale de confirmation)

**Contexte :** Côté entreprise, un recruteur consulte le catalogue de profils. Au clic sur "Convoquer", on affiche une **modale** demandant confirmation, puis on simule l'envoi d'un courriel.

### Le HTML

```html
<div class="carte-profil" data-id="42" data-nom="Marie Dupont">
    <h3>Marie Dupont</h3>
    <p>Cherche : Alternance</p>
    <button class="btn-convoquer">📩 Convoquer</button>
</div>

<!-- Modale (cachée par défaut) -->
<div id="modale-convocation" class="modale cachee">
    <div class="modale-contenu">
        <h2>Confirmer la convocation</h2>
        <p id="modale-texte"></p>
        <label for="date-entretien">Date proposée</label>
        <input type="date" id="date-entretien">
        <div class="modale-actions">
            <button id="annuler">Annuler</button>
            <button id="confirmer">Envoyer la convocation</button>
        </div>
    </div>
</div>
```

### Le JavaScript

```js
const modale = document.querySelector("#modale-convocation");
const modaleTexte = document.querySelector("#modale-texte");
const boutonAnnuler = document.querySelector("#annuler");
const boutonConfirmer = document.querySelector("#confirmer");

let profilSelectionne = null;

// === Ouvrir la modale au clic sur "Convoquer" ===
document.querySelectorAll(".btn-convoquer").forEach(bouton => {
    bouton.addEventListener("click", (event) => {
        const carte = event.target.closest(".carte-profil");
        profilSelectionne = {
            id: carte.dataset.id,
            nom: carte.dataset.nom
        };

        modaleTexte.textContent = `Convoquer ${profilSelectionne.nom} ?`;
        modale.classList.remove("cachee");
    });
});

// === Fermer la modale ===
boutonAnnuler.addEventListener("click", () => {
    modale.classList.add("cachee");
    profilSelectionne = null;
});

// === Confirmer la convocation ===
boutonConfirmer.addEventListener("click", () => {
    const date = document.querySelector("#date-entretien").value;

    if (!date) {
        alert("Choisissez une date d'entretien.");
        return;
    }

    // Simulation d'envoi (plus tard, on appellera le serveur via fetch)
    console.log("📩 Convocation envoyée :", {
        profil: profilSelectionne,
        date: date
    });

    afficherNotification(`Convocation envoyée à ${profilSelectionne.nom} pour le ${date}`);
    modale.classList.add("cachee");
});

// === Notification éphémère ===
const afficherNotification = (message) => {
    const notif = document.createElement("div");
    notif.classList.add("notification");
    notif.textContent = message;
    document.body.appendChild(notif);

    setTimeout(() => {
        notif.remove();
    }, 3000);
};
```

### Le CSS associé

```css
.modale {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modale.cachee {
    display: none;
}

.modale-contenu {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    max-width: 500px;
    border-top: 4px solid #6B2C91;
}

.notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #F39200;
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
```

> 💡 **Notez l'usage de `data-id` et `data-nom`** sur les cartes HTML. Ces **attributs `data-*`** permettent de stocker des informations propres à chaque carte, qu'on récupère en JS via `element.dataset.nom`.

---

## 💡 Démo 3 — Filtrage dynamique du catalogue de profils

**Contexte :** Côté entreprise, on veut filtrer les profils par **type de contrat recherché** et par **mot-clé** dans le nom ou les compétences, en temps réel.

### Le HTML

```html
<div class="filtres">
    <input type="text" id="recherche" placeholder="🔍 Rechercher par nom ou compétence">

    <select id="filtre-contrat">
        <option value="">Tous les contrats</option>
        <option value="stage">Stage</option>
        <option value="alternance">Alternance</option>
        <option value="cdi">CDI</option>
    </select>
</div>

<div class="catalogue" id="catalogue">
    <!-- les cartes seront générées en JS -->
</div>

<p id="aucun-resultat" class="cachee">Aucun profil ne correspond à votre recherche.</p>
```

### Le JavaScript

```js
// === Données simulées (plus tard chargées depuis MySQL via PHP) ===
const profils = [
    { id: 1, nom: "Marie Dupont",    contrat: "stage",      competences: ["HTML", "CSS", "JavaScript"] },
    { id: 2, nom: "Paul Martin",     contrat: "alternance", competences: ["PHP", "MySQL", "JavaScript"] },
    { id: 3, nom: "Sophie Bernard",  contrat: "cdi",        competences: ["React", "Node.js"] },
    { id: 4, nom: "Lucas Durand",    contrat: "alternance", competences: ["Python", "Data Science"] },
    { id: 5, nom: "Emma Lefevre",    contrat: "stage",      competences: ["Marketing", "SEO"] }
];

const catalogue = document.querySelector("#catalogue");
const champRecherche = document.querySelector("#recherche");
const filtreContrat = document.querySelector("#filtre-contrat");
const messageAucun = document.querySelector("#aucun-resultat");

// === Fonction qui affiche les profils filtrés ===
const afficherProfils = (liste) => {
    catalogue.innerHTML = "";    // vider d'abord

    if (liste.length === 0) {
        messageAucun.classList.remove("cachee");
        return;
    }
    messageAucun.classList.add("cachee");

    liste.forEach(profil => {
        const carte = document.createElement("div");
        carte.classList.add("carte-profil");
        carte.dataset.id = profil.id;
        carte.dataset.nom = profil.nom;
        carte.innerHTML = `
            <h3>${profil.nom}</h3>
            <p>Contrat : ${profil.contrat}</p>
            <p>Compétences : ${profil.competences.join(", ")}</p>
            <button class="btn-convoquer">📩 Convoquer</button>
        `;
        catalogue.appendChild(carte);
    });
};

// === Fonction de filtrage ===
const filtrer = () => {
    const motCle = champRecherche.value.toLowerCase();
    const contratChoisi = filtreContrat.value;

    const resultats = profils.filter(profil => {
        // Filtre par contrat
        const matchContrat = contratChoisi === "" || profil.contrat === contratChoisi;

        // Filtre par mot-clé (nom ou compétences)
        const matchMotCle = motCle === ""
            || profil.nom.toLowerCase().includes(motCle)
            || profil.competences.some(c => c.toLowerCase().includes(motCle));

        return matchContrat && matchMotCle;
    });

    afficherProfils(resultats);
};

// === Écouteurs d'événements ===
champRecherche.addEventListener("input", filtrer);
filtreContrat.addEventListener("change", filtrer);

// === Affichage initial ===
afficherProfils(profils);
```

> 💡 **Décryptage du filtre :** `Array.filter()` renvoie un nouveau tableau ne contenant que les éléments pour lesquels la fonction retourne `true`. On combine deux conditions avec `&&` — chaque profil doit passer **les deux filtres**.

---

---

# Partie 6 — fetch et AJAX : préparer la suite PHP

## 🌐 Pourquoi `fetch` ?

Jusqu'ici, nos données sont **codées en dur** dans le JavaScript (`const profils = [...]`). En réalité, les données viendront d'une **base MySQL**, interrogée par un script **PHP** côté serveur.

La communication client ↔ serveur se fait via **HTTP**, sans recharger la page. C'est ce qu'on appelle **AJAX** (Asynchronous JavaScript And XML), et la méthode moderne pour le faire est **`fetch`**.

```
┌─────────────────┐                              ┌─────────────────┐
│   NAVIGATEUR    │   1. fetch("api/profils")   │     SERVEUR     │
│                 │ ──────────────────────────► │                 │
│                 │                              │   profils.php   │
│   JavaScript    │                              │       ↓         │
│       ↑         │                              │     MySQL       │
│       │         │   2. Réponse JSON           │                 │
│  Affichage      │ ◄────────────────────────── │                 │
└─────────────────┘                              └─────────────────┘
```

---

## ⏱️ Synchrone vs asynchrone

### Code synchrone (séquentiel)

```js
console.log("1");
console.log("2");
console.log("3");
// Affiche : 1, 2, 3 dans l'ordre
```

### Code asynchrone (en attente d'une réponse)

```js
console.log("1");
fetch("api/profils.php").then(reponse => {
    console.log("2");    // arrive APRÈS le serveur a répondu
});
console.log("3");
// Affiche : 1, 3, 2  (car fetch attend la réponse du serveur)
```

> 💡 **À retenir :** une requête réseau prend du temps (10 ms à plusieurs secondes). JS ne **bloque pas** la page en attendant — il continue à exécuter le reste du code, et **revient** plus tard quand la réponse arrive.

---

## 🤝 Les Promises (promesses)

Une **Promise** est un objet qui représente une **valeur future** : un résultat qui sera disponible plus tard.

### États d'une promesse

- **pending** (en attente) → la requête est en cours
- **fulfilled** (réussie) → on a la réponse
- **rejected** (échouée) → erreur réseau, serveur, etc.

### Syntaxe `.then() / .catch()`

```js
fetch("api/profils.php")
    .then(reponse => reponse.json())     // convertir la réponse en objet JS
    .then(donnees => {
        console.log("Profils reçus :", donnees);
    })
    .catch(erreur => {
        console.error("Erreur :", erreur);
    });
```

### Syntaxe `async / await` (plus lisible) ✅ RECOMMANDÉ

```js
const chargerProfils = async () => {
    try {
        const reponse = await fetch("api/profils.php");
        const donnees = await reponse.json();
        console.log("Profils reçus :", donnees);
    } catch (erreur) {
        console.error("Erreur :", erreur);
    }
};

chargerProfils();
```

> 💡 **`async/await` n'est qu'une écriture plus claire de `.then()`.** Les deux fonctionnent, mais `async/await` ressemble à du code synchrone et est plus facile à lire.

---

## 📥 Requête GET — lire des données

**Cas d'usage :** charger la liste des profils depuis le serveur au chargement de la page.

```js
const chargerProfils = async () => {
    try {
        const reponse = await fetch("api/profils.php");

        if (!reponse.ok) {
            throw new Error(`Erreur HTTP : ${reponse.status}`);
        }

        const profils = await reponse.json();
        afficherProfils(profils);
    } catch (erreur) {
        console.error("Impossible de charger les profils :", erreur);
        document.querySelector("#catalogue").textContent
            = "⚠️ Erreur de chargement. Réessayez plus tard.";
    }
};

// Au chargement de la page
document.addEventListener("DOMContentLoaded", chargerProfils);
```

### Le PHP qui répond (aperçu — séance suivante)

```php
<?php
// api/profils.php
header("Content-Type: application/json");

// Connexion MySQL + requête (vu en séance PHP)
$profils = [
    ["id" => 1, "nom" => "Marie Dupont", "contrat" => "stage"],
    ["id" => 2, "nom" => "Paul Martin",  "contrat" => "alternance"]
];

echo json_encode($profils);
```

> 💡 **JSON** (JavaScript Object Notation) est le **format d'échange** entre PHP et JS. C'est simplement du texte qui ressemble à un objet JavaScript.

---

## 📤 Requête POST — envoyer des données

**Cas d'usage :** soumettre le formulaire CV pour l'enregistrer en base.

```js
const formulaire = document.querySelector("#form-cv");

formulaire.addEventListener("submit", async (event) => {
    event.preventDefault();

    // Récupérer toutes les données du formulaire
    const donnees = {
        nom: document.querySelector("#nom").value,
        prenom: document.querySelector("#prenom").value,
        email: document.querySelector("#email").value,
        motivation: document.querySelector("#motivation").value,
        competences: Array.from(document.querySelectorAll("input[name='competences']:checked"))
                          .map(c => c.value)
    };

    try {
        const reponse = await fetch("api/enregistrer-cv.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(donnees)
        });

        const resultat = await reponse.json();

        if (resultat.success) {
            alert("✅ CV enregistré avec succès !");
            formulaire.reset();
        } else {
            alert("❌ Erreur : " + resultat.message);
        }
    } catch (erreur) {
        console.error("Erreur réseau :", erreur);
    }
});
```

### Décryptage de `fetch` avec POST

| Élément | Rôle |
|---------|------|
| `method: "POST"` | Type de requête (vs `"GET"` par défaut) |
| `headers` | Métadonnées de la requête (format des données envoyées) |
| `"Content-Type": "application/json"` | On annonce qu'on envoie du JSON |
| `body` | Le contenu envoyé au serveur |
| `JSON.stringify(donnees)` | Convertit un objet JS en chaîne JSON |

---

## 🔧 Outils complémentaires : FormData

Pour les formulaires complexes (avec fichiers, photos...), il existe une alternative à JSON : **FormData**.

```js
formulaire.addEventListener("submit", async (event) => {
    event.preventDefault();

    // FormData ramasse automatiquement tous les champs du formulaire
    const donnees = new FormData(formulaire);

    const reponse = await fetch("api/enregistrer-cv.php", {
        method: "POST",
        body: donnees     // pas besoin de headers ni de stringify
    });

    const resultat = await reponse.json();
    console.log(resultat);
});
```

> 💡 **FormData est indispensable pour l'envoi de fichiers** (photo de profil sur le CV par exemple). PHP les récupère ensuite via `$_POST` et `$_FILES`, comme un formulaire HTML classique.

---

## 🎯 Application : catalogue dynamique avec fetch

Reprenons la **Démo 3** mais avec un chargement depuis le serveur :

```js
let profils = [];    // sera rempli par fetch

const chargerProfils = async () => {
    try {
        const reponse = await fetch("api/profils.php");
        profils = await reponse.json();
        afficherProfils(profils);
    } catch (erreur) {
        console.error("Erreur :", erreur);
    }
};

// Le reste du code (filtrage, affichage) ne change pas !
// On a juste remplacé les données en dur par un appel réseau.

document.addEventListener("DOMContentLoaded", chargerProfils);
```

> 💡 **Bonne nouvelle :** la séparation entre **données** et **affichage** dans nos Démos 1-3 permet de remplacer facilement le tableau codé en dur par un `fetch`. C'est l'architecture qu'on cherche.

---

---

# Partie 7 — Bonnes pratiques et synthèse

## ✅ Bonnes pratiques JavaScript

### Lisibilité du code

```js
// ❌ MAUVAIS
const f = (x) => {return x.filter(p=>p.a==="x"&&p.b>10).map(p=>p.n);};

// ✅ BON
const filtrerProfilsAdultes = (profils) => {
    return profils
        .filter(profil => profil.contrat === "alternance" && profil.age > 18)
        .map(profil => profil.nom);
};
```

### Nommage explicite

```js
// ❌ MAUVAIS
let x = 22;
let arr = [];
const fn = () => {};

// ✅ BON
let ageEtudiant = 22;
let profilsConvoques = [];
const calculerAge = () => {};
```

### Toujours utiliser `===`

```js
// ❌ Source de bugs
if (age == "22") { }

// ✅ Sûr
if (age === 22) { }
```

### Gérer les erreurs

```js
// ❌ Pas de filet de sécurité
const data = await fetch("api/profils.php");
const profils = await data.json();

// ✅ Avec try/catch
try {
    const data = await fetch("api/profils.php");
    if (!data.ok) throw new Error("Serveur indisponible");
    const profils = await data.json();
} catch (erreur) {
    afficherMessageErreur("Impossible de charger les profils.");
}
```

### Séparer JS / CSS / HTML

```js
// ❌ Mauvais : style dans le JS
bouton.style.backgroundColor = "#F39200";
bouton.style.color = "white";
bouton.style.padding = "12px 24px";

// ✅ Bon : on ajoute juste une classe
bouton.classList.add("btn-actif");
// (le style est dans le CSS)
```

### Organisation des fichiers du projet

```
plateforme-cv/
├── index.html
├── catalogue.html
├── css/
│   ├── style.css
│   └── responsive.css
├── js/
│   ├── app.js              ← code commun (navigation, modales)
│   ├── form-cv.js          ← validation du formulaire CV
│   └── catalogue.js        ← filtrage et chargement du catalogue
└── api/                    ← scripts PHP (séance suivante)
    ├── profils.php
    └── enregistrer-cv.php
```

---

## 📚 Ressources utiles

### Documentation
- **MDN Web Docs** ([developer.mozilla.org](https://developer.mozilla.org)) : référence officielle
- **JavaScript.info** : tutoriels modernes et progressifs
- **DevDocs** : documentation hors ligne

### Outils
- **Console du navigateur** (F12) : indispensable au quotidien
- **JSBin / CodePen** : tester rapidement du code
- **ESLint** : détecte automatiquement les erreurs de style

### Pour aller plus loin (après le projet)
- **Frameworks front** : React, Vue.js, Svelte
- **Node.js** : JavaScript côté serveur
- **TypeScript** : JavaScript typé pour les gros projets

---

## 🎓 Synthèse de la séance 2

### Compétences acquises

| Bloc | Compétences |
|------|-------------|
| **Bases du langage** | Variables (`let`/`const`), types, opérateurs, conditions, boucles, fonctions (classiques + arrow) |
| **DOM** | Sélection (`querySelector`), modification (`textContent`, `classList`), création/suppression d'éléments |
| **Événements** | `addEventListener`, `event.preventDefault()`, `event.target`, événements de formulaire et de souris |
| **fetch / AJAX** | Requêtes GET et POST, Promises, `async/await`, gestion d'erreurs avec try/catch |

### Application au projet JUNIA

Tout ce qui a été vu sera mobilisé dans la **plateforme de centralisation des CV** :

1. **Validation en direct** du formulaire CV (email JUNIA, compteur de caractères, indication visuelle)
2. **Bouton "Convoquer" interactif** avec modale de confirmation et notification
3. **Catalogue filtrable** par type de contrat et mot-clé, en temps réel
4. **Communication client/serveur** : les données ne seront plus codées en dur mais chargées depuis MySQL via des scripts PHP (séance 3)

### Prochaine étape : séance 3

- **PHP** : syntaxe, variables, structures de contrôle
- **PHP-MySQL** : connexion, requêtes, extraction des données
- **Côté serveur** : reprise des `fetch` du jour pour les **brancher** sur de vrais scripts PHP
- **Sessions et cookies** : authentification des étudiants et des entreprises

---

## 🛠️ Atelier de fin de séance

### 🎯 Mission : ajouter de l'interactivité au formulaire CV (TP de séance 1)

**Durée :** 1h30

1. Ouvrez le formulaire CV créé en séance 1
2. Créez un fichier `js/form-cv.js` lié dans votre HTML
3. Implémentez les fonctionnalités suivantes :
   - ✅ Validation en direct de l'email JUNIA (`@junia.com`)
   - ✅ Compteur de caractères pour la lettre de motivation (max 500)
   - ✅ Affichage d'un aperçu du CV en bas de page **au fur et à mesure** de la saisie
   - ✅ Au clic sur "Générer", affichage d'une notification de confirmation
4. **Bonus :** sauvegarder les données dans `localStorage` pour les retrouver au rechargement de la page

### Critères d'évaluation (40%)

- Code propre, commenté, organisé
- Utilisation de `const` / `let` correcte
- `addEventListener` plutôt que `onclick=""` dans le HTML
- Gestion des erreurs et des cas limites
- Cohérence visuelle avec la charte JUNIA

---

>  **JUNIA — Architecture Web AP3** | Séance 2 : JavaScript — Document pédagogique de référence
