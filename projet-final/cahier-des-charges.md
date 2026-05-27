# 📋 Cahier des charges — Projet final

> **Plateforme web de centralisation des CV étudiants JUNIA**
> Projet de validation du module Architecture Web AP3

> **Soutenance : 10 et 11/06/2025**

---

## 🎯 1. Contexte et objectif

Le projet a pour objectif de développer une **plateforme web** dédiée à la **centralisation des CV des étudiants JUNIA**. Les CV sont générés automatiquement dans un format standardisé, à partir d'un formulaire complet recueillant toutes les informations nécessaires : données personnelles, biographie/lettre de motivation, parcours académique, expériences professionnelles, photo, etc.

Chaque étudiant peut sélectionner un ou plusieurs **domaines de recherche** correspondant à ses objectifs : contrat d'apprentissage (5e année), contrat de professionnalisation, mobilité internationale, stage de 1re ou 2e année, CDI, etc.

La plateforme met à disposition des **entreprises partenaires** (y compris le réseau **ALUMNI JUNIA**) un **catalogue consultable** de profils étudiants à la recherche d'opportunités. Un bouton **"Convoquer"** est disponible sur chaque fiche profil, permettant à une entreprise d'inviter un candidat à un entretien. Lors de l'utilisation de cette fonctionnalité, un **courriel automatique** est envoyé au candidat concerné, contenant les informations de l'entreprise et les détails de la convocation.

---

## 👥 2. Public cible et profils utilisateurs

### Étudiants
- Profil principal
- Création de compte avec adresse `@junia.com`
- Saisie et mise à jour de leur CV

### Entreprises partenaires
- Reçoivent leurs identifiants par JUNIA
- Consultent le catalogue et convoquent les étudiants

### Administrateurs (équipe JUNIA)
- Gèrent les comptes (création, suspension, suppression)
- Modèrent les profils

---

## ⚙️ 3. Fonctionnalités attendues

### 3.1 Page d'accueil

- Mise en avant des principales fonctionnalités (recherche de contrats, stages, mobilité, CDI)
- Présentation des **entreprises partenaires**
- Liens vers la connexion et la création de compte
- Design **dynamique** et **attractif**

### 3.2 Côté étudiant

- ✅ **Création de compte** avec authentification (email + mot de passe)
- ✅ **Remplissage d'un formulaire** pour générer un CV standardisé
  - Données personnelles (nom, prénom, date de naissance, photo, coordonnées)
  - Biographie / lettre de motivation
  - Parcours académique
  - Expériences professionnelles
  - Compétences techniques et linguistiques
- ✅ **Choix des domaines de recherche** (cases à cocher multiples : stage, alternance, CDI, mobilité)
- ✅ **Consultation** et **modification** du profil
- ✅ Téléchargement du CV au format PDF (bonus)

### 3.3 Côté entreprise

L'entreprise se connecte avec un **identifiant** et un **mot de passe** fournis par JUNIA. Une fois connectée, elle dispose de :

- ✅ **Accès au catalogue** des profils étudiants
- ✅ **Recherche par filtres** :
  - Domaine de recherche (stage, alternance, CDI...)
  - Compétences
  - Type de contrat
  - École / promotion
- ✅ **Bouton "Convoquer"** sur chaque fiche profil
  - Sélection d'une date d'entretien
  - Génération automatique d'un **courriel** envoyé à l'étudiant
- ✅ **Historique** des profils convoqués

### 3.4 Page de contact

- Formulaire dédié pour les **entreprises non-partenaires** souhaitant rejoindre la plateforme
- Envoi de la demande à l'administrateur

### 3.5 Administration

