# 📘 Mémo PHP — Référence Rapide

## Syntaxe de base

```php
<?php
  // Commentaire sur une ligne
  /* Commentaire
     sur plusieurs lignes */
  
  echo "Afficher du texte";
  print "Afficher du texte";  // Plus lent
  var_dump($var);             // Afficher type + valeur
  die("Stopper + message");   // Arrêter l'exécution
?>
```

---

## Variables et types

```php
<?php
  $nom = "Anaelle";            // String
  $age = 21;                   // Integer
  $prix = 19.99;               // Float
  $actif = true;               // Boolean
  $rien = null;                // Null
  
  // Vérifier le type
  var_dump($nom);              // string(5) "Anaelle"
  gettype($nom);               // "string"
  
  // Constantes (ne pas oublier define)
  define("SITE", "junia.fr");
  echo SITE;
?>
```

| Type | Exemple |
|------|---------|
| string | `"texte"`, `'texte'` |
| int | `42`, `-5` |
| float | `3.14`, `2.0` |
| bool | `true`, `false` |
| array | `[]`, `[1,2,3]` |
| object | Instances de classe |
| null | `null` |

---

## Opérateurs

### Arithmétiques
```php
<?php
  +, -, *, /, %, **  // +, -, ×, ÷, modulo, puissance
  
  $a = 10;
  echo $a + 5;   // 15
  echo $a ** 2;  // 100
?>
```

### Comparaison
```php
<?php
  ==   // égal (comparaison faible, type flexible)
  ===  // identique (même valeur ET même type)
  !=   // différent
  <>   // différent (autre syntaxe)
  !==  // non identique
  <, >, <=, >=
  <=>  // spaceship (retourne -1, 0, 1)
  
  5 == "5"   // true
  5 === "5"  // false
?>
```

### Logiques
```php
&&  or  and   // ET
||  or  or    // OU
!             // NON
xor           // OU exclusif
```

### Chaînes
```php
<?php
  $a = "Bonjour";
  $b = "monde";
  
  echo $a . " " . $b;  // Concaténation
  echo "$a $b";        // Interpolation (avec doubles quotes)
  
  // Pas d'interpolation en simple quotes
  echo '$a $b';  // $a $b (littéral)
?>
```

---

## Structures de contrôle

### if / else / elseif
```php
<?php
  if ($age >= 18) {
    echo "Majeur";
  } elseif ($age >= 16) {
    echo "16-17 ans";
  } else {
    echo "Mineur";
  }
  
  // Ternaire
  $status = ($age >= 18) ? "Majeur" : "Mineur";
?>
```

### switch
```php
<?php
  switch ($jour) {
    case "lundi":
      echo "Lundi";
      break;
    case "vendredi":
      echo "Vendredi";
      break;
    default:
      echo "Autre jour";
  }
?>
```

### Boucles
```php
<?php
  // for
  for ($i = 0; $i < 10; $i++) {
    echo $i;
  }
  
  // while
  while ($i < 10) {
    echo $i;
    $i++;
  }
  
  // do...while (au moins une fois)
  do {
    echo $i;
  } while ($i < 10);
  
  // foreach
  $fruits = ["pomme", "banane", "orange"];
  foreach ($fruits as $fruit) {
    echo $fruit;
  }
  
  // foreach avec clé
  $person = ["nom" => "Anaelle", "age" => 21];
  foreach ($person as $key => $value) {
    echo "$key: $value";
  }
  
  // break et continue
  for ($i = 0; $i < 10; $i++) {
    if ($i == 3) continue;  // Sauter cette itération
    if ($i == 7) break;     // Quitter la boucle
    echo $i;
  }
?>
```

---

## Tableaux

### Tableaux indexés (numéroté)
```php
<?php
  $fruits = ["pomme", "banane", "orange"];
  
  echo $fruits[0];        // pomme
  
  // Ajouter un élément
  $fruits[] = "raisin";   // Ajoute à la fin
  $fruits[2] = "cerise";  // Remplace
  
  // Itérer
  foreach ($fruits as $fruit) {
    echo $fruit;
  }
  
  // Fonctions utiles
  count($fruits);         // 4
  in_array("pomme", $fruits);  // true
  array_push($fruits, "kiwi");   // Ajouter à la fin
  array_pop($fruits);     // Retirer le dernier
  array_shift($fruits);   // Retirer le premier
  array_unshift($fruits, "mangue");  // Ajouter au début
  
  sort($fruits);          // Trier (modifie le tableau)
  reverse($fruits);       // Inverser
?>
```

