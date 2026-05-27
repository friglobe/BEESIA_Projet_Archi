# 🛠️ TP 2 — Du CV statique au CV interactif

> **Durée :** 2h30 (Partie 1 : 1h30 + Partie 2 : 1h)
> **Prérequis :** TP 1 terminé (`cv.html` + `style.css`)
> **Rendu :** dossier compressé `tp2-cv-nom-prenom.zip` à déposer sur l'espace pédagogique

---

## 🎯 Objectif

Ce TP se déroule en deux temps :

1. **Partie 1 — Enrichir le CV du TP 1 avec JavaScript**
   Le CV statique de la semaine dernière reste joli, mais il ne bouge pas. On va y injecter de l'interactivité : mode sombre, animations au scroll, modale au clic, calcul automatique de l'âge, bouton d'impression.

2. **Partie 2 — Démarrer le formulaire de saisie du projet final**
   La plateforme JUNIA finale aura un formulaire pour saisir un CV ; les données seront ensuite envoyées à PHP qui générera dynamiquement la page CV. On commence à construire ce formulaire dès aujourd'hui avec un aperçu en direct qui ressemble au CV du TP 1.

Cela boucle proprement le cycle :

```
TP 1                  TP 2 — Partie 1         TP 2 — Partie 2          TP 3 (Docker/PHP)
CV statique  ─────►   CV interactif    ┐  ┌►  Formulaire de saisie  ─►  Branchement BDD
                                       │  │                              + génération PHP
                                       └──┘
                                  Cohérence visuelle
                                  (mêmes variables CSS)
```

---

## 📦 Mise en place

Repartez de votre dossier `tp1-cv-junia` du TP 1. Ajoutez un dossier `js/` :

```
tp1-cv-junia/
├── cv.html           ← du TP 1
├── style.css         ← du TP 1, qu'on va enrichir
├── formulaire.html   ← nouveau, créé en Partie 2
└── js/
    ├── script.js     ← nouveau, pour la Partie 1
    └── formulaire.js ← nouveau, pour la Partie 2
```

Dans `cv.html`, juste avant `</body>`, ajoutez :

```html
<script src="js/script.js"></script>
```

---

# PARTIE 1 — Enrichir le CV avec JavaScript (1h30)

## Étape 1 — Quelques ajustements préalables au HTML

Pour faciliter les manipulations JS, complétez votre `cv.html` ainsi :

```html
<!-- Dans le <header>, ajoutez un bouton thème à droite des coordonnées -->
<button id="toggle-theme" aria-label="Changer de thème">🌙</button>

<!-- Dans le sous-titre, ajoutez un span pour l'âge -->
<p>Étudiant·e ingénieur·e <span id="age" data-naissance="2003-09-15"></span></p>

<!-- Sur chaque <article> de la section #experiences, ajoutez l'attribut data-details -->
<article data-details="Description complète des missions : développement Python d'un outil de monitoring, mise en place d'une CI/CD, présentation des résultats à l'équipe…">
    <h3>Stage de découverte — Entreprise X</h3>
    <p class="dates"><em>Été 2024 · 6 semaines</em></p>
    <p>Description courte des missions et compétences acquises.</p>
</article>

<!-- Dans le <footer>, ajoutez un bouton d'impression -->
<button id="imprimer">🖨️ Imprimer mon CV</button>
```

> ☐ **À cocher :** Le HTML est complété, la page s'affiche toujours sans erreur.

---

## Étape 2 — Mode sombre 🌙 / ☀️

### CSS — ajouter au début de `style.css`

```css
body.dark {
    --texte: #E8E0F0;
    --fond: #1A0E2A;
    --violet: #A569BD;
}

body {
    background: var(--fond);
    color: var(--texte);
    transition: background 0.3s ease, color 0.3s ease;
}

#toggle-theme {
    background: transparent;
    border: 2px solid white;
    color: white;
    font-size: 1.2rem;
    padding: 0.3rem 0.6rem;
    border-radius: 50%;
    cursor: pointer;
    transition: transform 0.2s;
}

#toggle-theme:hover {
    transform: scale(1.1);
}
```

### JS — dans `js/script.js`

