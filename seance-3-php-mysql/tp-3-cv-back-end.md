# 🔧 TP 3 — Brancher le CV Front-End sur un Back-End PHP/MySQL

**Durée :** 8h | **Travail :** Trinôme | **Difficultés progressives**

---

## 🎯 Objectifs

À la fin de ce TP, vous aurez :

✅ Créé une **base de données MySQL** pour les CV  
✅ Implémenté une **API JSON** en PHP  
✅ Connecté votre formulaire de la Séance 2 au back-end  
✅ Mis en place l'**authentification** étudiant/entreprise  
✅ Testé avec **Postman** ou le navigateur  

---

## 📋 Prérequis

- ✅ Formulaire de CV terminé (Séance 2)
- ✅ XAMPP installé et fonctionnel
- ✅ Bases de PHP (Séance 3, Partie 1-2)

---

## 🏗️ Architecture du projet

```
Projet CV
├── frontend/
│   ├── index.html           (Formulaire CV)
│   ├── style.css
│   └── app.js              (fetch() vers API)
│
├── backend/
│   ├── config.php          (Connexion DB)
│   ├── api/
│   │   ├── enregistrer-cv.php      (POST: créer CV)
│   │   ├── profils.php             (GET: liste CV)
│   │   ├── profil.php              (GET: un CV)
│   │   ├── convocation.php         (POST: convoquer)
│   │   └── login.php               (POST: authentifier)
│   └── uploads/            (Dossier photos)
│
└── database/
    └── bdd-cv.sql          (Script création BD)
```

---

## 🗂️ Étape 1 : Préparer l'environnement

### 1.1 Démarrer XAMPP
```
Windows:
  C:\xampp\xampp-control.exe
  → Cliquer "Start" pour Apache et MySQL

Mac/Linux:
  sudo /Applications/XAMPP/xamppfiles/bin/xampp start
```

### 1.2 Créer le dossier projet
```bash
# Sur votre disque (ex: C:\xampp\htdocs)
mkdir cv-platform
cd cv-platform

# Créer les dossiers
mkdir frontend
mkdir backend
mkdir backend/api
mkdir backend/uploads
mkdir database
```

### 1.3 Vérifier la connexion
- Allez à : http://localhost/cv-platform
- Vous devriez voir l'erreur "index.html non trouvé" → normal

---

## 🗄️ Étape 2 : Créer la base de données

### 2.1 Ouvrir phpMyAdmin
```
http://localhost/phpmyadmin
```

### 2.2 Créer la base "cv_platform"
- Cliquer "Bases de données"
- Entrez "cv_platform"
- Cliquer "Créer"

### 2.3 Exécuter le script SQL

Créez le fichier `database/bdd-cv.sql` avec le contenu fourni (Étape 3).

Dans phpMyAdmin :
1. Aller à la base "cv_platform"
2. Cliquer l'onglet "SQL"
3. Copier/coller le contenu de `bdd-cv.sql`
4. Cliquer "Exécuter"

✅ Les tables sont créées!

---

## 🛠️ Étape 3 : Script de création de la base

**Fichier : `database/bdd-cv.sql`**

```sql
-- Table des étudiants
CREATE TABLE etudiants (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  photo VARCHAR(255),
  biographie TEXT,
  domaines_recherche VARCHAR(255),  -- JSON: ["stage", "alternance", "cdi"]
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des expériences (pour chaque étudiant)
CREATE TABLE experiences (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  entreprise VARCHAR(100),
  poste VARCHAR(100),
  date_debut DATE,
  date_fin DATE,
  description TEXT,
  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des formations (pour chaque étudiant)
CREATE TABLE formations (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  ecole VARCHAR(100),
  diplome VARCHAR(100),
  date_fin DATE,
  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des compétences
CREATE TABLE competences (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  competence VARCHAR(100),
  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des entreprises
CREATE TABLE entreprises (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  email_contact VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  logo VARCHAR(255),
  secteur VARCHAR(100),
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des convocations
CREATE TABLE convocations (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  entreprise_id INT NOT NULL,
  type_contrat VARCHAR(50),  -- stage, alternance, cdi, mobilité
  message TEXT,
  date_convocation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  statut VARCHAR(20) DEFAULT 'en attente',  -- en attente, accepté, refusé
  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
  FOREIGN KEY (entreprise_id) REFERENCES entreprises(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Index pour les recherches fréquentes
CREATE INDEX idx_etudiant_email ON etudiants(email);
CREATE INDEX idx_entreprise_email ON entreprises(email_contact);
CREATE INDEX idx_convocations_etudiant ON convocations(etudiant_id);
CREATE INDEX idx_convocations_entreprise ON convocations(entreprise_id);
```