### Tableaux associatifs
```php
<?php
  $person = [
    "nom" => "Anaelle",
    "age" => 21,
    "email" => "anaelle@junia.fr"
  ];
  
  echo $person["nom"];    // Anaelle
  
  // Ajouter
  $person["ville"] = "Lille";
  
  // Vérifier existence
  isset($person["nom"]);  // true
  
  // Supprimer
  unset($person["age"]);
  
  // Fonctions
  array_keys($person);    // ["nom", "email", "ville"]
  array_values($person);  // ["Anaelle", "anaelle@junia.fr", "Lille"]
  array_merge($p1, $p2);  // Fusionner deux tableaux
?>
```

---

## Fonctions

### Définition et appel
```php
<?php
  function sayHello($name) {
    return "Bonjour, $name!";
  }
  
  echo sayHello("Anaelle");  // Bonjour, Anaelle!
  
  // Paramètres par défaut
  function greet($name = "Ami") {
    return "Salut $name";
  }
  
  echo greet();        // Salut Ami
  echo greet("Romain");   // Salut Romain
  
  // Plusieurs paramètres
  function add($a, $b, $c = 0) {
    return $a + $b + $c;
  }
  
  // Paramètres nommés (PHP 8+)
  function makeUrl($protocol, $domain, $path) {
    return "$protocol://$domain/$path";
  }
  
  echo makeUrl(path: "api", protocol: "https", domain: "junia.fr");
?>
```

### Fonctions utiles

#### Chaînes
```php
strlen("texte")         // 5 (longueur)
strtoupper("texte")     // "TEXTE"
strtolower("TEXTE")     // "texte"
ucfirst("alice")        // "Alice"
trim("  texte  ")       // "texte" (sans espaces)
str_replace("a", "o", "anaelle")  // "onoelle"
substr("Anaelle", 0, 3)   // "ana"
strpos("Anaelle", "n")    // 2 (position)
explode(",", "a,b,c")   // ["a", "b", "c"]
implode(",", ["a", "b", "c"])  // "a,b,c"
```

#### Tableaux
```php
count($array)           // Nombre d'éléments
in_array($value, $array)  // true si existe
array_key_exists($key, $array)  // true si clé existe
sort($array)            // Trier (modifie)
array_reverse($array)   // Inverser
array_merge($a1, $a2)   // Fusionner
array_filter($array, function)  // Filtrer
array_map(function, $array)  // Transformer
```

#### Math
```php
abs(-5)       // 5
round(3.7)    // 4
floor(3.7)    // 3
ceil(3.2)     // 4
max(1, 2, 3)  // 3
min(1, 2, 3)  // 1
rand(1, 10)   // Nombre aléatoire
```

---

## Superglobales

```php
<?php
  $_GET       // Paramètres GET (URL)
  $_POST      // Données du formulaire POST
  $_REQUEST   // GET + POST
  $_SERVER    // Infos serveur
  $_FILES     // Fichiers uploadés
  $_SESSION   // Variables de session
  $_COOKIE    // Cookies
  $_ENV       // Variables d'environnement
  
  // Exemples
  $_GET['id']            // ?id=5
  $_POST['nom']          // name="nom" du formulaire
  $_SERVER['REQUEST_METHOD']  // "GET" ou "POST"
  $_SERVER['HTTP_HOST']   // "localhost"
  $_FILES['photo']['name']    // Nom du fichier
  $_SESSION['user_id']   // ID utilisateur
  $_COOKIE['theme']      // Valeur du cookie
?>
```

---

## Formulaires

### Traitement basique
```php
<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'] ?? null;  // Null coalescing operator
    $email = $_POST['email'] ?? "";
    
    // Validation
    if (empty($nom)) {
      echo "Le nom est requis";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo "Email invalide";
    }
  }
?>
```

