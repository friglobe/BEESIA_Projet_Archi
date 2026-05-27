# 🐘 Séance 3 — PHP et MySQL

> **Durée :** 8h | **Niveau :** Intermédiaire | **Prérequis :** HTML, CSS, JavaScript (Séance 2)

---

## 🎯 Objectifs d'apprentissage

À la fin de cette séance, vous serez capable de :

✅ Écrire du **PHP** de base (variables, structures, fonctions)  
✅ Traiter des **formulaires** côté serveur (`POST` et `GET`)  
✅ **Gérer les fichiers** uploadés (`$_FILES`)  
✅ Se **connecter à MySQL** et exécuter des requêtes sécurisées  
✅ Implémenter l'**authentification** (sessions et cookies)  
✅ **Brancher le front-end** (Séance 2) sur un back-end fonctionnel  

---

## 📋 Plan de la séance

### **Partie 1 : PHP — Les Bases** (2h)
- Historique et environnement
- Syntaxe, variables, types de données
- Opérateurs et structures de contrôle
- Fonctions et tableaux
- Intégration HTML/PHP

### **Partie 2 : Formulaires & HTTP** (2h)
- Méthodes GET et POST
- Superglobales (`$_POST`, `$_GET`, `$_REQUEST`)
- Validation des données
- Upload de fichiers
- Sécurité : sanitization et validation

### **Partie 3 : MySQL & Requêtes** (2h)
- Architecture client-serveur MySQL
- Connexion (MySQLi orienté objet)
- Requêtes préparées (protection SQL injection)
- CRUD basique
- Gestion des erreurs

### **Partie 4 : Sessions & Authentification** (1h)
- Principes des sessions
- Variables `$_SESSION`
- Gestion du logout
- Cookies (préférences)

### **Partie 5 : TP Pratique** (1h)
- Intégration front/back du formulaire CV
- API JSON côté serveur
- Tests et debugging

---

## 🏗️ Partie 1 : PHP — Les Bases

### 1.1 Qu'est-ce que PHP ?

**PHP** (PHP : Hypertext Preprocessor) est un langage de programmation **côté serveur** qui :
- S'exécute sur le serveur, avant d'envoyer le contenu au navigateur
- Peut générer du HTML dynamique
- Peut accéder à des bases de données
- N'est jamais visible au client (le code source reste sur le serveur)

#### Architecture

```
Client (Navigateur)                    Serveur
    |                                    |
    | --- Demande (HTTP) ---------->    |
    |                          Exécution PHP
    |                          Requête BD
    |                          Génération HTML
    | <--------- Réponse (HTML) ------- |
    |
Affichage dans le navigateur
```

### 1.2 Syntaxe de base

Tout code PHP est encadré par `<?php` et `?>` :

```php
<?php
  // Ceci est du code PHP
  echo "Bonjour, monde!";
?>
```

Les fichiers PHP portent l'extension `.php`.

### 1.3 Variables et types

Les variables en PHP commencent par `$` :

```php
<?php
  $nom = "Anaelle";           // String (chaîne)
  $age = 21;                 // Integer (entier)
  $prix = 19.99;             // Float (décimal)
  $est_etudiant = true;      // Boolean (booléen)
  
  // Affichage
  echo $nom;                 // Anaelle
  echo "Je suis " . $nom;    // Je suis Anaelle
?>
```

#### Types principaux

| Type | Exemple | Description |
|------|---------|-------------|
| `string` | `"texte"` | Chaîne de caractères |
| `int` | `42` | Nombre entier |
| `float` | `3.14` | Nombre décimal |
| `bool` | `true / false` | Booléen |
| `array` | `[1, 2, 3]` | Tableau (liste) |
| `null` | `null` | Absence de valeur |

### 1.4 Opérateurs

#### Opérateurs arithmétiques
```php
<?php
  $a = 10;
  $b = 3;
  
  echo $a + $b;  // 13
  echo $a - $b;  // 7
  echo $a * $b;  // 30
  echo $a / $b;  // 3.33...
  echo $a % $b;  // 1 (modulo)
?>
```

#### Opérateurs de comparaison
```php
<?php
  $x = 5;
  
  $x == 5;   // true (égal)
  $x === "5"; // false (identique, même type)
  $x != 3;   // true (différent)
  $x > 3;    // true (plus grand)
  $x >= 5;   // true (plus grand ou égal)
?>
```

