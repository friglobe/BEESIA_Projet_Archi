# 🎓 Architecture Web ISEN-AP3 — JUNIA

> Cours d'Architecture Web pour la promotion **ISEN-AP3 de JUNIA**
> Volume horaire : **21h**

---

## 📖 À propos du cours

Ce dépôt centralise l'ensemble des **supports pédagogiques**, **mémos**, **TPs guidés** et **ressources** du module *Architecture Web* dispensé à JUNIA. Le cours propose une introduction progressive aux langages **HTML**, **CSS**, **JavaScript** et **PHP/MySQL**, avec pour objectif final la conception d'une plateforme web complète : la **centralisation des CV étudiants** pour la mise en relation avec les entreprises partenaires (réseau ALUMNI JUNIA inclus).

### 🎯 Objectifs pédagogiques

- Comprendre l'**architecture client-serveur** d'une application web
- Maîtriser les langages du **front-end** : HTML, CSS, JavaScript
- Maîtriser les langages du **back-end** : PHP, MySQL
- Concevoir une **application web complète** : front + back + base de données
- Appliquer les principes **RGPD** et de **design responsive**
- Travailler en **équipe** (trinôme) sur un projet structuré

---

## 🗂️ Structure du dépôt

```
junia-architecture-web/
│
├── README.md                          ← Ce fichier
├── plan-de-cours.md                   ← Plan détaillé des 21h
│
├── seance-1-html/                     ← Introduction au web + HTML
│   ├── README.md
│   └── tp-1-cv-junia.pdf              ← TP de conception du formulaire CV
│
├── seance-2-css-js/                   ← CSS et JavaScript
│   ├── README.md
│   ├── memo-css.md                    ← Mémo complet CSS (sélecteurs, layout, Flexbox/Grid)
│   ├── memo-javascript.md             ← Mémo complet JS (bases, DOM, fetch/AJAX)
│   └── tp-2-cv-interactif.md          ← TP d'enrichissement du CV avec JS
│
├── seance-3-php-mysql/                ← Back-end (à venir)
│   └── README.md
│
├── ressources/                        ← Documentation transverse
│   ├── charte-graphique-junia.md      ← Couleurs, polices, logos JUNIA
│   └── liens-utiles.md                ← MDN, CSS Tricks, etc.
│
└── projet-final/                      ← Projet de validation en trinôme
    └── cahier-des-charges.md          ← Description complète de la plateforme CV
```

---

## 📚 Sommaire des séances

| # | Séance | Thème | Supports |
|---|--------|-------|----------|
| 1 | **HTML** | Introduction au web, architecture client-serveur, balises HTML, formulaires | [📁 seance-1-html](./seance-1-html/) |
| 2 | **CSS + JS** | Styles, layouts, Flexbox/Grid, interactivité, DOM, fetch/AJAX | [📁 seance-2-css-js](./seance-2-css-js/) |
| 3 | **PHP + MySQL** | Côté serveur, traitement de formulaires, BDD, sessions, cookies | [📁 seance-3-php-mysql](./seance-3-php-mysql/) *(à venir)* |
| 4 | **Projet** | Accompagnement, revue de code, préparation soutenance | — |

---

## 🛠️ Prérequis techniques

Avant de commencer le cours, les étudiants doivent installer :

- **XAMPP** (Apache + PHP + MySQL) — [apachefriends.org](https://www.apachefriends.org/fr/index.html)
- Docker Desktop (pour la séance 3 PHP/MySQL) — docker.com/products/docker-desktop
- Un **éditeur de code** : Visual Studio Code recommandé
- Un **navigateur moderne** : Chrome, Firefox ou Edge à jour
- Un **compte GitHub** pour la collaboration en trinôme

> 💡 Pour des tests rapides sans installation, on peut aussi utiliser **JSBin** ([jsbin.com](https://jsbin.com)) pour HTML/CSS/JS uniquement.

---

## 🎨 Charte graphique

Le projet final respecte la **charte graphique JUNIA** :

- **Violet principal** : `#6B2C91`
- **Orange principal** : `#F39200`
- **Violet clair** : `#A569BD`
- **Orange clair** : `#FDB44B`
- **Polices** : Montserrat (titres), Open Sans (corps de texte)

Détails complets dans [`ressources/charte-graphique-junia.md`](./ressources/charte-graphique-junia.md).

---

## 🚀 Comment utiliser ce dépôt

### Pour les étudiants

```bash
# Cloner le dépôt
git clone -b academy https://github.com/nticonseil/junia-architecture-web.git
cd junia-architecture-web

# Consulter le plan de cours
cat plan-de-cours.md  # ou ouvrir dans VS Code
```

### Pour le projet final

1. Lire le [cahier des charges du projet](./projet-final/cahier-des-charges.md)
2. Constituer un trinôme
3. Forker ce dépôt et créer son propre repo de projet
4. Soutenance le **19/06/2025**

---

## 📋 Évaluation

| Composante | Pondération |
|------------|-------------|
| **TP en séance** | Évaluation continue |
| **Projet final** (trinôme) | Soutenance le 19/06/2025 |

**Critères de notation du projet final :**

- Respect du **cahier des charges** (toutes les fonctionnalités requises)
- Qualité du **code** (lisibilité, organisation, commentaires)
- **Design responsive** et conforme à la charte JUNIA
- **Sécurité** : RGPD, validation des données, protection des mots de passe
- **Présentation** orale et démo en direct

---

## 📞 Contact

Pour toute question concernant le cours ou le projet, contacter l'équipe pédagogique JUNIA.

---

>  **JUNIA — Architecture Web ISEN-AP3** | Branche `academy`