### Upload de fichiers
```php
<?php
  if ($_FILES['photo']['error'] == 0) {
    $tmp = $_FILES['photo']['tmp_name'];
    $name = $_FILES['photo']['name'];
    
    move_uploaded_file($tmp, "uploads/$name");
  }
  
  // Codes d'erreur
  // 0 = OK
  // 1 = Fichier trop volumineux
  // 2 = Fichier trop volumineux (formulaire)
  // 3 = Upload interrompu
  // 4 = Aucun fichier
?>
```

---

## Sessions et Cookies

```php
<?php
  // Démarrer session (PREMIÈRE ligne!)
  session_start();
  
  // Ajouter à la session
  $_SESSION['user_id'] = 1;
  $_SESSION['nom'] = "Anaelle";
  
  // Lire
  echo $_SESSION['nom'];
  
  // Vérifier existence
  isset($_SESSION['user_id']);  // true
  
  // Supprimer un élément
  unset($_SESSION['user_id']);
  
  // Supprimer toute la session
  session_destroy();
  
  // Cookies (avant toute sortie!)
  setcookie("theme", "dark", time() + 86400);  // 1 jour
  
  // Lire
  echo $_COOKIE['theme'];
  
  // Supprimer
  setcookie("theme", "", time() - 3600);
?>
```

---

## Inclusions

```php
<?php
  // Inclure un fichier
  include "header.php";     // Continue si erreur
  require "config.php";     // Arrête si erreur
  
  // Include une fois
  include_once "header.php";
  require_once "config.php";
?>
```

---

## Gestion des erreurs

```php
<?php
  // Try / Catch
  try {
    // Code qui pourrait échouer
    throw new Exception("Erreur!");
  } catch (Exception $e) {
    echo $e->getMessage();
  }
  
  // Vérifier avant
  if (!file_exists($file)) {
    die("Fichier non trouvé");
  }
?>
```

---

## Sécurité

### Validation
```php
<?php
  filter_var($email, FILTER_VALIDATE_EMAIL);  // true/false
  filter_var($url, FILTER_VALIDATE_URL);
  
  if (empty($_POST['nom'])) {
    echo "Champ requis";
  }
  
  if (strlen($_POST['password']) < 8) {
    echo "Min 8 caractères";
  }
?>
```

### Sanitization
```php
<?php
  htmlspecialchars($_POST['nom']);     // Échapper HTML
  strip_tags($_POST['bio']);            // Retirer les balises
  trim($_POST['texte']);                // Retirer espaces
  filter_var($email, FILTER_SANITIZE_EMAIL);
?>
```

### Mots de passe
```php
<?php
  // Hasher
  $hash = password_hash("monPassword", PASSWORD_DEFAULT);
  
  // Vérifier
  if (password_verify($_POST['password'], $hash)) {
    echo "Correct!";
  }
?>
```

---

## Dates et heures

```php
<?php
  time();                 // Timestamp actuel (secondes)
  date("Y-m-d");          // 2025-06-19
  date("H:i:s");          // 14:30:45
  date("Y-m-d H:i:s");    // 2025-06-19 14:30:45
  
  strtotime("2025-06-19");  // Convertir en timestamp
  
  // Ajouter des jours
  $tomorrow = time() + 86400;  // +1 jour en secondes
  echo date("Y-m-d", $tomorrow);
?>
```

| Format | Signification |
|--------|---------------|
| Y | Année 4 chiffres |
| m | Mois (01-12) |
| d | Jour (01-31) |
| H | Heure (00-23) |
| i | Minute (00-59) |
| s | Seconde (00-59) |

---

## Redirection

```php
<?php
  // Rediriger (avant toute sortie!)
  header("Location: dashboard.php");
  exit();
  
  // Définir type de contenu
  header("Content-Type: application/json");
  echo json_encode(["message" => "OK"]);
?>
```

---

## JSON

```php
<?php
  // PHP vers JSON
  $data = ["nom" => "Anaelle", "age" => 21];
  echo json_encode($data);
  // {"nom":"Alice","age":21}
  
  // JSON vers PHP
  $json = '{"nom":"Anaelle","age":21}';
  $data = json_decode($json, true);  // true = tableau
  echo $data['nom'];  // Alice
?>
```

---

**Besoin de plus ?** Voir seance-3-cours.md pour les explications détaillées.