#### Opérateurs logiques
```php
<?php
  $a = true;
  $b = false;
  
  $a && $b;  // false (ET)
  $a || $b;  // true (OU)
  !$a;       // false (NOT)
?>
```

### 1.5 Structures de contrôle

#### If / Else
```php
<?php
  $age = 18;
  
  if ($age >= 18) {
    echo "Vous êtes majeur";
  } elseif ($age >= 16) {
    echo "Vous avez 16 ou 17 ans";
  } else {
    echo "Vous êtes mineur";
  }
?>
```

#### Switch
```php
<?php
  $jour = "lundi";
  
  switch ($jour) {
    case "lundi":
      echo "Début de semaine!";
      break;
    case "vendredi":
      echo "Presque le weekend!";
      break;
    default:
      echo "Jour normal";
  }
?>
```

#### Boucles

**for** — Boucle avec compteur :
```php
<?php
  for ($i = 0; $i < 5; $i++) {
    echo $i;  // 0, 1, 2, 3, 4
  }
?>
```

**while** — Boucle conditionnelle :
```php
<?php
  $i = 0;
  while ($i < 5) {
    echo $i;
    $i++;
  }
?>
```

**foreach** — Boucle sur un tableau :
```php
<?php
  $fruits = ["pomme", "banane", "orange"];
  
  foreach ($fruits as $fruit) {
    echo $fruit;  // pomme, banane, orange
  }
?>
```

### 1.6 Tableaux

#### Tableaux indexés
```php
<?php
  $fruits = ["pomme", "banane", "orange"];
  
  echo $fruits[0];     // pomme
  echo $fruits[1];     // banane
  
  $fruits[3] = "raisin";  // Ajouter un élément
  count($fruits);      // 4 (nombre d'éléments)
?>
```

#### Tableaux associatifs
```php
<?php
  $etudiant = [
    "nom" => "Anaelle",
    "age" => 21,
    "promotion" => "GEC-2025"
  ];
  
  echo $etudiant["nom"];      // Anaelle
  echo $etudiant["age"];      // 21
?>
```

### 1.7 Fonctions

```php
<?php
  // Définition
  function sayHello($name) {
    return "Bonjour, " . $name;
  }
  
  // Appel
  echo sayHello("Anaelle");  // Bonjour, Anaelle
  
  // Fonction avec plusieurs paramètres
  function add($a, $b) {
    return $a + $b;
  }
  
  echo add(5, 3);  // 8
?>
```

---

## 🌐 Partie 2 : Formulaires & HTTP

### 2.1 Rappel : GET vs POST

| Aspect | GET | POST |
|--------|-----|------|
| **Méthode** | Paramètres dans l'URL | Données dans le corps |
| **Sécurité** | ❌ Visible dans l'URL | ✅ Caché au client |
| **Taille** | ⚠️ Limité (~2KB) | ✅ Pas de limite |
| **Cache** | ✅ Peut être mis en cache | ❌ Pas de cache |
| **Usage** | Recherches, filtres | Login, upload, données sensibles |

### 2.2 Traiter un formulaire POST

**HTML :**
```html
<form method="POST" action="traiter.php">
  <input type="text" name="nom" required>
  <input type="email" name="email" required>
  <input type="password" name="password" required>
  <button type="submit">Envoyer</button>
</form>
```

**PHP (traiter.php) :**
```php
<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Traiter les données
    echo "Merci, $nom!";
  }
?>
```

### 2.3 Superglobales

Les **superglobales** sont des variables PHP toujours disponibles :

```php
<?php
  $_POST       // Données POST du formulaire
  $_GET        // Paramètres GET dans l'URL
  $_REQUEST    // POST + GET
  $_SERVER     // Infos du serveur (méthode, IP, fichier...)
  $_FILES      // Fichiers uploadés
  $_SESSION    // Variables de session
  $_COOKIE     // Cookies du client
  $_ENV        // Variables d'environnement
?>
```

### 2.4 Upload de fichiers

**HTML :**
```html
<form method="POST" action="upload.php" enctype="multipart/form-data">
  <input type="file" name="photo" accept="image/*" required>
  <button type="submit">Uploader</button>
</form>
```

⚠️ N'oubliez **pas** `enctype="multipart/form-data"` !

