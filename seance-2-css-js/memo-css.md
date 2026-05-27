# 🎨 Cours CSS — Architecture Web ISEN-AP3

> **JUNIA — Architecture Web ISEN-AP3** 
> Couvre l'introduction au CSS, les effets avancés, le modèle de boîte, Flexbox et CSS Grid.

---

## 📚 Sommaire général

- **[Partie 1 - Introduction au CSS et Mise en Forme](#séance-3--introduction-au-css-et-mise-en-forme)**
- **[Partie 2 - Bordures, Ombres, Effets et Modèle de Boîte CSS](#séance-4--bordures-ombres-effets-et-modèle-de-boîte-css)**
- **[Partie 3 - Mise en Page Moderne : Flexbox et CSS Grid](#séance-5--mise-en-page-moderne--flexbox-et-css-grid)**

---

# Introduction au CSS et Mise en Forme

## 📋 Au Programme

### Partie 1 : Introduction au CSS

- Qu'est-ce que le CSS ?
- Les trois méthodes d'intégration du CSS
- La syntaxe CSS de base
- Les sélecteurs CSS

### Partie 2 : Mise en Forme

- Styliser les textes
- Gérer les couleurs
- Ajouter des fonds (backgrounds)
- Défi pratique en binôme

> 💡 **Objectif :** Transformer une page HTML basique en une page visuellement attractive grâce au CSS !

---

## 🎨 Qu'est-ce que le CSS ?

**CSS = Cascading Style Sheets (Feuilles de Style en Cascade)**

> Le CSS est le langage qui permet de **styliser** vos pages HTML. Si le HTML est le squelette de votre page, le CSS en est l'habillage.

### Le principe de séparation fond/forme

- **HTML** : structure et contenu (le **QUOI**)
- **CSS** : présentation et design (le **COMMENT**)

**Exemple concret :**

- Sans CSS : texte basique
- Avec CSS : texte stylisé avec couleur, taille, ombre, gras… ✨

---

## 📝 Les 3 Méthodes d'Intégration du CSS

### 1️⃣ CSS Inline (en ligne)

```html
<p style="color: red; font-size: 20px;">Texte rouge</p>
```

> ⚠️ **À éviter** : difficile à maintenir et ne respecte pas la séparation fond/forme.

### 2️⃣ CSS Interne (dans le `<head>`)

```html
<head>
    <style>
        p {
            color: blue;
            font-size: 18px;
        }
    </style>
</head>
```

> 👍 Pratique pour des petites pages ou des tests.

### 3️⃣ CSS Externe (fichier séparé) — RECOMMANDÉ ✅

```html
<!-- Dans le HTML -->
<head>
    <link rel="stylesheet" href="style.css">
</head>
```

```css
/* Dans style.css */
p {
    color: green;
    font-size: 16px;
}
```

> ✨ **MEILLEURE PRATIQUE** : réutilisable, maintenable, organisé.

---

## 📐 Syntaxe CSS de Base

```css
sélecteur {
    propriété: valeur;
    autre-propriété: autre-valeur;
}
```

### Exemple concret

```css
h1 {
    color: purple;
    font-size: 32px;
    text-align: center;
}
```

**Décomposition :**

- **sélecteur** : l'élément HTML à cibler (`h1`, `p`, `div`...)
- **propriété** : l'aspect à modifier (`color`, `font-size`...)
- **valeur** : la valeur à appliquer (`purple`, `32px`...)
- **`;`** : sépare chaque déclaration
- **`{ }`** : contient toutes les déclarations

> ⚠️ N'oubliez pas les **deux-points `:`** et les **points-virgules `;`** !

---

## 🎯 Les Sélecteurs CSS

| Type | Syntaxe | Exemple | Cible |
|------|---------|---------|-------|
| **Élément** | `nomElement` | `p { }` | Tous les `<p>` |
| **Classe** | `.nomClasse` | `.intro { }` | Éléments avec `class="intro"` |
| **ID** | `#nomID` | `#titre { }` | Élément avec `id="titre"` |
| **Universel** | `*` | `* { }` | Tous les éléments |

### Exemple pratique

```css
/* Tous les paragraphes */
p {
    color: gray;
}

/* Les éléments avec la classe "important" */
.important {
    color: red;
    font-weight: bold;
}

/* L'élément unique avec l'id "header" */
#header {
    background-color: navy;
    color: white;
}
```

---

## ✍️ Styliser les Textes

### Propriétés de police

```css
p {
    font-family: Arial, sans-serif;
    font-size: 16px;
    font-weight: bold;        /* normal, bold, ou 100-900 */
    font-style: italic;       /* normal, italic */
    line-height: 1.5;         /* espacement entre lignes */
}
```

### Propriétés de texte

```css
h1 {
    text-align: center;         /* left, right, center, justify */
    text-decoration: underline; /* none, underline, line-through */
    text-transform: uppercase;  /* uppercase, lowercase, capitalize */
    letter-spacing: 2px;        /* espacement entre lettres */
}
```

---

## 🌈 Gérer les Couleurs

### Les différentes syntaxes de couleurs

| Type | Syntaxe | Exemple |
|------|---------|---------|
| **Nom** | `nom-couleur` | `color: red;` |
| **Hexadécimal** | `#RRGGBB` | `color: #FF5733;` |
| **RGB** | `rgb(r, g, b)` | `color: rgb(255, 87, 51);` |
| **RGBA** | `rgba(r, g, b, a)` | `color: rgba(255, 87, 51, 0.5);` |

### Exemple

```css
p {
    color: #333333;              /* Couleur du texte */
    background-color: #f0f0f0;   /* Couleur de fond */
}
```

---

## 🖼️ Les Fonds (Backgrounds)

### Couleur de fond

```css
body {
    background-color: #f5f5f5;
}
```

### Image de fond

```css
header {
    background-image: url('images/banniere.jpg');
    background-size: cover;        /* cover ou contain */
    background-position: center;   /* position de l'image */
    background-repeat: no-repeat;  /* no-repeat, repeat, repeat-x, repeat-y */
}
```

### Dégradés

```css
.gradient {
    background: linear-gradient(to right, #F39200, #6B2C91);
}
```

> **Astuce JUNIA :** Utilisez un dégradé orange → violet pour rester fidèle à la charte graphique de l'école.

---


## ✅ Bonnes Pratiques CSS

### Organisation du code

- Commentez votre CSS pour expliquer vos choix
- Organisez par sections (header, navigation, contenu, footer...)
- Indentez correctement votre code

```css
/* ========== HEADER ========== */
header {
    background-color: navy;
    padding: 20px;
}

/* ========== NAVIGATION ========== */
nav {
    display: flex;
    gap: 15px;
}

/* ========== CONTENU PRINCIPAL ========== */
main {
    max-width: 1200px;
    margin: 0 auto;
}
```

### Conseils importants

- 🎨 Limitez-vous à **2-3 polices maximum**
- 🌈 Utilisez une **palette de couleurs cohérente**
- 📏 Soyez **cohérent dans vos espacements**
- 🔍 Testez votre page dans **différents navigateurs**
- 💾 **Sauvegardez régulièrement** votre travail

---

## 📚 Ressources Utiles

### Documentation

- **MDN Web Docs CSS** : documentation de référence
- **CSS Tricks** : astuces et tutoriels
- **Can I use** : compatibilité des propriétés CSS

### Outils pratiques

- **Google Fonts** : polices gratuites à utiliser
- **Coolors.co** : générateur de palettes de couleurs
- **CodePen** : éditeur en ligne pour tester
- **ColorZilla** : extension pour prélever des couleurs

### Pour aller plus loin

- Explorez les animations CSS
- Découvrez les transformations 2D
- Apprenez les transitions au survol
- Pratiquez régulièrement sur CodePen

> 💡 **Conseil :** La meilleure façon d'apprendre le CSS est de pratiquer ! N'hésitez pas à expérimenter et à faire des erreurs.

---

## 📝 Récapitulatif de cette partie

### Introduction au CSS

- ✅ Définition et rôle du CSS
- ✅ Les 3 méthodes d'intégration (inline, interne, externe)
- ✅ La syntaxe CSS de base
- ✅ Les sélecteurs (élément, classe, ID)

### Mise en Forme

- ✅ Stylisation des textes (`font`, `text`)
- ✅ Gestion des couleurs (nom, hex, rgb, rgba)
- ✅ Ajout de fonds (couleurs, images, dégradés)
- ✅ Mise en pratique avec le défi binôme

---

---

# Bordures, Ombres, Effets et Modèle de Boîte CSS

## 🎯 Objectifs

- **Partie 1 :** Maîtriser les **bordures**, les **ombres** et les **effets au survol** avec CSS
- **Partie 2 :** Comprendre et appliquer le **modèle de boîte CSS** (`margin`, `padding`, `border`)
- **Compétence visée :** Créer des interfaces visuelles attractives et bien structurées

> 💡 **Clé de succès :** Ces concepts sont fondamentaux pour tout développement web. Ils permettent de contrôler précisément l'apparence et la mise en page de vos éléments HTML.

---

## 🔲 Les Bordures en CSS

### Types de bordures

| Type | Description |
|------|-------------|
| `solid` | Trait plein |
| `dashed` | Tirets |
| `double` | Double trait |
| `border-radius` | Coins arrondis (jusqu'au cercle parfait avec `50%`) |

### Syntaxe

```css
.element {
    border: 3px solid #6B2C91;
    border-radius: 8px;
}
```

### Variantes

```css
/* Border arrondi en cercle */
.cercle {
    width: 100px;
    height: 100px;
    border: 3px solid #F39200;
    border-radius: 50%;
}

/* Border uniquement sur certains côtés */
.encadre {
    border-top: 2px solid #6B2C91;
    border-bottom: 2px solid #F39200;
}
```

---

## 🌑 Les Ombres (box-shadow)

### Créer de la profondeur

```css
.carte {
    box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
    /* décalage-x décalage-y flou couleur */
}
```

### Ombres multiples

```css
.carte-elegante {
    box-shadow: 
        0 5px 15px rgba(0, 0, 0, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
}
```

> 💡 Les ombres offrent une impression de profondeur et rendent l'interface plus moderne et attrayante.

---

## 🎬 Effets au Survol (Hover)

### Interactivité avec `:hover` et `transition`

```css
.element {
    background: #6B2C91;
    transition: all 0.3s ease;
}

.element:hover {
    background: #F39200;
    transform: scale(1.1);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}
```

---

## ⏱️ Les Transitions

### Rendre les changements progressifs et fluides

La propriété **`transition`** permet de créer une animation fluide lors des changements d'état.

```css
.element {
    transition: all 0.3s ease;
    /* propriété  durée  fonction-temporelle */
}

/* Autres fonctions temporelles disponibles :
   linear, ease-in, ease-out, ease-in-out */
```

### ✓ Bonnes pratiques

- **Durée courte** (0.2s à 0.5s) : plus réactif et naturel
- Utilisez des transitions sur les **états interactifs** (`:hover`, `:focus`)
- Testez toujours sur **différents navigateurs**

---

## 📦 Le Modèle de Boîte CSS

### Fondamental pour la mise en page !

Chaque élément HTML est une **boîte composée de 4 couches** (de l'extérieur vers l'intérieur) :

```
┌─────────────────────────────────────┐
│  MARGIN (Marge externe)             │
│  ┌───────────────────────────────┐  │
│  │  BORDER (Bordure)             │  │
│  │  ┌─────────────────────────┐  │  │
│  │  │  PADDING (Marge interne)│  │  │
│  │  │  ┌───────────────────┐  │  │  │
│  │  │  │  CONTENT (Contenu)│  │  │  │
│  │  │  └───────────────────┘  │  │  │
│  │  └─────────────────────────┘  │  │
│  └───────────────────────────────┘  │
└─────────────────────────────────────┘
```

---

## ↔️ Margin vs Padding

Comprendre la différence entre les deux :

| Propriété | Position | Description |
|-----------|----------|-------------|
| **PADDING** | À l'**intérieur** de l'élément | Espace entre le contenu et la bordure |
| **MARGIN** | À l'**extérieur** de l'élément | Espace entre l'élément et les autres |

```css
.boite {
    padding: 30px;   /* espace interne */
    border: 2px solid #6B2C91;
    margin: 30px auto;  /* espace externe + centrage horizontal */
}
```

---

## 🧮 Syntaxe Margin et Padding

```css
/* Une valeur : s'applique partout */
padding: 20px;

/* Deux valeurs : haut/bas et gauche/droite */
padding: 10px 20px;

/* Quatre valeurs : haut, droite, bas, gauche (sens horaire) */
padding: 10px 20px 15px 5px;

/* Propriétés individuelles */
padding-top: 10px;
padding-right: 20px;
padding-bottom: 15px;
padding-left: 5px;
```

> 💡 La même syntaxe s'applique pour **`margin`**, **`border`** et **`border-radius`**.

---

## 🪆 Atelier "Boîtes Russes"

### Nesting de blocs stylisés

Observez comment les boîtes imbriquées interagissent :

- **Sans marge** : `margin: 0`
- **Avec marge** : `margin: 10px`
- **Padding augmenté** : `padding: 20px`
- **Transform** : `transform: scale(0.95)`

> 💡 **Exercice en classe :** Modifier le CSS pour créer des arrangements différents et observer l'impact des margins et paddings sur la mise en page.

---

## 📏 box-sizing : Important !

### Contrôler comment la largeur et la hauteur sont calculées

```css
/* Par défaut : content-box */
box-sizing: content-box;
/* Largeur totale = contenu + padding + border */

/* Recommandé : border-box */
box-sizing: border-box;
/* Largeur totale = contenu (padding et border inclus) */
```

> ✓ **Bonne pratique :** Appliquez `* { box-sizing: border-box; }` au début de votre CSS pour éviter les surprises !

```css
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}
```

---

## 📝 Résumé de cette partie

Points clés à retenir :

- **Bordures :** style, épaisseur, couleur, arrondi avec `border-radius`
- **Ombres :** `box-shadow` pour ajouter de la profondeur
- **Effets :** `:hover`, `transition`, `transform` pour l'interactivité
- **Modèle de boîte :** content → padding → border → margin
- **box-sizing :** toujours utiliser `border-box` !

> 📝 **Prochain projet :** Créer un composant "carte" avec bordure, ombre, padding et effet hover — préparation au projet final !

---

---

# Mise en Page Moderne : Flexbox et CSS Grid

## 🎯 Objectifs

- **Partie 1 :** Maîtriser **Flexbox** (mise en page 1D)
- **Partie 2 :** Découvrir **CSS Grid** (mise en page 2D)
- **Compétence :** Choisir l'outil approprié selon le besoin

> 💡 Flexbox et Grid **remplacent les anciennes techniques** (floats, positionnements absolus) et constituent les standards modernes du développement web.

---

## ⚖️ Flexbox vs Grid — Avant / Maintenant

| ❌ Avant (floats, tableaux) | ✅ Maintenant (Flexbox/Grid) |
|------------------------------|-------------------------------|
| Code complexe | Code simple |
| Difficile à centrer | Centrage facile |
| Peu flexible | Très flexible |

---

## 📦 Introduction à Flexbox

Système **1D** (une dimension) pour distribuer l'espace entre des éléments sur une ligne ou une colonne.

```css
.container {
    display: flex;
    gap: 10px;
    justify-content: center;
    align-items: center;
}
```

> 🎯 **Cas d'usage :** Barres de navigation, listes d'éléments, centrage vertical/horizontal.

---

## 💪 Flexbox en Action

### 1. Ligne horizontale (par défaut)

```css
.container {
    display: flex;
    gap: 10px;
}
```

### 2. Colonne verticale

```css
.container {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
```

### 3. Enrobage (wrapping)

Quand il n'y a plus de place, les éléments passent à la ligne :

```css
.container {
    display: flex;
    flex-wrap: wrap;
}
```

---

## ↔️ justify-content (axe principal)

Permet de distribuer les éléments le long de l'axe principal.

| Valeur | Effet |
|--------|-------|
| `flex-start` | Éléments alignés au début |
| `flex-end` | Éléments alignés à la fin |
| `center` | Éléments centrés |
| `space-between` | Espace réparti entre les éléments |
| `space-around` | Espace réparti autour des éléments |
| `space-evenly` | Espace identique partout |

```css
.container {
    display: flex;
    justify-content: space-between;
}
```

---

## ↕️ align-items (axe secondaire)

```css
align-items: center;     /* Vertical centré */
align-items: flex-start; /* Top */
align-items: flex-end;   /* Bottom */
align-items: stretch;    /* Étirement (par défaut) */
```

### ✓ Combinaison magique : centrage parfait

```css
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
```

> 💡 Trois lignes pour un **centrage parfait** vertical et horizontal !

---

## 📐 flex-grow et flex-basis

Permet de définir la proportion d'espace occupée par chaque enfant.

### Répartition équitable

```css
.item {
    flex: 1;  /* tous les enfants prennent la même place */
}
```

### 2x l'espace au centre

```css
.item-1 { flex: 1; }
.item-2 { flex: 2; }  /* prend 2x plus d'espace */
.item-3 { flex: 1; }
```

### Propriétés détaillées

- **`flex-grow`** : capacité à grandir
- **`flex-shrink`** : capacité à rétrécir
- **`flex-basis`** : taille de base

---

## 🛠️ Atelier Flexbox

### 🎯 Défi

- Utiliser `display: flex`
- **Aligner et distribuer** les éléments
- Rendre la page **responsive** avec `flex-wrap`
- Code **commenté**

---

## 🧱 Introduction à CSS Grid

Système **2D** (deux dimensions) pour créer des layouts complexes avec lignes ET colonnes.

```css
.grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: auto 1fr auto;
    gap: 20px;
}
```

> 🎯 **Cas d'usage :** Layouts complexes, galeries d'images, tableaux de bord, mise en page de pages entières.

---

## 🎯 CSS Grid en Action

### Grille à 2 colonnes

```css
.grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}
```

### Grille à 3 colonnes

```css
.grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}
```

### Avec tailles personnalisées

```css
.grid-perso {
    display: grid;
    grid-template-columns: 200px 1fr 100px;
    /* sidebar fixe | contenu flexible | colonne fixe */
}
```

---

## ↔️ grid-column : span

### S'étendre sur plusieurs colonnes ou lignes

```css
.wide {
    grid-column: span 2;  /* prend 2 colonnes */
}

.tall {
    grid-row: span 2;     /* prend 2 lignes */
}
```

### Exemple complet

```css
.header {
    grid-column: span 3;  /* header sur toute la largeur */
}

.sidebar {
    grid-row: span 2;     /* sidebar sur 2 lignes */
}
```

---

## 🏗️ Layout Complexe

Exemple de structure de page classique avec Grid :

```css
.page {
    display: grid;
    grid-template-columns: 1fr 2fr 1fr;
    grid-template-rows: auto 1fr auto;
    gap: 12px;
}

.header  { grid-column: 1 / -1; }  /* toute la largeur */
.sidebar-gauche { /* colonne 1 */ }
.main    { /* colonne 2 - contenu principal */ }
.sidebar-droite { /* colonne 3 */ }
.footer  { grid-column: 1 / -1; }  /* toute la largeur */
```

**Résultat visuel :**

```
┌─────────────────────────────────────┐
│            HEADER                   │
├─────────┬─────────────────┬─────────┤
│ Sidebar │      Main       │ Sidebar │
│ gauche  │    (contenu)    │ droite  │
├─────────┴─────────────────┴─────────┤
│            FOOTER                   │
└─────────────────────────────────────┘
```

---

## ⚖️ Flexbox vs CSS Grid

| Caractéristique | 📦 Flexbox | 🎯 CSS Grid |
|-----------------|------------|-------------|
| **Dimension** | 1D (ligne OU colonne) | 2D (lignes ET colonnes) |
| **Flexibilité** | Excellente | Stricte / précise |
| **Cas d'usage** | Navigation, listes, composants | Layouts, galeries, pages entières |

> 💡 **Astuce de pro :** Utilisez **Grid + Flexbox ensemble** ! Grid pour la structure générale de la page, Flexbox pour les composants internes.

---

## 📱 Responsive Grid

### La magie de `auto-fit` et `minmax()`

```css
.grille-responsive {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
}
```

> 💡 **S'adapte automatiquement à l'écran** ! Les colonnes se réorganisent en fonction de l'espace disponible, sans Media Queries.

### Décryptage

- **`auto-fit`** : autant de colonnes que possible
- **`minmax(150px, 1fr)`** : chaque colonne fait au minimum 150px, au maximum 1 fraction d'espace

---

## 🏗️ Atelier Landing Page

### 🎯 Structure proposée

- **Header** : navigation avec **Flexbox**
- **Hero section** : zone d'accroche
- **Galerie** : grille de 3 colonnes avec **Grid**
- **CTA (Call To Action)** : boutons avec **Flexbox**
- **Footer** : structure avec **Grid**

### 📝 À livrer

- HTML **sémantique** (`<header>`, `<main>`, `<section>`, `<footer>`)
- CSS combinant **Grid + Flexbox**
- **Responsivité** (mobile, tablette, desktop)
- Code **commenté**

---

## 📝 Résumé de cette partie

- **Flexbox :** mises en page **1D**, distribution, centrage
- **Grid :** mises en page **2D**, layouts, galeries
- **`justify-content` / `align-items`** : pour Flexbox
- **`grid-template-columns/rows`** : pour Grid
- **Responsive :** `auto-fit`, `minmax()` pour des grilles adaptatives
- **Combinaison :** Grid + Flexbox = **optimal !**

> 🚀 **Prochaine étape :** Projet final — application des connaissances dans la plateforme de centralisation des CV étudiants JUNIA.

---

---

# 🎓 Synthèse globale du cours CSS

## Compétences acquises

| Parties | Compétences |
|--------|-------------|
| **Partie 1** | Comprendre le rôle du CSS, écrire la syntaxe de base, utiliser sélecteurs, stylisation typographique et chromatique |
| **Partie 2** | Bordures, ombres, transitions, effets au survol, modèle de boîte, `box-sizing` |
| **Partie 3** | Mise en page moderne avec Flexbox (1D) et CSS Grid (2D), responsive design |

## 🎯 Application au projet JUNIA

Toutes ces compétences seront mobilisées dans le cadre du projet de **plateforme de centralisation des CV étudiants** :

1. **Page d'accueil** — header Flexbox, sections Grid, dégradés violet/orange
2. **Formulaire de saisie CV** — bordures stylisées, états `:focus`, transitions
3. **Catalogue de profils** — grille responsive en CSS Grid (`auto-fit` + `minmax`)
4. **Cartes de profil** — bordures, ombres, effets `:hover`, modèle de boîte maîtrisé
5. **Bouton "Convoquer"** — transitions, transformations, couleurs JUNIA
6. **Footer** — layout Grid sur toute la largeur

## 🎨 Palette JUNIA recommandée

```css
:root {
    --junia-violet: #6B2C91;
    --junia-violet-clair: #A569BD;
    --junia-orange: #F39200;
    --junia-orange-clair: #FDB44B;
    --junia-texte: #333333;
    --junia-fond: #FAF7F2;
    --junia-blanc: #FFFFFF;
}

/* Exemple d'utilisation */
.bouton-principal {
    background: linear-gradient(135deg, var(--junia-violet), var(--junia-orange));
    color: var(--junia-blanc);
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.bouton-principal:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(107, 44, 145, 0.3);
}
```

## 🎯 Évaluation

La maîtrise de ces compétences sera évaluée lors du contrôle écrit prévu le **22/05/2026**.

---

>  **JUNIA — Architecture Web ISEN-AP3** | Document pédagogique 