---

## 🔧 Étape 4 : Fichier de configuration

**Fichier : `backend/config.php`**

```php
<?php
  // Configuration de la connexion
  define("DB_HOST", "localhost");
  define("DB_USER", "root");
  define("DB_PASS", "");  // Vide par défaut sur XAMPP
  define("DB_NAME", "cv_platform");
  
  // Créer la connexion
  $connection = new mysqli(
    DB_HOST,
    DB_USER,
    DB_PASS,
    DB_NAME
  );
  
  // Vérifier la connexion
  if ($connection->connect_error) {
    die(json_encode([
      "success" => false,
      "message" => "Erreur de connexion: " . $connection->connect_error
    ]));
  }
  
  // Encoder en UTF-8
  $connection->set_charset("utf8mb4");
  
  // Démarrer les sessions
  session_start();
?>
```

---

## 🔐 Étape 5 : API d'authentification

**Fichier : `backend/api/login.php`**

```php
<?php
  require "../config.php";
  
  header("Content-Type: application/json");
  
  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
  }
  
  // Récupérer les données
  $data = json_decode(file_get_contents("php://input"), true);
  
  $email = $data['email'] ?? "";
  $password = $data['password'] ?? "";
  $user_type = $data['user_type'] ?? "";  // "student" ou "company"
  
  // Validation
  if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["error" => "Email et mot de passe requis"]);
    exit;
  }
  
  // Déterminer la table
  $table = ($user_type == "company") ? "entreprises" : "etudiants";
  $email_field = ($user_type == "company") ? "email_contact" : "email";
  
  // Chercher l'utilisateur
  $stmt = $connection->prepare(
    "SELECT id, nom, password_hash FROM $table WHERE $email_field = ?"
  );
  $stmt->bind_param("s", $email);
  $stmt->execute();
  
  $result = $stmt->get_result();
  
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Vérifier le password
    if (password_verify($password, $user['password_hash'])) {
      // Succès
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_type'] = $user_type;
      $_SESSION['nom'] = $user['nom'];
      
      http_response_code(200);
      echo json_encode([
        "success" => true,
        "message" => "Connecté!",
        "user" => [
          "id" => $user['id'],
          "nom" => $user['nom']
        ]
      ]);
    } else {
      // Mot de passe incorrect
      http_response_code(401);
      echo json_encode(["error" => "Mot de passe incorrect"]);
    }
  } else {
    // Email non trouvé
    http_response_code(401);
    echo json_encode(["error" => "Email non trouvé"]);
  }
  
  $stmt->close();
?>
```

---

## 📤 Étape 6 : API pour enregistrer un CV

**Fichier : `backend/api/enregistrer-cv.php`**

```php
<?php
  require "../config.php";
  
  header("Content-Type: application/json");
  
  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
  }
  
  // Vérifier que l'étudiant est connecté
  if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    http_response_code(403);
    echo json_encode(["error" => "Non autorisé"]);
    exit;
  }
  
  $etudiant_id = $_SESSION['user_id'];
  
  // Récupérer les données du formulaire
  $nom = $_POST['nom'] ?? "";
  $email = $_POST['email'] ?? "";
  $biographie = $_POST['biographie'] ?? "";
  $domaines = $_POST['domaines'] ?? "";  // Array ou JSON
  
  // Traiter la photo (si présente)
  $photo = null;
  if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $tmp = $_FILES['photo']['tmp_name'];
    $original_name = $_FILES['photo']['name'];
    
    // Générer un nom unique
    $ext = pathinfo($original_name, PATHINFO_EXTENSION);
    $photo_name = $etudiant_id . "_" . time() . "." . $ext;
    
    if (move_uploaded_file($tmp, "../uploads/" . $photo_name)) {
      $photo = $photo_name;
    }
  }
  
  // Convertir les domaines en JSON
  $domaines_json = is_array($domaines) ? json_encode($domaines) : $domaines;
  
  // Mettre à jour les infos de l'étudiant
  $stmt = $connection->prepare(
    "UPDATE etudiants 
     SET nom = ?, email = ?, biographie = ?, photo = ?, domaines_recherche = ?
     WHERE id = ?"
  );
  $stmt->bind_param("sssssi", $nom, $email, $biographie, $photo, $domaines_json, $etudiant_id);
  
  if ($stmt->execute()) {
    // Ajouter les expériences
    if (isset($_POST['experiences']) && is_array($_POST['experiences'])) {
      $stmt_exp = $connection->prepare(
        "INSERT INTO experiences (etudiant_id, entreprise, poste, date_debut, date_fin, description)
         VALUES (?, ?, ?, ?, ?, ?)"
      );
      
      foreach ($_POST['experiences'] as $exp) {
        $stmt_exp->bind_param(
          "isssss",
          $etudiant_id,
          $exp['entreprise'],
          $exp['poste'],
          $exp['date_debut'],
          $exp['date_fin'],
          $exp['description']
        );
        $stmt_exp->execute();
      }
      $stmt_exp->close();
    }
    
    // Ajouter les formations (même principe)
    // TODO: implémenter de la même façon
    
    // Ajouter les compétences
    if (isset($_POST['competences']) && is_array($_POST['competences'])) {
      $stmt_comp = $connection->prepare(
        "INSERT INTO competences (etudiant_id, competence) VALUES (?, ?)"
      );
      
      foreach ($_POST['competences'] as $comp) {
        $stmt_comp->bind_param("is", $etudiant_id, $comp);
        $stmt_comp->execute();
      }
      $stmt_comp->close();
    }
    
    http_response_code(200);
    echo json_encode([
      "success" => true,
      "message" => "CV enregistré!"
    ]);
  } else {
    http_response_code(500);
    echo json_encode(["error" => "Erreur: " . $stmt->error]);
  }
  
  $stmt->close();
?>
```

