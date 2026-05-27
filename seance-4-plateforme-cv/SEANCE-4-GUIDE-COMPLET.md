# 🚀 SÉANCE 4 - INTÉGRATION FRONTEND/BACKEND + PROJET FINAL

**Architecture Web ISEN-AP3 — Durée: 9-12h en solo/binôme/trinôme**

---

## 📚 Récapitulatif des Séances précédentes

### **Seance 1-2 (Terminées):**
- ✅ **Frontend HTML/CSS/JS** dans `seance-2-css-js/solutions/`
- ✅ Vous les avez **déjà**

### **Seance 3 (Terminée):**
- ✅ **APIs Backend PHP** dans `seance-3-php-mysql/solutions/`
- ✅ **Structure BD (bdd-cv.sql)** dans `seance-3-php-mysql/database/`
- ✅ Vous les avez **déjà**

---

## 🎯 SÉANCE 4: Votre Mission

**Créer la plateforme COMPLÈTE de gestion de CV avec:**

1. **Frontend personnalisé** (votre design)
2. **APIs Backend** (fournis comme template)
3. **Base de données** (schema fourni)
4. **Tests complets** (Postman)
5. **Déploiement local** (Docker)

---

## 📁 STRUCTURE FINALE DU PROJET

```
app/
├── index.html                    ← Votre page d'accueil
├── style.css                     ← Votre design
├── app.js                        ← Votre logique Frontend
│
├── api/                          ← Backend (fourni)
│   ├── config.php                ← Connexion DB
│   ├── login.php                 ← POST /api/login.php
│   ├── register.php              ← POST /api/register.php
│   ├── cv-form.php               ← GET/POST CV
│   ├── profiles.php              ← GET profils
│   └── convocation.php           ← POST convoquer
│
├── uploads/                      ← Photos (généré)
│   └── .gitkeep
│
└── backend/                      ← Config (si nécessaire)
    └── config.php
```

---

## ✅ ÉTAPE 1: Récupérer vos fichiers Seance 2 et 3

```bash
# Copier Frontend Seance 2
cp -r seance-2-css-js/solutions/* app/

# Copier Backend Seance 3 (optionnel si vous avez une solution)
# cp -r seance-3-php-mysql/solutions/cv-platform/backend/api/* app/api/
```

