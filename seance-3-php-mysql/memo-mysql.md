# 🗄️ Mémo MySQL + MySQLi — Requêtes & Sécurité

## Connexion

### Connexion basique
```php
<?php
  $connection = new mysqli(
    "localhost",    // Serveur
    "root",         // Utilisateur
    "password",     // Mot de passe
    "base_cv"       // Base de données
  );
  
  // Vérifier la connexion
  if ($connection->connect_error) {
    die("Erreur: " . $connection->connect_error);
  }
  
  // Définir l'encodage UTF-8
  $connection->set_charset("utf8mb4");
  
  // Fermer la connexion
  $connection->close();
?>
```

### Fichier de configuration (config.php)
```php
<?php
  define("DB_HOST", "localhost");
  define("DB_USER", "root");
  define("DB_PASS", "password");
  define("DB_NAME", "base_cv");
  
  $connection = new mysqli(
    DB_HOST,
    DB_USER,
    DB_PASS,
    DB_NAME
  );
  
  if ($connection->connect_error) {
    die("Connexion échouée: " . $connection->connect_error);
  }
  
  $connection->set_charset("utf8mb4");
?>
```

---

## Requêtes Préparées (☀️ TOUJOURS les utiliser!)

### Syntaxe
```php
<?php
  // Étape 1 : Préparer
  $stmt = $connection->prepare("SELECT * FROM etudiants WHERE id = ?");
  
  // Étape 2 : Lier les paramètres
  $stmt->bind_param("i", $id);  // "i" = integer
  
  // Étape 3 : Exécuter
  $stmt->execute();
  
  // Étape 4 : Récupérer les résultats
  $result = $stmt->get_result();
  
  // Fermer
  $stmt->close();
?>
```

### Types de paramètres
```
"i"  = integer (entier)
"d"  = double (décimal)
"s"  = string (texte)
"b"  = blob (binaire)
```

### Exemples
```php
<?php
  // 1 paramètre integer
  $stmt = $connection->prepare("SELECT * FROM etudiants WHERE id = ?");
  $stmt->bind_param("i", $id);
  
  // 2 paramètres string
  $stmt = $connection->prepare("SELECT * FROM etudiants WHERE nom = ? AND email = ?");
  $stmt->bind_param("ss", $nom, $email);
  
  // 1 string, 1 integer, 1 string
  $stmt = $connection->prepare("INSERT INTO logs (user, action, date) VALUES (?, ?, ?)");
  $stmt->bind_param("sis", $user, $id, $date);
  
  // Plusieurs types
  $stmt = $connection->prepare(
    "UPDATE etudiants SET nom = ?, age = ?, prix = ? WHERE id = ?"
  );
  $stmt->bind_param("ssdi", $nom, $age, $prix, $id);
?>
```

---

## CRUD Operations

### CREATE (Insérer)

#### Requête préparée
```php
<?php
  $nom = "Anaelle";
  $email = "anaelle@junia.fr";
  $age = 21;
  
  $stmt = $connection->prepare(
    "INSERT INTO etudiants (nom, email, age) VALUES (?, ?, ?)"
  );
  $stmt->bind_param("ssi", $nom, $email, $age);
  
  if ($stmt->execute()) {
    $last_id = $connection->insert_id;  // ID de la dernière insertion
    echo "Inséré! ID: $last_id";
  } else {
    echo "Erreur: " . $stmt->error;
  }
  
  $stmt->close();
?>
```

#### Insérer plusieurs lignes
```php
<?php
  $stmt = $connection->prepare(
    "INSERT INTO etudiants (nom, email, age) VALUES (?, ?, ?)"
  );
  
  $noms = ["Anaelle", "Romain", "Lucas"];
  $emails = ["anaelle@junia.fr", "romain@junia.fr", "lucas@junia.fr"];
  $ages = [21, 22, 20];
  
  for ($i = 0; $i < count($noms); $i++) {
    $stmt->bind_param("ssi", $noms[$i], $emails[$i], $ages[$i]);
    $stmt->execute();
  }
  
  $stmt->close();
?>
```

### READ (Lire)

#### Une seule ligne
```php
<?php
  $id = 1;
  
  $stmt = $connection->prepare(
    "SELECT id, nom, email, age FROM etudiants WHERE id = ?"
  );
  $stmt->bind_param("i", $id);
  $stmt->execute();
  
  $result = $stmt->get_result();
  
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();  // Tableau associatif
    echo $row['nom'];
    echo $row['email'];
  } else {
    echo "Étudiant non trouvé";
  }
  
  $stmt->close();
?>
```

#### Toutes les lignes
```php
<?php
  $stmt = $connection->prepare(
    "SELECT id, nom, email FROM etudiants ORDER BY nom ASC"
  );
  $stmt->execute();
  
  $result = $stmt->get_result();
  
  $etudiants = [];
  while ($row = $result->fetch_assoc()) {
    $etudiants[] = $row;
  }
  
  // Utiliser les données
  foreach ($etudiants as $etudiant) {
    echo $etudiant['nom'];
  }
  
  $stmt->close();
?>
```