---

## 📋 Étape 7 : API pour lire les profils

**Fichier : `backend/api/profils.php`**

```php
<?php
  require "../config.php";
  
  header("Content-Type: application/json");
  
  // Récupérer les paramètres (GET)
  $page = $_GET['page'] ?? 1;
  $limit = 10;
  $offset = ($page - 1) * $limit;
  
  // Filtres optionnels
  $domaine = $_GET['domaine'] ?? "";
  $search = $_GET['search'] ?? "";
  
  // Construire la requête
  $query = "SELECT id, nom, email, biographie, photo, domaines_recherche FROM etudiants WHERE 1=1";
  
  if (!empty($domaine)) {
    $query .= " AND domaines_recherche LIKE ?";
  }
  
  if (!empty($search)) {
    $query .= " AND (nom LIKE ? OR biographie LIKE ?)";
  }
  
  $query .= " LIMIT ? OFFSET ?";
  
  $stmt = $connection->prepare($query);
  
  // Lier les paramètres
  $domaine_like = "%$domaine%";
  $search_like = "%$search%";
  
  if (!empty($domaine) && !empty($search)) {
    $stmt->bind_param("sssii", $domaine_like, $search_like, $search_like, $limit, $offset);
  } elseif (!empty($domaine)) {
    $stmt->bind_param("sii", $domaine_like, $limit, $offset);
  } elseif (!empty($search)) {
    $stmt->bind_param("ssii", $search_like, $search_like, $limit, $offset);
  } else {
    $stmt->bind_param("ii", $limit, $offset);
  }
  
  $stmt->execute();
  $result = $stmt->get_result();
  
  $profils = [];
  while ($row = $result->fetch_assoc()) {
    $profils[] = [
      "id" => $row['id'],
      "nom" => $row['nom'],
      "email" => $row['email'],
      "biographie" => substr($row['biographie'], 0, 150) . "...",
      "photo" => $row['photo'] ? "/uploads/" . $row['photo'] : null,
      "domaines" => json_decode($row['domaines_recherche'])
    ];
  }
  
  echo json_encode($profils);
  
  $stmt->close();
?>
```

---

## 🎯 Étape 8 : Front-End (fetch)

**Fichier : `frontend/app.js`**

```javascript
const API_BASE = "http://localhost/cv-platform/backend/api";

// === LOGIN ===
async function login(email, password, userType) {
  try {
    const response = await fetch(`${API_BASE}/login.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      credentials: "include",  // Pour les cookies/sessions
      body: JSON.stringify({
        email,
        password,
        user_type: userType
      })
    });
    
    const data = await response.json();
    
    if (response.ok) {
      console.log("Login réussi!", data);
      // Rediriger vers dashboard
      window.location.href = "dashboard.html";
    } else {
      console.error("Erreur:", data.error);
      alert(data.error);
    }
  } catch (error) {
    console.error("Erreur réseau:", error);
  }
}

// === ENREGISTRER CV ===
async function enregistrerCV(formData) {
  try {
    const response = await fetch(`${API_BASE}/enregistrer-cv.php`, {
      method: "POST",
      credentials: "include",
      body: formData  // FormData pour les fichiers
    });
    
    const data = await response.json();
    
    if (response.ok) {
      alert("CV enregistré!");
      console.log(data);
    } else {
      console.error("Erreur:", data.error);
      alert(data.error);
    }
  } catch (error) {
    console.error("Erreur réseau:", error);
  }
}