**Vous avez maintenant:**
- `app/index.html` (page d'accueil)
- `app/style.css` (styles)
- `app/app.js` (logique Frontend)

---

## ✅ ÉTAPE 2: Ajouter les APIs Backend (fournies)

Créer `app/api/config.php`:

```php
<?php
// Connexion à la base de données
$conn = new mysqli(
  "db",
  "cv_user",
  "password123",
  "cv_platform"
);

if ($conn->connect_error) {
  http_response_code(500);
  die(json_encode(['error' => 'Erreur connexion: ' . $conn->connect_error]));
}

// Définir charset
$conn->set_charset("utf8");

// Fonction utilitaire: répondre en JSON
function respond($data, $status = 200) {
  http_response_code($status);
  header('Content-Type: application/json');
  echo json_encode($data);
  exit;
}

// Activer CORS (optionnel)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
?>
```

---

Créer `app/api/login.php`:

```php
<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(['error' => 'Méthode non autorisée'], 405);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email']) || !isset($data['password'])) {
  respond(['error' => 'Email et mot de passe requis'], 400);
}

$email = $conn->real_escape_string($data['email']);
$password = $data['password'];

$result = $conn->query("SELECT id, nom, email FROM etudiants WHERE email = '$email'");

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();
  // TODO: Vérifier le mot de passe (hash)
  respond(['success' => true, 'user' => $user]);
} else {
  respond(['error' => 'Utilisateur non trouvé'], 401);
}
?>
```

---

Créer `app/api/register.php`:

```php
<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(['error' => 'Méthode non autorisée'], 405);
}

$data = json_decode(file_get_contents('php://input'), true);

$required = ['nom', 'prenom', 'email', 'password'];
foreach ($required as $field) {
  if (!isset($data[$field])) {
    respond(['error' => "$field requis"], 400);
  }
}

$nom = $conn->real_escape_string($data['nom']);
$prenom = $conn->real_escape_string($data['prenom']);
$email = $conn->real_escape_string($data['email']);
$password = password_hash($data['password'], PASSWORD_BCRYPT);

$insert = "INSERT INTO etudiants (nom, prenom, email, password) 
           VALUES ('$nom', '$prenom', '$email', '$password')";

if ($conn->query($insert)) {
  respond(['success' => true, 'message' => 'Enregistrement réussi']);
} else {
  respond(['error' => 'Erreur: ' . $conn->error], 500);
}
?>
```

---

Créer `app/api/cv-form.php`:

```php
<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Enregistrer/Modifier CV
  
  $data = json_decode(file_get_contents('php://input'), true);
  $etudiant_id = $data['etudiant_id'] ?? 1; // TODO: obtenir l'ID depuis session
  
  $formation = $conn->real_escape_string($data['formation'] ?? '');
  $experience = $conn->real_escape_string($data['experience'] ?? '');
  $competences = $conn->real_escape_string($data['competences'] ?? '');
  
  $update = "UPDATE etudiants SET 
             formation = '$formation',
             experience = '$experience',
             competences = '$competences'
             WHERE id = $etudiant_id";
  
  if ($conn->query($update)) {
    respond(['success' => true, 'message' => 'CV sauvegardé']);
  } else {
    respond(['error' => $conn->error], 500);
  }
  
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Récupérer le CV
  
  $etudiant_id = $_GET['id'] ?? 1; // TODO: obtenir depuis session
  
  $result = $conn->query(
    "SELECT id, nom, prenom, email, formation, experience, competences 
     FROM etudiants WHERE id = $etudiant_id"
  );
  
  if ($result->num_rows > 0) {
    respond(['success' => true, 'cv' => $result->fetch_assoc()]);
  } else {
    respond(['error' => 'CV non trouvé'], 404);
  }
  
} else {
  respond(['error' => 'Méthode non autorisée'], 405);
}
?>
```

---

Créer `app/api/profiles.php`:

```php
<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  respond(['error' => 'Méthode non autorisée'], 405);
}

// Filtrer par domaines si fourni
$domaine = $_GET['domaine'] ?? null;

$query = "SELECT id, nom, prenom, email, formation, competences 
          FROM etudiants WHERE formation IS NOT NULL";

if ($domaine) {
  $domaine = $conn->real_escape_string($domaine);
  $query .= " AND competences LIKE '%$domaine%'";
}

$result = $conn->query($query);
$profiles = [];

while ($row = $result->fetch_assoc()) {
  $profiles[] = $row;
}

respond(['success' => true, 'profiles' => $profiles]);
?>
```

---

Créer `app/api/convocation.php`:

```php
<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(['error' => 'Méthode non autorisée'], 405);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['etudiant_id']) || !isset($data['entreprise_id'])) {
  respond(['error' => 'Paramètres manquants'], 400);
}

$etudiant_id = intval($data['etudiant_id']);
$entreprise_id = intval($data['entreprise_id']);
$date = $conn->real_escape_string($data['date'] ?? date('Y-m-d'));

$insert = "INSERT INTO convocations (etudiant_id, entreprise_id, date_convocation, status) 
           VALUES ($etudiant_id, $entreprise_id, '$date', 'pending')";

if ($conn->query($insert)) {
  // TODO: Envoyer email à l'étudiant
  respond(['success' => true, 'message' => 'Convocation envoyée']);
} else {
  respond(['error' => $conn->error], 500);
}
?>
```

---

## ✅ ÉTAPE 3: Charger la base de données

```bash
# Importer le schema SQL
docker-compose exec db mysql -u root -proot cv_platform < seance-3-php-mysql/database/bdd-cv.sql

# Vérifier
docker-compose exec db mysql -u root -proot cv_platform -e "SHOW TABLES;"
```

---

## ✅ ÉTAPE 4: Frontend - Créer votre interface

### **Contraintes (pour la pédagogie):**

✅ **Vous DEVEZ avoir:**
- Page d'accueil (index.html)
- Formulaire d'inscription
- Formulaire de CV
- Affichage des profils
- Bouton "Convoquer"

❌ **Vous ne devez PAS copier:** Des frameworks complets (Bootstrap OK, mais faire votre CSS aussi)

### **Template HTML minimaliste:**

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JUNIA - Plateforme CV</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        header { background: linear-gradient(135deg, #FF6B35 0%, #8B4789 100%); 
                 color: white; padding: 20px 0; margin-bottom: 30px; }
        .nav { display: flex; gap: 20px; }
        .nav button { background: white; border: none; padding: 10px 20px; 
                      cursor: pointer; border-radius: 5px; }
        form { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        input, textarea, select { width: 100%; padding: 10px; margin: 10px 0; 
                                  border: 1px solid #ddd; border-radius: 4px; }
        button { background: #FF6B35; color: white; padding: 10px 20px; 
                 border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #8B4789; }
        .profile-card { background: white; padding: 15px; margin: 10px 0; 
                        border-radius: 8px; border-left: 4px solid #FF6B35; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>🎓 JUNIA - Plateforme CV</h1>
            <div class="nav">
                <button onclick="showHome()">Accueil</button>
                <button onclick="showRegister()">S'inscrire</button>
                <button onclick="showCV()">Mon CV</button>
                <button onclick="showProfiles()">Profils</button>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Contenu dynamique -->
        <div id="app"></div>
    </div>

    <script src="app.js"></script>
</body>
</html>
```

---

### **Template JavaScript - Gestion des appels API:**

```javascript
const API = 'http://localhost/api';

// Afficher la page d'accueil
function showHome() {
  document.getElementById('app').innerHTML = `
    <h2>Bienvenue sur la plateforme JUNIA</h2>
    <p>Gérez votre CV et trouvez des opportunités d'emploi</p>
  `;
}

// Afficher formulaire d'inscription
function showRegister() {
  document.getElementById('app').innerHTML = `
    <form onsubmit="register(event)">
      <h2>S'inscrire</h2>
      <input type="text" id="nom" placeholder="Nom" required>
      <input type="text" id="prenom" placeholder="Prénom" required>
      <input type="email" id="email" placeholder="Email" required>
      <input type="password" id="password" placeholder="Mot de passe" required>
      <button type="submit">S'inscrire</button>
    </form>
  `;
}

async function register(event) {
  event.preventDefault();
  
  const data = {
    nom: document.getElementById('nom').value,
    prenom: document.getElementById('prenom').value,
    email: document.getElementById('email').value,
    password: document.getElementById('password').value
  };
  
  try {
    const response = await fetch(`${API}/register.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    
    const result = await response.json();
    
    if (result.success) {
      alert('✅ Inscription réussie!');
      showHome();
    } else {
      alert('❌ ' + result.error);
    }
  } catch (error) {
    console.error('Erreur:', error);
  }
}

// Afficher formulaire CV
function showCV() {
  document.getElementById('app').innerHTML = `
    <form onsubmit="saveCV(event)">
      <h2>Remplir votre CV</h2>
      <textarea id="formation" placeholder="Formation" rows="4"></textarea>
      <textarea id="experience" placeholder="Expérience" rows="4"></textarea>
      <textarea id="competences" placeholder="Compétences" rows="4"></textarea>
      <button type="submit">Sauvegarder</button>
    </form>
  `;
}

async function saveCV(event) {
  event.preventDefault();
  
  const data = {
    etudiant_id: 1, // TODO: obtenir depuis session
    formation: document.getElementById('formation').value,
    experience: document.getElementById('experience').value,
    competences: document.getElementById('competences').value
  };
  
  try {
    const response = await fetch(`${API}/cv-form.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    
    const result = await response.json();
    alert(result.success ? '✅ CV sauvegardé' : '❌ ' + result.error);
  } catch (error) {
    console.error('Erreur:', error);
  }
}

// Afficher les profils
async function showProfiles() {
  try {
    const response = await fetch(`${API}/profiles.php`);
    const result = await response.json();
    
    let html = '<h2>Profils disponibles</h2>';
    result.profiles.forEach(profile => {
      html += `
        <div class="profile-card">
          <h3>${profile.prenom} ${profile.nom}</h3>
          <p><strong>Email:</strong> ${profile.email}</p>
          <p><strong>Compétences:</strong> ${profile.competences}</p>
          <button onclick="convoquer(${profile.id})">Convoquer</button>
        </div>
      `;
    });
    
    document.getElementById('app').innerHTML = html;
  } catch (error) {
    console.error('Erreur:', error);
  }
}

async function convoquer(etudiantId) {
  const data = {
    etudiant_id: etudiantId,
    entreprise_id: 1, // TODO: obtenir depuis session entreprise
    date: new Date().toISOString().split('T')[0]
  };
  
  try {
    const response = await fetch(`${API}/convocation.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    
    const result = await response.json();
    alert(result.success ? '✅ Convocation envoyée' : '❌ ' + result.error);
  } catch (error) {
    console.error('Erreur:', error);
  }
}

// Afficher la page d'accueil au chargement
showHome();
```

---

## ✅ ÉTAPE 5: Tester vos APIs

### **Avec curl:**

```bash
# Test login
curl -X POST http://localhost/api/login.php \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"test"}'

# Test register
curl -X POST http://localhost/api/register.php \
  -H "Content-Type: application/json" \
  -d '{"nom":"Dupont","prenom":"Jean","email":"jean@test.com","password":"test123"}'

# Test CV
curl -X GET "http://localhost/api/cv-form.php?id=1"

# Test profils
curl -X GET "http://localhost/api/profiles.php"
```

### **Avec Postman:**

1. Importer la collection (voir fichier postman-collection.json)
2. Tester chaque endpoint
3. Vérifier les réponses JSON

---

## 📋 CHECKLIST FINALE

- [ ] Docker lancé et fonctionnel
- [ ] Base de données chargée (bdd-cv.sql)
- [ ] APIs créées dans `app/api/`
- [ ] Frontend créé dans `app/`
- [ ] Les 5 pages principales fonctionnent:
  - [ ] Accueil
  - [ ] Inscription
  - [ ] CV
  - [ ] Profils
  - [ ] Convocation
- [ ] Tous les endpoints testés
- [ ] Code documenté
- [ ] Tout commité sur Git

---

## 🎯 BONUS (si vous avez du temps):

- [ ] Authentification avec sessions PHP
- [ ] Upload de photos
- [ ] Recherche/filtrage des profils
- [ ] Historique des convocations
- [ ] Design responsive mobile
- [ ] Validation côté client et serveur
- [ ] Gestion des erreurs complète
- [ ] Tests avec Postman documentés

---

## 📅 Timing estimé:

- **1h:** Setup Docker + copier fichiers
- **2h:** Créer les APIs
- **2h:** Frontend basique
- **1h:** Tests et debug
- **1h:** Amélioration design
- **2h:** Bonus (si temps)

**Total: 9-12h** ✅

---

**À vous de jouer! Bonne chance!** 🚀