```js
const toggleTheme = document.querySelector("#toggle-theme");

toggleTheme.addEventListener("click", () => {
    document.body.classList.toggle("dark");
    const estSombre = document.body.classList.contains("dark");
    toggleTheme.textContent = estSombre ? "☀️" : "🌙";
    localStorage.setItem("theme", estSombre ? "dark" : "clair");
});

// Au chargement, restaurer la préférence de l'utilisateur
if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark");
    toggleTheme.textContent = "☀️";
}
```

> ☐ **À cocher :** Le bouton bascule entre clair et sombre. Rechargez la page (F5) : votre choix est conservé.

---

## Étape 3 — Calcul automatique de l'âge

```js
const spanAge = document.querySelector("#age");
const dateNaissance = new Date(spanAge.dataset.naissance);
const aujourdhui = new Date();

let age = aujourdhui.getFullYear() - dateNaissance.getFullYear();
const moisDiff = aujourdhui.getMonth() - dateNaissance.getMonth();

// Ajuster si l'anniversaire n'est pas encore passé cette année
if (moisDiff < 0 || (moisDiff === 0 && aujourdhui.getDate() < dateNaissance.getDate())) {
    age--;
}

spanAge.textContent = ` · ${age} ans`;
```

> ☐ **À cocher :** Votre âge s'affiche automatiquement à côté du sous-titre. Modifiez `data-naissance` pour vérifier que ça se met à jour.

> 💡 **Pourquoi data-* ?** Les attributs `data-*` permettent de **stocker dans le HTML des informations utilisées par le JS**. Ici, la date de naissance n'est pas affichée mais reste accessible via `element.dataset.naissance`.

---

## Étape 4 — Animation d'apparition au scroll

### CSS

```css
/* Cibler les éléments à animer */
section, .layout aside section, main article {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

section.visible, .layout aside section.visible, main article.visible {
    opacity: 1;
    transform: translateY(0);
}
```

### JS

```js
const observateur = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add("visible");
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll("section, .layout aside section, main article")
        .forEach(element => observateur.observe(element));
```

> ☐ **À cocher :** Les sections apparaissent progressivement quand vous scrollez.

> ⚠️ **Piège :** Si vos sections sont **toutes visibles** dès le chargement (CV court), l'animation se déclenche au démarrage et tout apparaît d'un coup — c'est normal. Pour bien tester l'effet, réduisez la hauteur de la fenêtre du navigateur.

---

## Étape 5 — Modale "détails de l'expérience"

### HTML — ajouter à la fin du `<body>`

```html
<div id="modale" class="modale cachee">
    <div class="modale-contenu">
        <button id="fermer-modale" aria-label="Fermer">×</button>
        <h3 id="modale-titre"></h3>
        <p id="modale-corps"></p>
    </div>
</div>
```

### CSS

```css
.modale {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modale.cachee { display: none; }

.modale-contenu {
    background: var(--fond);
    color: var(--texte);
    padding: 2rem;
    border-radius: 12px;
    max-width: 500px;
    border-top: 4px solid var(--violet);
    position: relative;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

#fermer-modale {
    position: absolute;
    top: 10px;
    right: 15px;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--texte);
}

#experiences article {
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

#experiences article:hover {
    transform: translateY(-4px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}
```

### JS

```js
const modale = document.querySelector("#modale");
const modaleTitre = document.querySelector("#modale-titre");
const modaleCorps = document.querySelector("#modale-corps");

document.querySelectorAll("#experiences article").forEach(article => {
    article.addEventListener("click", () => {
        modaleTitre.textContent = article.querySelector("h3").textContent;
        modaleCorps.textContent = article.dataset.details || "Pas de détails disponibles.";
        modale.classList.remove("cachee");
    });
});

document.querySelector("#fermer-modale").addEventListener("click", () => {
    modale.classList.add("cachee");
});

// Bonus : fermer en cliquant sur le fond
modale.addEventListener("click", (event) => {
    if (event.target === modale) modale.classList.add("cachee");
});
```

> ☐ **À cocher :** Cliquer sur une expérience ouvre une modale avec les détails. Cliquer sur × ou sur le fond la ferme.

---

## Étape 6 — Bouton d'impression PDF

### CSS — impression

```css
@media print {
    header { background: white !important; color: black !important; }
    #toggle-theme, #imprimer, .modale, footer button { display: none; }
    body.dark { background: white !important; color: black !important; }
    body.dark * { color: black !important; }
    article, section { page-break-inside: avoid; }
}
```