**PHP :**
```php
<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
    $file = $_FILES['photo'];
    
    $nom_fichier = $file['name'];        // Nom original
    $chemin_temp = $file['tmp_name'];    // Emplacement temporaire
    $erreur = $file['error'];            // Code d'erreur (0 = OK)
    $taille = $file['size'];             // Taille en octets
    
    // Vérifier si pas d'erreur
    if ($erreur == 0) {
      // Vérifier la taille (max 5 MB)
      if ($taille <= 5000000) {
        // Déplacer vers le dossier final
        move_uploaded_file($chemin_temp, "uploads/" . $nom_fichier);
        echo "Fichier uploadé!";
      } else {
        echo "Fichier trop volumineux";
      }
    } else {
      echo "Erreur lors de l'upload";
    }
  }
?>
```

### 2.5 Validation et sécurité

⚠️ **JAMAIS** faire confiance aux données du client !

#### Validation basique
```php
<?php
  if (empty($_POST['email'])) {
    echo "Email vide";
  }
  
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo "Email invalide";
  }
  
  if (strlen($_POST['password']) < 8) {
    echo "Mot de passe trop court";
  }
?>
```

#### Sanitization (nettoyage)
```php
<?php
  // Supprimer les balises HTML
  $nom = strip_tags($_POST['nom']);
  
  // Échapper les caractères spéciaux (pour DB)
  $email = htmlspecialchars($_POST['email']);
  
  // Supprimer les espaces inutiles
  $nom = trim($_POST['nom']);
?>
```

---

## 🗄️ Partie 3 : MySQL & Requêtes

### 3.1 Architecture client-serveur

```
Application PHP                 Serveur MySQL
    |                                |
    | --- Connexion -------->        |
    | --- Requête SQL ------>        |
    | <---- Résultat ------- |
    |                   Traitement BD
```

### 3.2 Connexion à MySQL (MySQLi)

```php
<?php
  // Connexion
  $connection = new mysqli(
    "localhost",    // Serveur
    "root",         // Utilisateur
    "password",     // Mot de passe
    "base_cv"       // Base de données
  );
  
  // Vérifier la connexion
  if ($connection->connect_error) {
    die("Erreur de connexion: " . $connection->connect_error);
  }
  
  echo "Connecté!";
  
  // Fermer la connexion
  $connection->close();
?>
```

### 3.3 Requêtes simples (non recommandé ❌)

```php
<?php
  $nom = "Anaelle";
  
  // ❌ DANGEREUX : vulnérable à l'injection SQL
  $sql = "INSERT INTO etudiants (nom) VALUES ('$nom')";
  $connection->query($sql);
?>
```

### 3.4 Requêtes préparées (recommandé ✅)

Les **requêtes préparées** protègent contre l'injection SQL :

```php
<?php
  $nom = "Alice";
  $email = "alice@junia.fr";
  
  // Préparer la requête
  $stmt = $connection->prepare(
    "INSERT INTO etudiants (nom, email) VALUES (?, ?)"
  );
  
  // Lier les paramètres
  $stmt->bind_param("ss", $nom, $email);
  // "ss" = string, string
  // "si" = string, integer
  // "sd" = string, double
  
  // Exécuter
  if ($stmt->execute()) {
    echo "Inséré!";
  } else {
    echo "Erreur: " . $stmt->error;
  }
  
  $stmt->close();
?>
```

### 3.5 CRUD — CREATE, READ, UPDATE, DELETE

#### CREATE (Insérer)
```php
<?php
  $stmt = $connection->prepare(
    "INSERT INTO etudiants (nom, email, age) VALUES (?, ?, ?)"
  );
  $stmt->bind_param("ssi", $nom, $email, $age);
  $stmt->execute();
?>
```

#### READ (Lire)
```php
<?php
  // Lire un étudiant
  $stmt = $connection->prepare(
    "SELECT * FROM etudiants WHERE id = ?"
  );
  $stmt->bind_param("i", $id);
  $stmt->execute();
  
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();  // Récupérer une ligne
  
  echo $row['nom'];
  echo $row['email'];
?>
```

#### UPDATE (Mettre à jour)
```php
<?php
  $stmt = $connection->prepare(
    "UPDATE etudiants SET nom = ?, email = ? WHERE id = ?"
  );
  $stmt->bind_param("ssi", $nom, $email, $id);
  $stmt->execute();
  
  echo "Mis à jour!";
?>
```