#### Requête avec filtre
```php
<?php
  $promotion = "GEC-2025";
  $age_min = 20;
  
  $stmt = $connection->prepare(
    "SELECT * FROM etudiants WHERE promotion = ? AND age >= ? ORDER BY nom"
  );
  $stmt->bind_param("si", $promotion, $age_min);
  $stmt->execute();
  
  $result = $stmt->get_result();
  
  while ($row = $result->fetch_assoc()) {
    echo $row['nom'] . " (" . $row['age'] . " ans)";
  }
  
  $stmt->close();
?>
```

### UPDATE (Mettre à jour)

#### Mettre à jour une ligne
```php
<?php
  $nom = "Anaelle Dupont";
  $email = "anaelle.dupont@junia.fr";
  $id = 1;
  
  $stmt = $connection->prepare(
    "UPDATE etudiants SET nom = ?, email = ? WHERE id = ?"
  );
  $stmt->bind_param("ssi", $nom, $email, $id);
  
  if ($stmt->execute()) {
    echo "Mise à jour réussie! Lignes modifiées: " . $stmt->affected_rows;
  } else {
    echo "Erreur: " . $stmt->error;
  }
  
  $stmt->close();
?>
```

#### Incrémenter une colonne
```php
<?php
  $id = 1;
  
  // Incrémenter le nombre de convocations
  $stmt = $connection->prepare(
    "UPDATE etudiants SET nb_convocations = nb_convocations + 1 WHERE id = ?"
  );
  $stmt->bind_param("i", $id);
  $stmt->execute();
  
  $stmt->close();
?>
```

### DELETE (Supprimer)

#### Supprimer une ligne
```php
<?php
  $id = 1;
  
  $stmt = $connection->prepare(
    "DELETE FROM etudiants WHERE id = ?"
  );
  $stmt->bind_param("i", $id);
  
  if ($stmt->execute()) {
    echo "Supprimé! Lignes affectées: " . $stmt->affected_rows;
  } else {
    echo "Erreur: " . $stmt->error;
  }
  
  $stmt->close();
?>
```

#### Supprimer plusieurs lignes
```php
<?php
  $promotion = "GEC-2023";  // Ancienne promo
  
  $stmt = $connection->prepare(
    "DELETE FROM etudiants WHERE promotion = ?"
  );
  $stmt->bind_param("s", $promotion);
  $stmt->execute();
  
  echo "Supprimés: " . $stmt->affected_rows . " enregistrements";
  
  $stmt->close();
?>
```

---

## Requêtes Avancées

### COUNT (Compter les lignes)
```php
<?php
  $stmt = $connection->prepare(
    "SELECT COUNT(*) as total FROM etudiants"
  );
  $stmt->execute();
  
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  
  echo "Total: " . $row['total'];
  
  $stmt->close();
?>
```

### GROUP BY et aggregate functions
```php
<?php
  // Nombre d'étudiants par promotion
  $stmt = $connection->prepare(
    "SELECT promotion, COUNT(*) as nb 
     FROM etudiants 
     GROUP BY promotion"
  );
  $stmt->execute();
  
  $result = $stmt->get_result();
  
  while ($row = $result->fetch_assoc()) {
    echo $row['promotion'] . ": " . $row['nb'] . " étudiants";
  }
  
  $stmt->close();
?>
```

### ORDER BY et LIMIT
```php
<?php
  $limit = 10;
  $offset = 0;  // Pour pagination
  
  $stmt = $connection->prepare(
    "SELECT * FROM etudiants 
     ORDER BY nom ASC 
     LIMIT ? OFFSET ?"
  );
  $stmt->bind_param("ii", $limit, $offset);
  $stmt->execute();
  
  $result = $stmt->get_result();
  
  while ($row = $result->fetch_assoc()) {
    echo $row['nom'];
  }
  
  $stmt->close();
?>
```

### Recherche (LIKE)
```php
<?php
  $recherche = "%anaelle%";  // % = wildcard
  
  $stmt = $connection->prepare(
    "SELECT * FROM etudiants WHERE nom LIKE ? OR email LIKE ?"
  );
  $stmt->bind_param("ss", $recherche, $recherche);
  $stmt->execute();
  
  $result = $stmt->get_result();
  
  while ($row = $result->fetch_assoc()) {
    echo $row['nom'];
  }
  
  $stmt->close();
?>
```

### JOIN (Joindre deux tables)
```php
<?php
  // Récupérer les entreprises qui ont convoqué les étudiants
  $stmt = $connection->prepare(
    "SELECT e.nom as etudiant, ent.nom as entreprise 
     FROM etudiants e
     JOIN convocations c ON e.id = c.etudiant_id
     JOIN entreprises ent ON c.entreprise_id = ent.id"
  );
  $stmt->execute();
  
  $result = $stmt->get_result();
  
  while ($row = $result->fetch_assoc()) {
    echo $row['etudiant'] . " convoqué par " . $row['entreprise'];
  }
  
  $stmt->close();
?>
```