### JS

```js
document.querySelector("#imprimer").addEventListener("click", () => {
    window.print();
});
```

> ☐ **À cocher :** Cliquer sur "🖨️ Imprimer mon CV" ouvre la boîte de dialogue d'impression. Vérifiez l'aperçu : pas de fond violet, pas de boutons, juste le contenu.

---

## 🏁 Checkpoint Partie 1

Avant de passer à la Partie 2, vérifiez :

- [ ] Mode sombre fonctionnel et persistant après rechargement
- [ ] Âge calculé automatiquement
- [ ] Sections qui apparaissent au scroll
- [ ] Modale d'expérience qui s'ouvre au clic
- [ ] Impression propre (sans fond coloré, sans boutons)
- [ ] Aucune erreur dans la console (F12)

---

# PARTIE 2 — Démarrer le formulaire de saisie du projet (1h)

> 🎯 **Pourquoi maintenant ?** La plateforme JUNIA finale aura un **formulaire** qui permettra à chaque étudiant de saisir ses informations. PHP les enregistrera en base et générera ensuite une page CV semblable à celle de votre TP 1. Aujourd'hui, on **prépare ce formulaire** côté front, avec un aperçu en direct.

## Étape 7 — Créer `formulaire.html`

Créez un nouveau fichier à la racine de votre dossier :

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisie de mon CV — JUNIA</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Saisir mon CV</h1>
        <a href="cv.html" class="lien-cv">← Voir mon CV statique</a>
    </header>

    <main class="layout-formulaire">
        <form id="form-cv">
            <fieldset>
                <legend>Informations personnelles</legend>

                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" required>

                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required>

                <label for="email">Email JUNIA</label>
                <input type="email" id="email" name="email" required>
                <span id="erreur-email"></span>
            </fieldset>

            <fieldset>
                <legend>Lettre de motivation</legend>
                <label for="motivation">Quelques mots sur vous</label>
                <textarea id="motivation" name="motivation" maxlength="500" rows="6"></textarea>
                <small id="compteur"></small>
            </fieldset>

            <button type="submit">Générer mon CV</button>
        </form>

        <aside id="apercu">
            <h2>Aperçu en direct</h2>
            <div id="apercu-contenu">
                <p><em>Remplissez le formulaire pour voir l'aperçu apparaître…</em></p>
            </div>
        </aside>
    </main>

    <script src="js/formulaire.js"></script>