#### DELETE (Supprimer)
```php
<?php
  $stmt = $connection->prepare(
    "DELETE FROM etudiants WHERE id = ?"
  );
  $stmt->bind_param("i", $id);
  $stmt->execute();
  
  echo "Supprimé!";
?>
```

---

## 🔐 Partie 4 : Sessions & Authentification

### 4.1 Principes des sessions

Une **session** permet de mémoriser des informations sur un utilisateur entre plusieurs pages :

```
Demande 1          Serveur            Navigateur
  |  -------->        |
  |    Créer session  |
  |    ID: abc123     | <----- Envoyer ID de session
  |    $data stored   |
  |
  |                   | Cookie: PHPSESSID=abc123
  |
Demande 2 (avec cookie)
  |  -------->        |
  |    Reconnaître    |
  |    Retrouver $data|
  |    pour cet user  |
```

### 4.2 Démarrer une session

**À faire en PREMIER** sur chaque page :
```php
<?php
  session_start();  // Démarrer ou récupérer la session
  
  // Ajouter des données à la session
  $_SESSION['user_id'] = 1;
  $_SESSION['nom'] = "Anaelle";
  $_SESSION['is_logged_in'] = true;
?>
```

### 4.3 Exemple : Authentification simple

**login.php :**
```php
<?php
  session_start();
  
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Chercher l'utilisateur dans la base (requête préparée!)
    $stmt = $connection->prepare(
      "SELECT id, nom, password_hash FROM etudiants WHERE email = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      
      // Vérifier le password (avec hash!)
      if (password_verify($password, $user['password_hash'])) {
        // Succès : sauvegarder la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['is_logged_in'] = true;
        
        header("Location: dashboard.php");
        exit();
      }
    }
    
    // Échec
    $error = "Email ou mot de passe incorrect";
  }
?>
<form method="POST">
  <input type="email" name="email" required>
  <input type="password" name="password" required>
  <button type="submit">Login</button>
</form>
<?php if (isset($error)) echo $error; ?>
```

**dashboard.php :**
```php
<?php
  session_start();
  
  // Vérifier que l'utilisateur est connecté
  if (!isset($_SESSION['is_logged_in'])) {
    header("Location: login.php");
    exit();
  }
  
  echo "Bienvenue, " . $_SESSION['nom'];
?>
```

**logout.php :**
```php
<?php
  session_start();
  
  // Supprimer toutes les données de session
  session_destroy();
  
  header("Location: login.php");
  exit();
?>
```

### 4.4 Cookies

Les **cookies** sont des fichiers stockés côté client, utiles pour les préférences :

```php
<?php
  // Créer un cookie (avant toute sortie!)
  setcookie("theme", "dark", time() + 60*60*24*30);
  // Expire dans 30 jours
  
  // Lire un cookie
  if (isset($_COOKIE['theme'])) {
    echo $_COOKIE['theme'];  // dark
  }
  
  // Supprimer un cookie
  setcookie("theme", "", time() - 3600);
?>
```

⚠️ `setcookie()` doit être appelé AVANT tout affichage !

---

## 💡 Bonnes pratiques

### Sécurité
✅ Toujours valider les données du client  
✅ Utiliser des requêtes préparées (protection SQL injection)  
✅ Hasher les mots de passe : `password_hash()` et `password_verify()`  
✅ Définir les permissions de fichiers correctement  

### Structure
✅ Séparer logique (PHP) et présentation (HTML)  
✅ Créer un fichier de configuration pour les connexions DB  
✅ Utiliser des fonctions réutilisables  

### Performance
✅ Fermer les connexions : `$connection->close()`  
✅ Limiter les requêtes à la base  
✅ Utiliser l'indexation des colonnes  

---

## 🔗 Ressources

📚 [Documentation PHP officielle](https://www.php.net/docs.php)  
📚 [MySQLi Manuel](https://www.php.net/manual/en/book.mysqli.php)  
🧪 [Test localement avec XAMPP](https://www.apachefriends.org/)  

---

**Prochaine étape :** Consulter le **TP Pratique** (tp-3-cv-back-end.md) et les **mémos** (memo-php.md, memo-mysql.md)
