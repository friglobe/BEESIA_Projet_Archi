# 🎨 Charte graphique JUNIA

> Document de référence pour le respect de l'identité visuelle de JUNIA dans le projet de plateforme CV.

---

## 🎨 Palette de couleurs

### Couleurs principales

| Couleur | Hex | RGB | Usage |
|---------|-----|-----|-------|
| **Violet JUNIA** | `#6B2C91` | `rgb(107, 44, 145)` | Titres, accents principaux, boutons primaires |
| **Orange JUNIA** | `#F39200` | `rgb(243, 146, 0)` | Boutons d'action, mises en avant, dégradés |

### Couleurs secondaires

| Couleur | Hex | RGB | Usage |
|---------|-----|-----|-------|
| **Violet clair** | `#A569BD` | `rgb(165, 105, 189)` | Liens secondaires, hover, états actifs |
| **Orange clair** | `#FDB44B` | `rgb(253, 180, 75)` | Bordures, fonds d'encadrés |
| **Violet foncé** | `#4A1B68` | `rgb(74, 27, 104)` | Texte sur fond clair, headers |

### Couleurs neutres

| Couleur | Hex | Usage |
|---------|-----|-------|
| **Texte** | `#333333` | Texte courant |
| **Texte secondaire** | `#6B5B7A` | Légendes, métadonnées |
| **Fond principal** | `#FAF7F2` | Background body |
| **Fond clair** | `#FAF7FB` | Sections, cartes |
| **Bordure** | `#E5DCED` | Séparations, bordures de cartes |
| **Blanc** | `#FFFFFF` | Cartes, formulaires |

### Couleurs fonctionnelles

| Couleur | Hex | Usage |
|---------|-----|-------|
| **Succès** | `#27AE60` | Validation, message positif |
| **Erreur** | `#E74C3C` | Erreurs, alertes critiques |
| **Information** | `#3498DB` | Messages informatifs |

---

## 💡 Variables CSS prêtes à l'emploi

```css
:root {
    /* Couleurs principales JUNIA */
    --junia-violet: #6B2C91;
    --junia-orange: #F39200;

    /* Variations */
    --junia-violet-clair: #A569BD;
    --junia-violet-fonce: #4A1B68;
    --junia-orange-clair: #FDB44B;

    /* Neutres */
    --junia-texte: #333333;
    --junia-texte-secondaire: #6B5B7A;
    --junia-fond: #FAF7F2;
    --junia-fond-clair: #FAF7FB;
    --junia-bordure: #E5DCED;
    --junia-blanc: #FFFFFF;

    /* Fonctionnel */
    --junia-succes: #27AE60;
    --junia-erreur: #E74C3C;
    --junia-info: #3498DB;
}
```

---

## 🌈 Dégradés signature

### Dégradé principal (violet → orange)

```css
background: linear-gradient(135deg, #6B2C91 0%, #F39200 100%);
```

Idéal pour : **headers**, **boutons d'appel à l'action**, **sections de couverture**.

### Dégradé secondaire (violet clair → violet)

```css
background: linear-gradient(135deg, #A569BD 0%, #6B2C91 100%);
```

Idéal pour : **encarts**, **cartes premium**, **éléments secondaires**.

---

## ✍️ Typographie

### Polices recommandées

| Usage | Police | Source |
|-------|--------|--------|
| **Titres** | Montserrat | [Google Fonts](https://fonts.google.com/specimen/Montserrat) |
| **Corps de texte** | Open Sans | [Google Fonts](https://fonts.google.com/specimen/Open+Sans) |
| **Code / mono** | Consolas, Monaco, monospace | Polices système |

### Intégration

Dans le `<head>` :

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
```

En CSS :

```css
body {
    font-family: 'Open Sans', Arial, sans-serif;
}

h1, h2, h3, h4 {
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
}
```

### Échelle typographique

| Niveau | Taille | Poids | Usage |
|--------|--------|-------|-------|
| H1 | 2.5rem (40px) | 700 | Titre de page |
| H2 | 2rem (32px) | 700 | Sections principales |
| H3 | 1.5rem (24px) | 600 | Sous-sections |
| H4 | 1.25rem (20px) | 600 | Éléments structurants |
| Texte courant | 1rem (16px) | 400 | Paragraphes |
| Texte secondaire | 0.9rem (14px) | 400 | Légendes, métadonnées |

---

## 🖼️ Logos et images

> ⚠️ **À récupérer auprès de l'équipe JUNIA :**
> - Logo principal (versions claire et foncée)
> - Logo monochrome (violet et blanc)
> - Pictogrammes JUNIA
> - Photos d'illustration officielles

Placez les fichiers dans `assets/logos/` et `assets/images/` du projet.

---

## 📐 Espacements et grille

### Système d'espacement (multiples de 0.5rem = 8px)

| Variable | Valeur | Usage |
|----------|--------|-------|
| `--espace-xs` | 0.25rem (4px) | Espacement très fin |
| `--espace-sm` | 0.5rem (8px) | Espacement compact |
| `--espace-md` | 1rem (16px) | Espacement standard |
| `--espace-lg` | 1.5rem (24px) | Sections, paragraphes |
| `--espace-xl` | 2rem (32px) | Séparations majeures |
| `--espace-2xl` | 3rem (48px) | Sections de page |

### Conteneur principal

```css
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}
```

### Breakpoints responsive

| Nom | Largeur min | Cibles |
|-----|-------------|--------|
| Mobile | 0 | Smartphones (par défaut) |
| Tablette | 768px | Tablettes verticales |
| Desktop | 1024px | Ordinateurs portables |
| Large | 1280px | Grands écrans |

---

## 🎯 Exemples d'usage

### Bouton primaire

```css
.btn-primaire {
    background: linear-gradient(135deg, var(--junia-violet), var(--junia-orange));
    color: var(--junia-blanc);
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.btn-primaire:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(107, 44, 145, 0.3);
}
```

### Carte de profil

```css
.carte-profil {
    background: var(--junia-blanc);
    border: 1px solid var(--junia-bordure);
    border-top: 4px solid var(--junia-violet);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s, box-shadow 0.2s;
}

.carte-profil:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border-top-color: var(--junia-orange);
}
```

### Badge / pastille

```css
.badge-stage      { background: var(--junia-violet-clair); color: white; }
.badge-alternance { background: var(--junia-orange);       color: white; }
.badge-cdi        { background: var(--junia-violet);       color: white; }
```

---

## ✅ Checklist conformité charte

Avant tout rendu projet, vérifier :

- [ ] Couleurs **uniquement** dans la palette définie
- [ ] Polices **Montserrat** + **Open Sans** chargées
- [ ] **Logo JUNIA** visible sur toutes les pages (header)
- [ ] **Dégradé violet → orange** présent sur au moins un élément majeur
- [ ] Boutons d'action stylisés avec **transition** au survol
- [ ] **Contraste** suffisant (texte foncé sur fond clair, blanc sur dégradé)
- [ ] **Cohérence** des espacements (utilisation des variables)
- [ ] Rendu **lisible** sur mobile, tablette et desktop
