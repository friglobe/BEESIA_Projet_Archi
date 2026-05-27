# 📋 Plan de cours — Architecture Web AP3

> **Volume horaire :** 21h | **Soutenance projet :** 19/06/2025

Ce cours propose une introduction aux langages **HTML**, **CSS** et **PHP**, avec pour objectif final la création d'applications web complètes. Les étudiants apprendront à **structurer**, **styliser** et **rendre dynamique** une interface, puis à connecter le tout à une base de données MySQL.

---

## Chapitre 1 — Introduction au Web

- Le Web : principes et histoire
- Architecture **Client-Serveur**
- Architecture Web : HTTP, URL, requêtes/réponses

---

## Chapitre 2 — HTML

### Bases
- Introduction
- Balises, éléments et attributs
- Outils et librairies
- Structure d'un document HTML
- Règles d'écriture

### Balises de structuration
- Catégories de contenu
- Contenu de flux, sectionnant, de titres, de phrasés
- Contenu intégré, interactif, de méta-données
- Les divisions d'un document (`<div>`, `<section>`, `<article>`)
- Les balises de style (`<strong>`, `<em>`, `<mark>`)

### Balises spécialisées
- Gestion des **listes** (`<ul>`, `<ol>`, `<dl>`)
- **Tableaux** (`<table>`, `<tr>`, `<td>`, `<th>`)
- **Images et liens** (`<img>`, `<a>`)
- **Formulaires** (`<form>`, `<input>`, `<button>`)

### Formulaires en détail
- Traitement d'un formulaire
- Champs : texte, multiligne, boutons radio, cases à cocher
- Listes de choix simples et multiples
- Regroupement de blocs (`<fieldset>`, `<legend>`)
- Étiquetage des champs (`<label>`)

### Le DOM
- Structure arborescente d'un document HTML
- Construction de l'arbre DOM
- Rôle du navigateur
- Caractères spéciaux et entités HTML

---

## Chapitre 3 — CSS

### Bases
- Introduction
- Principe, syntaxe et intégration des règles (inline, interne, externe)
- Exemples de propriétés
- Ajustement de la taille des éléments

### Structuration
- Sémantique et attribut `class`
- Les feuilles de style en **cascade**
  - I. Par média
  - II. Par origine
  - III. Par spécificité des sélecteurs
- **Héritage**
- Pseudo-éléments et pseudo-classes

### Layout
- **Positionnement** (`display`, `position`, `float`)
- **Media Queries** (responsive design)
- **Transformations et transitions**
- **Bootstrap Framework**
- Outils et librairies

---

## Chapitre 4 — Langage PHP

### Bases
- Introduction
- Syntaxe de base
- Constantes, variables, affectation
- Opérateurs, chaînes de caractères, tableaux
- Structures de contrôle (`if`, `for`, `while`, `switch`)
- Instructions `break` et `continue`

### Formulaires
- Transmission (`GET`, `POST`)
- Champs de formulaire (`text`, `button`, `textarea`, `radio`, `checkbox`, `select`)

### PHP — MySQL
- Accès aux bases de données
- Connexion à une base de données
- Fonctions de connexion
- Interrogation et extraction des données

### Sessions
- Principe de fonctionnement
- Fonctions principales : `session_start()`, `session_destroy()`, `$_SESSION`, `session_unset()`, `session_id()`, `isset()`

### Cookies
- Qu'est-ce qu'un cookie ?
- Génération d'un cookie en PHP
- Récupération de la valeur d'un cookie
- Destruction d'un cookie

---

## 🎯 Projet de validation (9 à 12h en trinôme)

### Description

Le projet consiste à développer une **plateforme web** dédiée à la centralisation des CV des étudiants. Les CV sont générés automatiquement dans un format standardisé, à partir d'un formulaire complet recueillant toutes les informations nécessaires : données personnelles, biographie/lettre de motivation, parcours académique, expériences professionnelles, photo, etc.

Chaque étudiant peut sélectionner un ou plusieurs **domaines de recherche** correspondant à ses objectifs : contrat d'apprentissage (5e année), contrat de professionnalisation, mobilité internationale, stage de 1re ou 2e année, CDI, etc.

La plateforme met à disposition des **entreprises partenaires** (y compris le réseau ALUMNI JUNIA) un catalogue consultable de profils étudiants. Un bouton **"Convoquer"** est disponible sur chaque fiche profil, permettant à une entreprise d'inviter un candidat à un entretien (génération automatique d'un courriel contenant les informations de l'entreprise et les détails de la convocation).

### Objectifs

- Concevoir une plateforme **intuitive et accessible**
- Définir les profils utilisateurs : **étudiants**, **entreprises partenaires**, **administrateurs**
- Faciliter la diffusion des demandes étudiantes auprès des entreprises

### Fonctionnalités requises

#### Page d'accueil
Présentation dynamique des fonctionnalités principales et des entreprises partenaires.

#### Côté étudiant
- Création de compte et authentification
- Formulaire de génération du CV standardisé
- Choix des domaines de recherche (stage, alternance, CDI, mobilité…)
- Consultation et modification du profil

#### Côté entreprise
Accès via identifiant et mot de passe fournis par JUNIA :
- Catalogue des profils
- Recherche par filtre (domaine, compétences, type de contrat)
- Bouton **"Convoquer"** avec génération automatique de courriel
- Historique des profils convoqués

#### Page de contact
Formulaire de demande de création de compte pour les entreprises non encore référencées.

#### Administration
- Gestion des comptes utilisateurs
- Création des comptes entreprise

### Contraintes

#### Conformité RGPD
- Mention légale sur la collecte des données
- Option de consentement explicite

#### Charte graphique
- Couleurs **JUNIA** (violet et orange)
- Polices lisibles et modernes
- Intégration du **logo JUNIA** et des visuels institutionnels

#### Navigation
- Menu clair et accessible
- Accès rapide aux différentes sections

#### Design responsive
- Adaptation à tous les types d'écrans (mobile, tablette, desktop)

### Soutenance

Validation du projet lors d'une **soutenance devant un jury** prévue le **19/06/2025**.
