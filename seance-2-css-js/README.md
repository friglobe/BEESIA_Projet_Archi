# 🎨 Séance 2 — CSS et JavaScript

> **Durée :** 6h | **Format :** Cours + TP guidé

---

## 🎯 Objectifs

À l'issue de cette séance, les étudiants seront capables de :

- **CSS :** Styliser une page HTML, maîtriser le modèle de boîte, les sélecteurs, la mise en page moderne (Flexbox + Grid), le responsive design
- **JavaScript :** Manipuler le DOM, gérer les événements utilisateur, valider des formulaires en direct, communiquer avec un serveur (`fetch`)
- **Projet :** Rendre le formulaire CV de la séance 1 stylisé, responsive et interactif

---

## 📋 Plan de la séance

### Bloc CSS (3h)

#### Partie 1 — Bases du CSS (1h)
- Méthodes d'intégration, syntaxe, sélecteurs
- Stylisation des textes et des couleurs
- Fonds et dégradés

#### Partie 2 — CSS avancé (1h)
- Bordures, ombres, transitions, effets `:hover`
- Modèle de boîte (`margin`, `padding`, `border`)
- `box-sizing: border-box`

#### Partie 3 — Layout moderne (1h)
- Flexbox (mise en page 1D)
- CSS Grid (mise en page 2D)
- Media Queries et responsive design

### Bloc JavaScript (3h)

#### Partie 4 — Bases du langage (1h)
- Variables (`let`, `const`), types, opérateurs
- Conditions, boucles, fonctions (classiques + arrow)
- Tableaux et objets, méthodes `forEach`/`map`/`filter`

#### Partie 5 — DOM et événements (1h)
- Sélection d'éléments (`querySelector`)
- Modification du contenu et des classes
- Gestion des événements (`addEventListener`)
- Application au formulaire CV : validation en direct

#### Partie 6 — fetch et AJAX (1h)
- Synchrone vs asynchrone, Promises
- `async / await`
- Requêtes GET et POST
- Préparation à la séance PHP

---

## 📎 Supports de cours

| Fichier | Description |
|---------|-------------|
| 📄 [`memo-css.md`](./memo-css.md) | Mémo complet CSS (sélecteurs, modèle de boîte, typographie, couleurs, Flexbox, Grid, positionnement, bonnes pratiques) |
| 📄 [`memo-javascript.md`](./memo-javascript.md) | Mémo complet JavaScript (bases du langage, DOM, événements, applications au projet CV, fetch/AJAX) |
| 📄 [`tp-2-cv-interactif.md`](./tp-2-cv-interactif.md) | TP guidé : enrichir le formulaire CV avec CSS et JS |

---

## 🎨 Charte graphique appliquée

Tous les exemples reprennent les couleurs JUNIA :

```css
:root {
    --junia-violet: #6B2C91;
    --junia-violet-clair: #A569BD;
    --junia-orange: #F39200;
    --junia-orange-clair: #FDB44B;
}
```

Détails complets dans [`../ressources/charte-graphique-junia.md`](../ressources/charte-graphique-junia.md).

---

## 🛠️ Outils utilisés

- **Éditeur** : Visual Studio Code
- **Console du navigateur** : indispensable (F12)
- **Validation CSS** : [jigsaw.w3.org/css-validator](https://jigsaw.w3.org/css-validator/)
- **Polices** : [Google Fonts](https://fonts.google.com)
- **Palettes** : [Coolors](https://coolors.co)

---

## 🔗 Liens

- Séance précédente : [📁 seance-1-html](../seance-1-html/)
- Séance suivante : [📁 seance-3-php-mysql](../seance-3-php-mysql/)
- Plan de cours global : [📄 plan-de-cours.md](../plan-de-cours.md)
- Projet final : [📁 projet-final](../projet-final/)