---

## Gestion des erreurs

```php
<?php
  $stmt = $connection->prepare("SELECT * FROM etudiants WHERE id = ?");
  
  if (!$stmt) {
    echo "Erreur de préparation: " . $connection->error;
  }
  
  $stmt->bind_param("i", $id);
  
  if (!$stmt->execute()) {
    echo "Erreur d'exécution: " . $stmt->error;
  }
  
  $result = $stmt->get_result();
  
  if (!$result) {
    echo "Erreur de résultat: " . $connection->error;
  }
  
  $stmt->close();
?>
```

---

## Transactions (Atomicité)

Utile pour plusieurs requêtes qui doivent réussir ensemble :

```php
<?php
  $connection->begin_transaction();
  
  try {
    // Requête 1 : Insérer étudiant
    $stmt1 = $connection->prepare(
      "INSERT INTO etudiants (nom, email) VALUES (?, ?)"
    );
    $stmt1->bind_param("ss", $nom, $email);
    $stmt1->execute();
    $etudiant_id = $connection->insert_id;
    
    // Requête 2 : Ajouter CV
    $stmt2 = $connection->prepare(
      "INSERT INTO cvs (etudiant_id, contenu) VALUES (?, ?)"
    );
    $stmt2->bind_param("is", $etudiant_id, $contenu);
    $stmt2->execute();
    
    // Si tout réussit
    $connection->commit();
    echo "Transacation réussie!";
  } catch (Exception $e) {
    // Si erreur, annuler tout
    $connection->rollback();
    echo "Erreur: " . $e->getMessage();
  }
?>
```

---

## Sécurité

### ✅ À FAIRE
```php
<?php
  // Toujours utiliser les requêtes préparées
  $stmt = $connection->prepare("SELECT * FROM etudiants WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
?>
```

### ❌ À NE PAS FAIRE
```php
<?php
  // DANGEREUX! SQL Injection possible
  $email = $_POST['email'];
  $result = $connection->query(
    "SELECT * FROM etudiants WHERE email = '$email'"
  );
?>
```

### Exemple d'injection SQL
```
Email: ' OR '1'='1
Requête non sécurisée:
SELECT * FROM etudiants WHERE email = '' OR '1'='1'
→ Retourne TOUS les étudiants!

Requête préparée:
SELECT * FROM etudiants WHERE email = ?
bind_param("s", "' OR '1'='1'")
→ Cherche un email littéral: ' OR '1'='1'
→ Pas d'injection!
```

---

## Utile à savoir

### insert_id
```php
<?php
  $stmt = $connection->prepare(
    "INSERT INTO etudiants (nom) VALUES (?)"
  );
  $stmt->bind_param("s", $nom);
  $stmt->execute();
  
  $last_id = $connection->insert_id;  // ID généré
?>
```

### affected_rows
```php
<?php
  $stmt = $connection->prepare("UPDATE etudiants SET age = ? WHERE promotion = ?");
  $stmt->bind_param("is", $age, $promo);
  $stmt->execute();
  
  echo $stmt->affected_rows;  // Nombre de lignes modifiées
?>
```

### num_rows
```php
<?php
  $stmt = $connection->prepare("SELECT * FROM etudiants");
  $stmt->execute();
  
  $result = $stmt->get_result();
  echo $result->num_rows;  // Nombre de lignes en résultat
?>
```

---

## Fonctions MySQL utiles

```sql
COUNT(*)           -- Nombre de lignes
SUM(colonne)       -- Somme
AVG(colonne)       -- Moyenne
MIN(colonne)       -- Minimum
MAX(colonne)       -- Maximum
UPPER(colonne)     -- Majuscules
LOWER(colonne)     -- Minuscules
LENGTH(colonne)    -- Longueur
NOW()              -- Date/heure actuelle
DATE(colonne)      -- Extraire la date
CONCAT(a, b)       -- Concaténer
```

### Exemples
```php
<?php
  // Prix moyen
  $stmt = $connection->prepare(
    "SELECT AVG(prix) as prix_moyen FROM produits"
  );
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  echo "Prix moyen: " . $row['prix_moyen'];
  
  // Noms en majuscules
  $stmt = $connection->prepare(
    "SELECT UPPER(nom) as nom_maj FROM etudiants"
  );
  $stmt->execute();
  // ...
?>
```

---

## Checklist SQL

- ✅ Utiliser les requêtes préparées
- ✅ Vérifier les erreurs (`$stmt->error`, `$connection->error`)
- ✅ Fermer les requêtes (`$stmt->close()`)
- ✅ Encoder en UTF-8 (`set_charset`)
- ✅ Valider les données côté PHP
- ✅ Utiliser les transactions pour les opérations critiques
- ✅ Indexer les colonnes souvent cherchées

---

**Besoin de plus ?** Voir seance-3-cours.md pour les explications complètes.
