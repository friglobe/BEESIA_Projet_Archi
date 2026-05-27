# 🚀 TP 2 bis — Catalogue côté entreprise + Export/Import JSON

> **Pour qui ?** Étudiants ayant terminé le TP 2 en avance
> **Durée :** 1h à 1h30 | **Format :** TP guidé avec parts d'autonomie
> **Prérequis :** TP 2 terminé (`formulaire.html` fonctionnel avec aperçu et localStorage)

---

## 🎯 Objectif

Ce TP complémentaire vous fait construire **l'autre moitié** de la plateforme JUNIA : la **vue côté entreprise**. Les recruteurs s'y connectent pour consulter le catalogue de profils étudiants, filtrer selon leurs critères, et convoquer les candidats à un entretien.

Pour alimenter ce catalogue sans serveur PHP (qu'on verra en séance 3), vous allez d'abord doter votre formulaire d'une **fonction d'export et d'import en JSON**. Cette manipulation préfigure exactement ce que PHP fera en envoyant les données depuis MySQL.

```
┌────────────────┐   export JSON   ┌──────────────────┐
│  formulaire    │ ──────────────► │  Fichier .json   │
│  (étudiant)    │ ◄────────────── │  (sur disque)    │
└────────────────┘   import JSON   └──────────────────┘
                                            │
                                            │ import dans le catalogue
                                            ▼
                                   ┌──────────────────┐
                                   │   catalogue      │
                                   │   (entreprise)   │
                                   └──────────────────┘
```

> 💡 **Pédagogie :** ce que vous allez écrire en JS aujourd'hui correspond exactement à ce que PHP fera côté serveur en séance 3. Vous comprendrez mieux le flux de données.

---

## 📦 Mise en place

Partez de votre dossier `tp1-cv-junia` (issu des TP 1 et 2). Ajoutez :

```
tp1-cv-junia/
├── cv.html                ← TP 1
├── formulaire.html        ← TP 2 (qu'on va enrichir)
├── catalogue.html         ← 🆕 à créer
├── style.css
└── js/
    ├── script.js          ← TP 2 P1
    ├── formulaire.js      ← TP 2 P2 (qu'on va enrichir)
    └── catalogue.js       ← 🆕 à créer
```

---

# PARTIE 1 — Export / Import du CV (1h)

## Étape 1 — Bouton "Exporter en JSON"

Dans `formulaire.html`, juste avant le bouton de soumission, ajoutez :

```html
<div class="actions-secondaires">
    <button type="button" id="exporter">📥 Exporter en JSON</button>
    <button type="button" id="importer">📤 Importer un brouillon</button>
    <input type="file" id="importer-fichier" accept=".json" hidden>
</div>
```

CSS rapide à ajouter :

```css
.actions-secondaires {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.actions-secondaires button {
    flex: 1;
    background: white;
    color: var(--violet);
    border: 2px solid var(--violet);
    padding: 0.6rem;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.actions-secondaires button:hover {
    background: var(--violet);
    color: white;
}
```

### Le JavaScript

Dans `js/formulaire.js`, ajoutez à la fin :

```js
// ═══════════════════════════════════════════════
// EXPORT JSON
// ═══════════════════════════════════════════════
document.querySelector("#exporter").addEventListener("click", () => {
    const donnees = Object.fromEntries(new FormData(formulaire));

    // Enrichir avec des métadonnées
    const cv = {
        ...donnees,
        _meta: {
            version: "1.0",
            exporte_le: new Date().toISOString(),
            source: "JUNIA AP3 Architecture Web"
        }
    };

    // Conversion en JSON formaté (lisible)
    const json = JSON.stringify(cv, null, 2);

    // Création d'un fichier téléchargeable
    const blob = new Blob([json], { type: "application/json" });
    const url = URL.createObjectURL(blob);

    // Déclenchement du téléchargement
    const lien = document.createElement("a");
    lien.href = url;
    lien.download = `cv-${donnees.prenom || "mon"}-${donnees.nom || "cv"}.json`;
    lien.click();

    // Libération de la mémoire
    URL.revokeObjectURL(url);
});
```

> 💡 **Décryptage :**
> - `Blob` = "Binary Large Object" — un conteneur de données. Ici, du texte JSON.
> - `URL.createObjectURL()` crée une URL temporaire pointant vers ce Blob.
> - On crée un lien `<a>` invisible avec `download="..."`, on simule un clic dessus, et le navigateur télécharge le fichier.
> - `URL.revokeObjectURL()` libère la mémoire (toujours bonne pratique).

> ☐ **À cocher :** Remplissez le formulaire, cliquez sur **📥 Exporter**. Un fichier `cv-prenom-nom.json` se télécharge. Ouvrez-le : c'est du JSON lisible.

---

## Étape 2 — Bouton "Importer un brouillon"

Toujours dans `formulaire.js` :

```js
// ═══════════════════════════════════════════════
// IMPORT JSON
// ═══════════════════════════════════════════════
const boutonImporter = document.querySelector("#importer");
const inputFichier = document.querySelector("#importer-fichier");

// Le bouton déclenche le sélecteur de fichier caché
boutonImporter.addEventListener("click", () => {
    inputFichier.click();
});

// Quand un fichier est sélectionné
inputFichier.addEventListener("change", (event) => {
    const fichier = event.target.files[0];
    if (!fichier) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        try {
            const cv = JSON.parse(e.target.result);

            // Remplir tous les champs correspondants
            for (const [cle, valeur] of Object.entries(cv)) {
                if (cle.startsWith("_")) continue;    // ignorer les métadonnées
                const champ = formulaire.querySelector(`[name="${cle}"]`);
                if (champ) champ.value = valeur;
            }

            genererApercu();
            mettreAJourCompteur();
            alert(`✅ Brouillon importé !\n\nExporté le : ${cv._meta?.exporte_le || "date inconnue"}`);
        } catch (erreur) {
            alert("❌ Fichier JSON invalide. Vérifiez le contenu.");
            console.error(erreur);
        }
    };

    reader.onerror = () => {
        alert("❌ Erreur lors de la lecture du fichier.");
    };

    reader.readAsText(fichier);
});
```

> 💡 **`FileReader`** est l'objet du navigateur qui sait lire un fichier sélectionné par l'utilisateur. Trois méthodes principales :
> - `readAsText()` — pour du texte/JSON
> - `readAsDataURL()` — pour les images (Base64)
> - `readAsArrayBuffer()` — pour le binaire pur

> ☐ **À cocher :** Exportez un brouillon, modifiez le formulaire, puis importez le brouillon : les anciens champs reviennent.

---

## Étape 3 — Gestion d'erreurs robuste

Améliorez l'import pour qu'il gère plus de cas :

**À vous de faire :**
- Vérifier que le fichier est bien du JSON valide (déjà fait avec `try/catch`)
- Vérifier que les champs attendus sont présents (prenom, nom, email...)
- Afficher une **notification visuelle** (pas un `alert`) plus élégante
- Garder l'**historique des imports** dans `localStorage` (date + nom du fichier)

> 💡 **Indication pour la notification :** créez une `<div class="notification">` qui apparaît 3 secondes puis disparaît (voir mémo JS Démo 2 pour le pattern).

> ☐ **À cocher :** L'import gère les erreurs proprement et notifie visuellement.

---

## 🏁 Checkpoint Partie 1

- [ ] Export en JSON produit un fichier téléchargeable avec métadonnées
- [ ] Import remplit tous les champs et régénère l'aperçu
- [ ] Erreurs gérées proprement (fichier invalide, JSON cassé)
- [ ] Notification visuelle au lieu d'`alert()` (bonus)

---

# PARTIE 2 — Catalogue côté entreprise (1h30)

> 🎯 **Contexte :** Vous êtes maintenant dans la peau d'un recruteur connecté à la plateforme JUNIA. Vous consultez les profils d'étudiants, vous filtrez selon vos besoins, et vous convoquez les candidats.

## Étape 4 — Créer `catalogue.html`

Créez le fichier à la racine du dossier :

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue Profils — JUNIA</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>🏢 Espace Entreprise</h1>
        <p>Bienvenue · <a href="formulaire.html" class="lien-cv">Espace étudiant</a></p>
    </header>

    <main class="catalogue-page">

        <!-- Barre de statistiques -->
        <section class="stats">
            <div class="stat-card">
                <span class="stat-nombre" id="nb-profils">0</span>
                <span class="stat-libelle">profils disponibles</span>
            </div>
            <div class="stat-card">
                <span class="stat-nombre" id="nb-convocations">0</span>
                <span class="stat-libelle">convocations envoyées</span>
            </div>
            <button id="charger-profil">📂 Charger un profil JSON</button>
            <input type="file" id="charger-fichier" accept=".json" hidden>
        </section>

        <!-- Barre de filtres -->
        <section class="filtres">
            <input type="search" id="recherche" placeholder="🔍 Rechercher (nom, compétence, mot-clé)">
            <select id="filtre-contrat">
                <option value="">Tous les contrats</option>
                <option value="stage">Stage</option>
                <option value="alternance">Alternance</option>
                <option value="cdi">CDI</option>
                <option value="mobilite">Mobilité internationale</option>
            </select>
        </section>

        <!-- Catalogue -->
        <section id="catalogue" class="catalogue"></section>
        <p id="aucun-resultat" class="cache">Aucun profil ne correspond à votre recherche.</p>

    </main>

    <!-- Modale de convocation -->
    <div id="modale-convocation" class="modale cachee">
        <div class="modale-contenu">
            <button id="fermer-convocation" aria-label="Fermer">×</button>
            <h3>Convoquer <span id="modale-nom"></span></h3>
            <label for="date-entretien">Date d'entretien</label>
            <input type="date" id="date-entretien" required>
            <label for="message-convocation">Message personnalisé (optionnel)</label>
            <textarea id="message-convocation" rows="4" placeholder="Bonjour, nous serions ravis de vous rencontrer pour discuter du poste de…"></textarea>
            <button id="envoyer-convocation">📩 Envoyer la convocation</button>
        </div>
    </div>

    <script src="js/catalogue.js"></script>
</body>
</html>
```

---

## Étape 5 — Styliser le catalogue

À la fin de `style.css` :

```css
/* === CATALOGUE === */
.catalogue-page {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

/* Statistiques en haut */
.stats {
    display: flex;
    gap: 1rem;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.stat-card {
    background: white;
    border-radius: 8px;
    padding: 1rem 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    border-left: 4px solid var(--violet);
    display: flex;
    flex-direction: column;
    min-width: 160px;
}

.stat-nombre {
    font-size: 2rem;
    font-weight: bold;
    color: var(--violet);
}

.stat-libelle {
    font-size: 0.85rem;
    color: #666;
}

#charger-profil {
    margin-left: auto;
    background: var(--orange);
    color: white;
    border: none;
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

#charger-profil:hover { background: #d97f00; }

/* Filtres */
.filtres {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.filtres input,
.filtres select {
    flex: 1;
    padding: 0.75rem;
    border: 2px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    min-width: 200px;
}

.filtres input:focus,
.filtres select:focus {
    outline: none;
    border-color: var(--orange);
}

/* Grille de cartes */
.catalogue {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.carte-profil {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border-top: 4px solid var(--violet);
    transition: transform 0.2s, box-shadow 0.2s, border-top-color 0.2s;
    display: flex;
    flex-direction: column;
}

.carte-profil:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-top-color: var(--orange);
}

.carte-profil h3 { color: var(--violet); margin-bottom: 0.5rem; }
.carte-profil .email { font-size: 0.85rem; color: #666; margin-bottom: 0.75rem; }
.carte-profil .motivation { font-size: 0.9rem; margin: 0.75rem 0; flex-grow: 1; }

.badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.3rem;
    margin: 0.75rem 0;
}

.badge {
    display: inline-block;
    padding: 0.25rem 0.6rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
}

.badge-stage      { background: #A569BD; }
.badge-alternance { background: var(--orange); }
.badge-cdi        { background: var(--violet); }
.badge-mobilite   { background: #3498DB; }

.btn-convoquer {
    background: linear-gradient(135deg, var(--violet), var(--orange));
    color: white;
    border: none;
    padding: 0.75rem;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    margin-top: auto;
    transition: transform 0.2s;
}

.btn-convoquer:hover { transform: translateY(-2px); }

.btn-convoquer.deja-convoque {
    background: #95a5a6;
    cursor: default;
}

/* Modale de convocation (reuse des styles existants si possible) */
.modale {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
.modale.cachee { display: none; }

.modale-contenu {
    background: var(--fond);
    padding: 2rem;
    border-radius: 12px;
    max-width: 500px;
    width: 90%;
    border-top: 4px solid var(--violet);
    position: relative;
}

.modale-contenu label {
    display: block;
    margin-top: 1rem;
    color: var(--violet);
    font-weight: 600;
}

.modale-contenu input,
.modale-contenu textarea {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #ddd;
    border-radius: 6px;
    margin-top: 0.3rem;
}

#envoyer-convocation {
    background: var(--violet);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    margin-top: 1rem;
    width: 100%;
}

#fermer-convocation {
    position: absolute;
    top: 10px;
    right: 15px;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
}

.cache, .cachee { display: none; }
```

---

## Étape 6 — Données de départ et affichage

Créez `js/catalogue.js`. On commence avec un jeu de données fictives :

```js
// ═══════════════════════════════════════════════
// DONNÉES INITIALES (en séance 3, ces données
// viendront de MySQL via PHP — pour l'instant
// on les met en dur côté client)
// ═══════════════════════════════════════════════
let profils = [
    {
        id: 1,
        prenom: "Marie", nom: "Dupont",
        email: "marie.dupont@junia.com",
        contrat: "alternance",
        competences: ["Python", "Java", "Git", "Docker"],
        motivation: "Passionnée par le développement back-end et la donnée, je cherche une alternance en 5e année pour approfondir mes compétences en architecture logicielle."
    },
    {
        id: 2,
        prenom: "Paul", nom: "Martin",
        email: "paul.martin@junia.com",
        contrat: "stage",
        competences: ["HTML", "CSS", "JavaScript", "React"],
        motivation: "Étudiant en 3e année, je recherche un stage de 6 semaines en développement web front-end pour cet été."
    },
    {
        id: 3,
        prenom: "Sophie", nom: "Bernard",
        email: "sophie.bernard@junia.com",
        contrat: "cdi",
        competences: ["Project Management", "Agile", "SQL", "Power BI"],
        motivation: "Diplômée en juin 2026, je cherche un CDI dans le pilotage de projets data en région lilloise."
    },
    {
        id: 4,
        prenom: "Lucas", nom: "Durand",
        email: "lucas.durand@junia.com",
        contrat: "alternance",
        competences: ["Python", "Machine Learning", "TensorFlow", "AWS"],
        motivation: "Spécialisé en data science, je vise une alternance en IA pour ma dernière année."
    },
    {
        id: 5,
        prenom: "Emma", nom: "Lefevre",
        email: "emma.lefevre@junia.com",
        contrat: "mobilite",
        competences: ["English", "Marketing digital", "SEO", "Analytics"],
        motivation: "Je souhaite faire un semestre d'échange dans une université partenaire en Amérique du Nord."
    },
    {
        id: 6,
        prenom: "Hugo", nom: "Petit",
        email: "hugo.petit@junia.com",
        contrat: "stage",
        competences: ["PHP", "MySQL", "Docker", "Linux"],
        motivation: "Étudiant passionné par le back-end et les systèmes, je cherche un stage en DevOps ou en développement serveur."
    }
];

let historiqueConvocations = JSON.parse(localStorage.getItem("convocations") || "[]");

// ═══════════════════════════════════════════════
// AFFICHAGE
// ═══════════════════════════════════════════════
const catalogue = document.querySelector("#catalogue");
const messageAucun = document.querySelector("#aucun-resultat");
const nbProfils = document.querySelector("#nb-profils");
const nbConvocations = document.querySelector("#nb-convocations");

const afficherProfils = (liste) => {
    catalogue.innerHTML = "";

    if (liste.length === 0) {
        messageAucun.classList.remove("cache");
        nbProfils.textContent = "0";
        return;
    }
    messageAucun.classList.add("cache");

    liste.forEach(profil => {
        const dejaConvoque = historiqueConvocations.some(c => c.profilId === profil.id);

        const carte = document.createElement("article");
        carte.classList.add("carte-profil");
        carte.dataset.id = profil.id;

        carte.innerHTML = `
            <h3>${profil.prenom} ${profil.nom}</h3>
            <p class="email">📧 ${profil.email}</p>
            <div class="badges">
                <span class="badge badge-${profil.contrat}">${profil.contrat}</span>
            </div>
            <p class="motivation">${profil.motivation}</p>
            <div class="badges">
                ${profil.competences.map(c => `<span class="badge" style="background:#666">${c}</span>`).join("")}
            </div>
            <button class="btn-convoquer ${dejaConvoque ? "deja-convoque" : ""}"
                    ${dejaConvoque ? "disabled" : ""}
                    data-id="${profil.id}"
                    data-nom="${profil.prenom} ${profil.nom}">
                ${dejaConvoque ? "✓ Déjà convoqué" : "📩 Convoquer"}
            </button>
        `;

        catalogue.appendChild(carte);
    });

    nbProfils.textContent = liste.length;
    nbConvocations.textContent = historiqueConvocations.length;
};

// Affichage initial
afficherProfils(profils);
```

> ☐ **À cocher :** En ouvrant `catalogue.html`, vous voyez les 6 cartes de profils avec badges et bouton Convoquer.

---

## Étape 7 — Filtres dynamiques

**À vous de faire** (avec aide) — c'est très proche de la **Démo 3 du mémo JS**, page "Application au projet CV" :

```js
// ═══════════════════════════════════════════════
// FILTRES
// ═══════════════════════════════════════════════
const champRecherche = document.querySelector("#recherche");
const filtreContrat = document.querySelector("#filtre-contrat");

const filtrer = () => {
    const motCle = champRecherche.value.toLowerCase().trim();
    const contrat = filtreContrat.value;

    const resultats = profils.filter(profil => {
        const matchContrat = contrat === "" || profil.contrat === contrat;
        const matchMotCle = motCle === ""
            || `${profil.prenom} ${profil.nom}`.toLowerCase().includes(motCle)
            || profil.competences.some(c => c.toLowerCase().includes(motCle))
            || profil.motivation.toLowerCase().includes(motCle);

        return matchContrat && matchMotCle;
    });

    afficherProfils(resultats);
};

champRecherche.addEventListener("input", filtrer);
filtreContrat.addEventListener("change", filtrer);
```

> ☐ **À cocher :** Tapez "Python" dans la recherche → seuls Marie et Lucas restent. Sélectionnez "Alternance" → seuls les alternants apparaissent.

---

## Étape 8 — Bouton Convoquer + modale

```js
// ═══════════════════════════════════════════════
// CONVOCATION
// ═══════════════════════════════════════════════
const modale = document.querySelector("#modale-convocation");
const modaleNom = document.querySelector("#modale-nom");
const dateEntretien = document.querySelector("#date-entretien");
const messageConvocation = document.querySelector("#message-convocation");

let profilEnCoursConvocation = null;

// Délégation d'événements (les boutons peuvent être recréés par filtrer())
catalogue.addEventListener("click", (event) => {
    const bouton = event.target.closest(".btn-convoquer");
    if (!bouton || bouton.disabled) return;

    profilEnCoursConvocation = {
        id: parseInt(bouton.dataset.id),
        nom: bouton.dataset.nom
    };

    modaleNom.textContent = profilEnCoursConvocation.nom;
    dateEntretien.value = "";
    messageConvocation.value = "";
    modale.classList.remove("cachee");
});

document.querySelector("#fermer-convocation").addEventListener("click", () => {
    modale.classList.add("cachee");
});

document.querySelector("#envoyer-convocation").addEventListener("click", () => {
    if (!dateEntretien.value) {
        alert("⚠️ Veuillez choisir une date d'entretien.");
        return;
    }

    // Enregistrer la convocation
    const convocation = {
        profilId: profilEnCoursConvocation.id,
        profilNom: profilEnCoursConvocation.nom,
        date: dateEntretien.value,
        message: messageConvocation.value,
        envoyeeLe: new Date().toISOString()
    };

    historiqueConvocations.push(convocation);
    localStorage.setItem("convocations", JSON.stringify(historiqueConvocations));

    // En séance 3, ici on ferait un fetch POST vers PHP
    console.log("📤 Convocation envoyée :", convocation);

    modale.classList.add("cachee");
    filtrer();    // recharger l'affichage (bouton passe en "Déjà convoqué")

    afficherNotification(`✅ Convocation envoyée à ${profilEnCoursConvocation.nom}`);
});

const afficherNotification = (message) => {
    const notif = document.createElement("div");
    notif.className = "notification";
    notif.textContent = message;
    Object.assign(notif.style, {
        position: "fixed", bottom: "20px", right: "20px",
        background: "var(--orange)", color: "white",
        padding: "1rem 1.5rem", borderRadius: "8px",
        boxShadow: "0 5px 15px rgba(0,0,0,0.2)", zIndex: "2000"
    });
    document.body.appendChild(notif);
    setTimeout(() => notif.remove(), 3000);
};
```

> ☐ **À cocher :** Cliquez sur Convoquer, choisissez une date, validez. Le bouton passe en gris "Déjà convoqué" et le compteur en haut s'incrémente. Rechargez la page : l'historique est conservé.

---

## Étape 9 — Charger un profil JSON externe 🔗

**Le pont avec la Partie 1 :** un recruteur peut charger un fichier `.json` exporté depuis le formulaire étudiant.

```js
// ═══════════════════════════════════════════════
// IMPORT DE PROFIL DEPUIS UN FICHIER JSON
// ═══════════════════════════════════════════════
const boutonCharger = document.querySelector("#charger-profil");
const inputCharger = document.querySelector("#charger-fichier");

boutonCharger.addEventListener("click", () => inputCharger.click());

inputCharger.addEventListener("change", (event) => {
    const fichier = event.target.files[0];
    if (!fichier) return;

    const reader = new FileReader();
    reader.onload = (e) => {
        try {
            const cv = JSON.parse(e.target.result);

            // Vérifier les champs minimaux
            if (!cv.prenom || !cv.nom || !cv.email) {
                throw new Error("Champs manquants");
            }

            // Adapter au format du catalogue
            const nouveauProfil = {
                id: Date.now(),    // ID unique basé sur le timestamp
                prenom: cv.prenom,
                nom: cv.nom,
                email: cv.email,
                contrat: cv.contrat || "stage",
                competences: cv.competences || ["À renseigner"],
                motivation: cv.motivation || "Pas de motivation renseignée."
            };

            profils.push(nouveauProfil);
            filtrer();    // rafraîchir l'affichage
            afficherNotification(`✅ Profil de ${cv.prenom} ${cv.nom} ajouté au catalogue`);

        } catch (erreur) {
            alert("❌ Fichier JSON invalide ou champs manquants.");
            console.error(erreur);
        }
    };
    reader.readAsText(fichier);
    inputCharger.value = "";    // reset pour pouvoir recharger le même fichier
});
```

> ☐ **À cocher :** Exportez un brouillon depuis `formulaire.html`, puis chargez-le dans `catalogue.html` via le bouton **📂 Charger un profil JSON**. La carte apparaît dans la grille.

---

## 🏁 Checkpoint Partie 2

- [ ] Le catalogue affiche les 6 profils avec badges colorés
- [ ] Les filtres (recherche + contrat) fonctionnent en temps réel
- [ ] La modale de convocation s'ouvre, valide une date, envoie
- [ ] Les convocations sont persistées en localStorage et bloquent les profils déjà convoqués
- [ ] L'import de fichier JSON ajoute un nouveau profil

---

# 🎁 BONUS — Pour les très rapides

### Bonus 1 — Tri du catalogue

Ajoutez un select de tri : par nom (A→Z), par date d'ajout, par nombre de compétences.

### Bonus 2 — Historique des convocations en sidebar

Affichez à droite de la page une liste des dernières convocations envoyées avec date et destinataire. Permettre de la vider en un clic.

### Bonus 3 — Export du tableau de bord

Bouton "Exporter le rapport" qui télécharge un JSON contenant : nombre de convocations, profils consultés, statistiques par contrat.

### Bonus 4 — Multi-import depuis un dossier

Permettre de sélectionner **plusieurs fichiers JSON d'un coup** (attribut `multiple` sur l'input) pour peupler le catalogue rapidement.

---

# 📊 Évaluation /20

| Critère | Points |
|---------|--------|
| **PARTIE 1 — Export / Import** | **/6** |
| Export en JSON avec métadonnées (Blob, download) | /2 |
| Import via FileReader avec remplissage du formulaire | /2 |
| Gestion d'erreurs propre (JSON invalide, fichier manquant) | /1 |
| Notification visuelle (pas d'`alert()`) | /1 |
| **PARTIE 2 — Catalogue** | **/12** |
| HTML sémantique + CSS aux couleurs JUNIA | /2 |
| Affichage des cartes avec badges colorés | /2 |
| Filtres recherche + contrat fonctionnels | /2 |
| Modale de convocation (ouverture, date, message) | /2 |
| Persistance des convocations en localStorage | /2 |
| Import de profil JSON dans le catalogue | /2 |
| **Code** (organisation, commentaires, lisibilité) | **/2** |
| **TOTAL** | **/20** |

> 🎁 **Bonus :** chaque bonus réussi peut compenser jusqu'à -1 point d'erreur ailleurs (plafond +2 pts).

---

## 📤 Rendu

Compressez votre dossier `tp1-cv-junia/` complet en `tp2bis-catalogue-nom-prenom.zip` et déposez-le sur l'espace pédagogique.

---

## 🔮 La suite — Séance 3

Tout ce que vous venez d'écrire en JavaScript trouve son **équivalent côté serveur** en PHP/MySQL :

| Côté JS (aujourd'hui) | Côté PHP (séance 3) |
|---|---|
| `profils = [...]` en dur | `SELECT * FROM cv` dans MySQL |
| `localStorage.setItem("convocations", ...)` | `INSERT INTO convocations` dans MySQL |
| Export `Blob` JSON | Endpoint `api/profils.php` qui renvoie du JSON |
| `FileReader` pour importer | `$_POST` ou `$_FILES` côté serveur |
| `filtrer()` en JS | `WHERE` dans la requête SQL |

Le **flux est exactement le même**, vous changerez juste l'**emplacement** du code. C'est l'intérêt d'avoir tout structuré proprement aujourd'hui.

---

> 💜🧡 **JUNIA AP3 — Architecture Web** | TP 2 bis : Catalogue + Export/Import JSON