- ✅ **Gestion des comptes utilisateurs** (étudiants, entreprises)
- ✅ **Création des comptes entreprise** (manuelle par l'admin)
- ✅ Modération des profils
- ✅ Tableau de bord avec statistiques (bonus)

---

## 🛡️ 4. Contraintes obligatoires

### 4.1 Conformité RGPD

- **Mention légale** sur la collecte des données (page dédiée)
- **Option de consentement** explicite lors de la création de compte
- Possibilité pour l'utilisateur de **supprimer son compte** et toutes ses données associées
- Stockage **sécurisé** des mots de passe (hachage avec `password_hash()` en PHP)

### 4.2 Charte graphique JUNIA

- Utilisation des **couleurs JUNIA** : violet (`#6B2C91`) et orange (`#F39200`)
- **Polices** lisibles et modernes (Montserrat + Open Sans recommandés)
- **Logo** et visuels institutionnels JUNIA présents sur toutes les pages
- Voir [`../ressources/charte-graphique-junia.md`](../ressources/charte-graphique-junia.md) pour les détails

### 4.3 Navigation intuitive

- **Menu** de navigation clair et accessible sur toutes les pages
- **Accès rapide** aux différentes sections
- Architecture d'information cohérente

### 4.4 Design responsive

- Adaptation à **tous types d'écrans** : mobile, tablette, desktop
- Tests obligatoires en plusieurs résolutions
- Utilisation de **Media Queries** + **Flexbox/Grid**

---

## 🏗️ 5. Spécifications techniques

### Stack technique imposée

| Couche | Technologie |
|--------|-------------|
| **Structure** | HTML5 sémantique |
| **Style** | CSS3 + Bootstrap (au choix) |
| **Interactivité** | JavaScript vanilla (pas de React/Vue pour ce projet) |
| **Serveur** | PHP 8+ |
| **Base de données** | MySQL (via XAMPP) |
| **Versioning** | Git + GitHub (obligatoire) |

### Architecture suggérée

```
projet-cv-junia/
├── index.php                ← Page d'accueil
├── css/
│   └── style.css
├── js/
│   ├── form-cv.js
│   ├── catalogue.js
│   └── auth.js
├── api/                     ← Endpoints PHP (JSON)
│   ├── auth.php
│   ├── profils.php
│   ├── enregistrer-cv.php
│   └── convoquer.php
├── inc/                     ← Inclusions PHP
│   ├── db.php               ← Connexion MySQL
│   ├── header.php
│   └── footer.php
├── pages/
│   ├── connexion.php
│   ├── inscription.php
│   ├── profil.php
│   ├── catalogue.php
│   └── admin/
├── uploads/                 ← Photos de profil
└── sql/
    └── junia_cv.sql         ← Script de création de la base
```

---

## 👥 6. Modalités de travail

### Composition des équipes

- **Trinômes** obligatoires
- Constitution libre, à valider avec l'enseignant

### Répartition suggérée

| Rôle | Responsabilités |
|------|-----------------|
| **Front-end Lead** | HTML, CSS, design, responsive |
| **Back-end Lead** | PHP, MySQL, sécurité |
| **Full-stack / Chef de projet** | JavaScript, intégration, Git, documentation |

### Outils collaboratifs

- **GitHub** : un dépôt par équipe (commits réguliers attendus)
- **Trello** ou **Notion** pour la gestion des tâches (recommandé)
- **Figma** pour les maquettes (recommandé)

---

## 📅 7. Calendrier

| Échéance | Étape |
|----------|-------|
| **Semaine 1** | Constitution des équipes, choix des rôles, maquettes |
| **Semaine 2** | Front-end statique (HTML + CSS) |
| **Semaine 3** | Intégration JavaScript (validation, interactivité) |
| **Semaine 4** | Back-end PHP + MySQL |
| **Semaine 5** | Tests, debug, finalisation |
| **19/06/2025** | 🎤 **SOUTENANCE devant jury** |

---

## 📊 8. Évaluation

### Grille de notation

| Critère | Pondération |
|---------|-------------|
| Respect du **cahier des charges** (toutes fonctionnalités présentes) | 25% |
| Qualité du **code** (lisibilité, organisation, commentaires) | 20% |
| **Design** et conformité à la charte JUNIA | 15% |
| **Responsive design** fonctionnel | 10% |
| **Sécurité** (RGPD, hachage mots de passe, validation données) | 10% |
| **Présentation orale** et démo | 15% |
| **Gestion de projet** (Git, répartition, méthodo) | 5% |
| **TOTAL** | **/20** |

### Soutenance (20 minutes par équipe)

- **5 min** : présentation du contexte et de l'architecture
- **10 min** : démonstration en direct des fonctionnalités
- **5 min** : questions du jury

---

## 🎁 9. Fonctionnalités bonus (valorisées)

- 📥 **Téléchargement du CV en PDF** (avec une lib PHP type FPDF ou TCPDF)
- 📊 **Tableau de bord admin** avec statistiques (graphiques)
- 🔍 **Recherche avancée** avec autocomplétion
- 📩 **Vrai envoi de courriels** (PHPMailer + SMTP)
- 🌓 **Mode sombre**
- 🌍 **Multilingue** (FR / EN)
- 🔐 **Connexion via Google/LinkedIn** (OAuth)
- ⚡ **Animations CSS** soignées

---

## ⚠️ 10. Points de vigilance

- ⛔ **Pas de copier-coller** depuis Internet sans compréhension
- ⛔ **Pas d'IA générative** pour produire du code sans validation et compréhension de chaque ligne
- ⛔ **Pas de mots de passe en clair** dans la base de données
- ⛔ **Pas d'injection SQL** : utilisez **prioritairement** les requêtes préparées
- ✅ **Commits Git réguliers** et messages clairs
- ✅ **Tests en conditions réelles** avant la soutenance

---

## 📞 Support

Pour toute question technique ou organisationnelle, contacter l'équipe pédagogique JUNIA.

---

>  **JUNIA — Architecture Web AP3** | Projet final — Plateforme CV