// === CHARGER LES PROFILS ===
async function chargerProfils(page = 1, domaine = "", search = "") {
  try {
    let url = `${API_BASE}/profils.php?page=${page}`;
    
    if (domaine) url += `&domaine=${domaine}`;
    if (search) url += `&search=${search}`;
    
    const response = await fetch(url);
    const profils = await response.json();
    
    console.log("Profils:", profils);
    afficherProfils(profils);
  } catch (error) {
    console.error("Erreur:", error);
  }
}

function afficherProfils(profils) {
  const container = document.getElementById("profils");
  container.innerHTML = "";
  
  profils.forEach(profil => {
    const card = document.createElement("div");
    card.className = "profil-card";
    card.innerHTML = `
      <div class="profil-header">
        ${profil.photo ? `<img src="${profil.photo}" alt="${profil.nom}">` : '<div class="no-photo">📷</div>'}
        <h3>${profil.nom}</h3>
      </div>
      <p>${profil.biographie}</p>
      <p><strong>Domaines:</strong> ${profil.domaines.join(", ")}</p>
      <button onclick="convoquer(${profil.id})">Convoquer</button>
    `;
    container.appendChild(card);
  });
}

// === CONVOQUER UN ÉTUDIANT ===
async function convoquer(etudiantId) {
  const typeContrat = prompt("Type de contrat? (stage, alternance, cdi, mobilité)");
  
  if (!typeContrat) return;
  
  try {
    const response = await fetch(`${API_BASE}/convocation.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      credentials: "include",
      body: JSON.stringify({
        etudiant_id: etudiantId,
        type_contrat: typeContrat
      })
    });
    
    const data = await response.json();
    
    if (response.ok) {
      alert("Convocation envoyée!");
    } else {
      alert(data.error);
    }
  } catch (error) {
    console.error("Erreur:", error);
  }
}

// === EVENT LISTENERS ===
document.addEventListener("DOMContentLoaded", () => {
  // Formulaire de login
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;
      const userType = document.querySelector('input[name="user_type"]:checked').value;
      
      login(email, password, userType);
    });
  }
  
  // Formulaire de CV
  const cvForm = document.getElementById("cvForm");
  if (cvForm) {
    cvForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const formData = new FormData(cvForm);
      enregistrerCV(formData);
    });
  }
  
  // Charger les profils au démarrage
  chargerProfils();
});
```

---

## 🧪 Étape 9 : Tester avec Postman

**Installer Postman :** https://www.postman.com/downloads/

### Test 1 : Login
```
POST http://localhost/cv-platform/backend/api/login.php

Body (JSON):
{
  "email": "alice@junia.fr",
  "password": "password123",
  "user_type": "student"
}
```

### Test 2 : Lire les profils
```
GET http://localhost/cv-platform/backend/api/profils.php?page=1&domaine=stage
```

### Test 3 : Enregistrer un CV
```
POST http://localhost/cv-platform/backend/api/enregistrer-cv.php

Body (form-data):
nom: Alice
email: alice@junia.fr
biographie: Je suis une étudiante...
domaines: ["stage", "alternance"]
photo: (sélectionner un fichier)
```

---

## ✅ Checklist de validation

- [ ] Base de données créée et tables visibles dans phpMyAdmin
- [ ] Fichier `config.php` fonctionne (pas d'erreur de connexion)
- [ ] Login fonctionne (test Postman)
- [ ] Profils peuvent être récupérés (GET profils.php)
- [ ] Nouveau CV peut être enregistré (POST enregistrer-cv.php)
- [ ] Formulaire front-end envoie les données correctement
- [ ] Photos s'uploadent dans le dossier `uploads/`
- [ ] Convocations peuvent être créées

---

## 🐛 Dépannage

| Erreur | Solution |
|--------|----------|
| "Erreur de connexion" | Vérifier les identifiants MySQL dans `config.php` |
| "Method not allowed" | Utiliser POST pour enregistrer, GET pour lire |
| "Non autorisé" | Vérifier que `credentials: "include"` est dans fetch() |
| "Fichier trop volumineux" | Augmenter `upload_max_filesize` dans php.ini |
| CORS error | Le front et back doivent être sur le même serveur |

---

## 🚀 Améliorations possibles

1. **Validation plus stricte** des emails et formats
2. **Pagination** avec boutons Suivant/Précédent
3. **Recherche avancée** avec plusieurs filtres
4. **Historique des convocations** pour les entreprises
5. **Notifications par email** lors d'une convocation
6. **Modification du profil** (UPDATE)
7. **Suppression de compte** (DELETE)
8. **Dashboard personnel** pour chaque utilisateur

---

**Vous avez réussi le TP 3! 🎉**

Vous disposez maintenant d'une plateforme CV fonctionnelle avec authentification, upload de fichiers, et gestion de base de données.