</body>
</html>
```

> ☐ **À cocher :** Le fichier s'ouvre dans le navigateur et affiche le formulaire (encore sans style adapté).

---

## Étape 8 — Styliser le formulaire (CSS)

À la fin de votre `style.css`, ajoutez :

```css
/* === FORMULAIRE === */
.layout-formulaire {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    max-width: 1100px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.lien-cv {
    color: white;
    text-decoration: underline;
    font-size: 0.9rem;
}

form fieldset {
    border: 2px solid var(--violet);
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

form legend {
    color: var(--violet);
    font-weight: bold;
    padding: 0 0.5rem;
}

form label {
    display: block;
    margin-top: 1rem;
    margin-bottom: 0.3rem;
    color: var(--violet);
    font-weight: 600;
}

form input,
form textarea {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    font-family: inherit;
    transition: border-color 0.3s, box-shadow 0.3s;
}

form input:focus,
form textarea:focus {
    outline: none;
    border-color: var(--orange);
    box-shadow: 0 0 0 3px rgba(243, 146, 0, 0.15);
}

form button[type="submit"] {
    background: linear-gradient(135deg, var(--violet), var(--orange));
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    width: 100%;
}

form button[type="submit"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(107, 44, 145, 0.3);
}

/* États de validation */
.invalide { border-color: #E74C3C !important; }
.valide   { border-color: #27AE60 !important; }

#erreur-email { display: block; margin-top: 0.3rem; font-size: 0.9rem; }
#erreur-email.ok { color: #27AE60; }
#erreur-email.ko { color: #E74C3C; }

#compteur { display: block; margin-top: 0.3rem; font-size: 0.85rem; color: var(--violet); }

/* Aperçu */
#apercu {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border-top: 4px solid var(--violet);
    height: fit-content;
    position: sticky;
    top: 1rem;
}

#apercu h2 {
    color: var(--violet);
    margin-bottom: 1rem;
}

/* Mobile : empilement */
@media (max-width: 768px) {
    .layout-formulaire { grid-template-columns: 1fr; }
    #apercu { position: static; }
}
```

> ☐ **À cocher :** Le formulaire est aux couleurs JUNIA, en 2 colonnes (formulaire à gauche, aperçu à droite).

---

## Étape 9 — Validation de l'email JUNIA en direct

Créez `js/formulaire.js` :

```js
const champEmail = document.querySelector("#email");
const erreurEmail = document.querySelector("#erreur-email");

champEmail.addEventListener("input", () => {
    const email = champEmail.value.trim();

    if (email === "") {
        erreurEmail.textContent = "";
        erreurEmail.className = "";
        champEmail.classList.remove("valide", "invalide");
    } else if (!email.endsWith("@junia.com")) {
        erreurEmail.textContent = "❌ Utilisez votre adresse @junia.com";
        erreurEmail.className = "ko";
        champEmail.classList.add("invalide");
        champEmail.classList.remove("valide");
    } else {
        erreurEmail.textContent = "✅ Email valide";
        erreurEmail.className = "ok";
        champEmail.classList.add("valide");
        champEmail.classList.remove("invalide");
    }
});
```

> ☐ **À cocher :** Saisir `test@gmail.com` → message rouge + bordure rouge. Saisir `marie@junia.com` → message vert + bordure verte.

---

## Étape 10 — Compteur de caractères pour la motivation

```js
const champMotivation = document.querySelector("#motivation");
const compteur = document.querySelector("#compteur");
const MAX_CARACTERES = 500;

const mettreAJourCompteur = () => {
    const longueur = champMotivation.value.length;
    compteur.textContent = `${longueur} / ${MAX_CARACTERES} caractères`;
    compteur.style.color = longueur > 450 ? "var(--orange)" : "var(--violet)";
};

champMotivation.addEventListener("input", mettreAJourCompteur);
mettreAJourCompteur();    // affichage initial
```

> ☐ **À cocher :** Le compteur affiche le nombre de caractères en temps réel. Au-delà de 450, il passe en orange.

---

## Étape 11 — Aperçu dynamique du CV 🌟

C'est **l'étape clé** : l'aperçu génère en direct un mini-CV qui reprend la structure visuelle du TP 1 (header violet, sections avec titres orange).

```js
const formulaire = document.querySelector("#form-cv");
const apercuContenu = document.querySelector("#apercu-contenu");

const genererApercu = () => {
    const donnees = new FormData(formulaire);
    const prenom = donnees.get("prenom") || "Prénom";
    const nom = donnees.get("nom") || "Nom";
    const email = donnees.get("email") || "email@junia.com";
    const motivation = donnees.get("motivation") || "Votre lettre de motivation apparaîtra ici en temps réel.";

    apercuContenu.innerHTML = `
        <div class="mini-cv-header">
            <h3>${prenom} ${nom}</h3>
            <p>📧 ${email}</p>
        </div>
        <div class="mini-cv-section">
            <h4>Lettre de motivation</h4>
            <p>${motivation}</p>
        </div>
    `;
};

formulaire.addEventListener("input", genererApercu);
genererApercu();    // appel initial
```

Ajoutez le style du mini-CV dans `style.css` :

```css
.mini-cv-header {
    background: var(--violet);
    color: white;
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 1rem;
}

.mini-cv-header h3 { color: white; margin-bottom: 0.3rem; }
.mini-cv-header p { font-size: 0.9rem; opacity: 0.9; }

.mini-cv-section h4 {
    color: var(--violet);
    border-bottom: 2px solid var(--orange);
    padding-bottom: 0.3rem;
    margin-bottom: 0.5rem;
}
```

> ☐ **À cocher :** Tapez dans les champs : l'aperçu se met à jour caractère par caractère et ressemble visuellement au CV de TP 1.

---

## Étape 12 — Persistance avec localStorage

Pour éviter de tout reperdre en cas de rechargement intempestif :

```js
// Sauvegarde à chaque saisie
formulaire.addEventListener("input", () => {
    const donnees = Object.fromEntries(new FormData(formulaire));
    localStorage.setItem("brouillon-cv", JSON.stringify(donnees));
});

// Restauration au chargement de la page
window.addEventListener("DOMContentLoaded", () => {
    const sauvegarde = localStorage.getItem("brouillon-cv");
    if (!sauvegarde) return;

    const donnees = JSON.parse(sauvegarde);
    for (const [cle, valeur] of Object.entries(donnees)) {
        const champ = formulaire.querySelector(`[name="${cle}"]`);
        if (champ) champ.value = valeur;
    }
    genererApercu();
    mettreAJourCompteur();
});

// Soumission finale
formulaire.addEventListener("submit", (event) => {
    event.preventDefault();

    if (!champEmail.value.endsWith("@junia.com")) {
        alert("⚠️ Merci d'utiliser votre adresse @junia.com");
        return;
    }

    console.log("📤 CV envoyé :", Object.fromEntries(new FormData(formulaire)));
    alert("✅ CV enregistré (simulation locale)\n\nEn séance 3, ces données partiront vraiment vers le serveur PHP via fetch !");
    localStorage.removeItem("brouillon-cv");
});
```

> ☐ **À cocher :** Remplissez quelques champs, rechargez la page (F5) : les données sont toujours là. Soumettez : message de confirmation, brouillon effacé.

---

# 🎁 BONUS (facultatif, valorisé)

### Bonus 1 — Photo de profil avec aperçu instantané

```html
<input type="file" id="photo" accept="image/*">
<img id="apercu-photo" style="max-width: 150px; display: none;">
```

```js
document.querySelector("#photo").addEventListener("change", (event) => {
    const fichier = event.target.files[0];
    if (!fichier) return;
    const url = URL.createObjectURL(fichier);
    const apercu = document.querySelector("#apercu-photo");
    apercu.src = url;
    apercu.style.display = "block";
});
```

### Bonus 2 — Domaines de recherche en badges

Ajoutez 4 checkboxes (Stage / Alternance / CDI / Mobilité) qui apparaissent comme **badges colorés** dans l'aperçu en temps réel.

### Bonus 3 — Compteur de mots et estimation de temps de lecture

Calculer en JS le nombre de mots de la motivation et un temps de lecture estimé (~200 mots/min).

---

# 📊 Évaluation /20

| Critère | Points |
|---------|--------|
| **PARTIE 1 — Enrichissement du CV** | **/10** |
| Mode sombre fonctionnel et persistant | /2 |
| Calcul automatique de l'âge correct | /1 |
| Animation au scroll (IntersectionObserver) | /2 |
| Modale d'expérience (ouverture + fermeture) | /2 |
| Bouton + media query d'impression | /2 |
| Qualité du code Partie 1 (lisibilité, commentaires) | /1 |
| **PARTIE 2 — Formulaire de saisie** | **/10** |
| Structure HTML sémantique du formulaire | /1 |
| CSS aux couleurs JUNIA, layout 2 colonnes responsive | /2 |
| Validation email JUNIA en direct | /2 |
| Compteur de caractères motivation | /1 |
| Aperçu dynamique fonctionnel et visuellement cohérent avec TP 1 | /3 |
| localStorage opérationnel (sauvegarde + restauration) | /1 |
| **TOTAL** | **/20** |

> 🎁 **Bonus :** chaque bonus réussi peut compenser jusqu'à -1 point d'erreur ailleurs (plafond +2 pts).

---

## 📤 Rendu

Compressez votre dossier `tp1-cv-junia/` (avec **tous les fichiers** : HTML, CSS, JS, images si applicable) en `tp2-cv-nom-prenom.zip` et déposez-le sur l'espace pédagogique avant la prochaine séance.

---

## 🔮 La suite — Séance 3 avec Docker

En **séance 3**, ce formulaire sera connecté à un vrai back-end via **PHP + MySQL** (dans des conteneurs Docker) :

- L'aperçu dynamique sera remplacé par la **génération réelle** d'un fichier CV par le serveur
- `localStorage` cédera la place à une vraie **base de données MySQL**
- L'`alert()` de la soumission deviendra un véritable **`fetch()`** vers une API PHP
- Plusieurs étudiants pourront enregistrer leurs CV, qui constitueront le **catalogue de la plateforme JUNIA**

Soignez votre code aujourd'hui : tout ce que vous écrivez sera réutilisé.
