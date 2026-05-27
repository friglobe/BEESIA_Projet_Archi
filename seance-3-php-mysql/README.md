# 🐘 Séance 3 — PHP & MySQL | Guide Complet

**Année académique :** 2025 | **Cours :** Architecture Web AP3 | **Durée :** 8h

---

## 📚 Index des documents

### 📖 Documents pédagogiques

1. **seance-3-cours.md** — 📘 Cours complet
   - Parties 1-4 : PHP, Formulaires, MySQL, Sessions
   - Explications détaillées avec exemples
   - **À lire d'abord pour comprendre les concepts**

2. **memo-php.md** — ⚡ Référence rapide PHP
   - Synthèse des syntaxes principales
   - Utile pendant la programmation
   - Consultable rapidement

3. **memo-mysql.md** — 🗄️ Référence rapide MySQL
   - Requêtes préparées, CRUD
   - Sécurité et bonnes pratiques
   - Exemples de code directement utilisables

### 🔧 Travaux pratiques

4. **tp-3-cv-back-end.md** — 🔧 TP Pratique Complet
   - 9 étapes progressives
   - Du setup à la validation
   - Intégration front-end/back-end
   - **À faire après avoir lu le cours**

### 🗄️ Base de données

5. **bdd-cv.sql** — 💾 Script SQL
   - Création complète de la base CV
   - 12 tables + 2 vues
   - Données de test
   - À exécuter dans phpMyAdmin

---

## 🎯 Objectifs par section

### Partie 1 : PHP — Les Bases (Niveau: ⭐⭐☆)
**Après cette partie, vous pouvez :**
- Écrire des variables et structures de contrôle
- Créer des fonctions
- Manipuler les tableaux
- Comprendre la syntaxe PHP

**À pratiquer:** Petits scripts PHP isolés

---

### Partie 2 : Formulaires & HTTP (Niveau: ⭐⭐⭐)
**Après cette partie, vous pouvez :**
- Traiter un formulaire POST/GET
- Valider les données utilisateur
- Gérer les uploads de fichiers
- Comprendre la sécurité de base

**À pratiquer:** Créer un formulaire → traiter → afficher les données

---

### Partie 3 : MySQL & Requêtes (Niveau: ⭐⭐⭐⭐)
**Après cette partie, vous pouvez :**
- Vous connecter à une base MySQL
- Écrire des requêtes préparées
- Implémenter le CRUD
- Protéger contre l'injection SQL

**À pratiquer:** Requêtes SQL → API PHP → Test Postman

---

### Partie 4 : Sessions & Authentification (Niveau: ⭐⭐⭐)
**Après cette partie, vous pouvez :**
- Gérer des sessions utilisateur
- Implémenter un login/logout
- Utiliser les cookies
- Sécuriser les mots de passe (hash)

**À pratiquer:** Formulaire login → créer session → rediriger

---

## 🛠️ Outils à installer

| Outil | Lien | Utilité |
|-------|------|---------|
| **XAMPP** | https://www.apachefriends.org/ | Serveur local (Apache + PHP + MySQL) |
| **Postman** | https://www.postman.com/downloads/ | Tester les APIs |
| **phpMyAdmin** | localhost/phpmyadmin | Interface MySQL (inclus dans XAMPP) |
| **Visual Studio Code** | https://code.visualstudio.com/ | Éditeur (optionnel) |

---

## 📝 Syntaxe rapide

### Ouvrir/Fermer PHP
```php
<?php
  // Code PHP ici
?>
```

### Variable et affichage
```php
$nom = "Anaelle";
echo $nom;          // Anaelle
echo "Salut $nom";  // Salut Anaelle
```

### Boucle
```php
for ($i = 0; $i < 5; $i++) {
  echo $i;  // 0, 1, 2, 3, 4
}
```

### Fonction
```php
function sayHello($name) {
  return "Bonjour, $name!";
}
echo sayHello("Romain");  // Bonjour, Romain!
```

### Formulaire GET/POST
```html
<form method="POST" action="traiter.php">
  <input name="email">
  <button type="submit">Envoyer</button>
</form>
```

```php
<?php
  $email = $_POST['email'];
  echo "Email reçu: $email";
?>
```

### Connexion MySQL
```php
<?php
  $connection = new mysqli("localhost", "root", "", "ma_base");
  if ($connection->connect_error) die("Erreur");
?>
```

### Requête préparée
```php
<?php
  $stmt = $connection->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    echo $row['email'];
  }
?>
```

### Session
```php
<?php
  session_start();
  $_SESSION['user_id'] = 123;
  $_SESSION['nom'] = "Anaelle";
  
  // Sur une autre page
  echo $_SESSION['nom'];  // Anaelle
  
  // Logout
  session_destroy();
?>
```

---

## 🔑 Points clés à retenir

### ☀️ À faire absolument
✅ **Requêtes préparées** — Protection contre SQL injection  
✅ **Valider les données** — Ne pas faire confiance au client  
✅ **Hasher les passwords** — Utiliser `password_hash()`  
✅ **Session_start() en premier** — Sur chaque page  
✅ **Vérifier les erreurs** — Toujours vérifier la réponse MySQL  

### ❌ À ne jamais faire
❌ Requêtes SQL avec concaténation : `"SELECT * FROM users WHERE id = '$id'"`  
❌ Stocker les mots de passe en clair  
❌ Faire confiance à `$_GET`, `$_POST` sans validation  
❌ Oublier `move_uploaded_file()` après upload  
❌ Utiliser l'email comme identifiant de session  

---

## 💡 Conseils pratiques

### Débogage
```php
<?php
  var_dump($variable);     // Afficher type + valeur
  die("Arrêter ici");      // Arrêter l'exécution
  error_log("Message");    // Écrire dans le log PHP
  // Logs PHP: C:\xampp\php\logs\php_error_log
?>
```

### Tester une API
1. **Postman** — Interface graphique facile
2. **cURL** — En ligne de commande : `curl -X POST http://localhost/api/login.php`
3. **Navigateur** — Pour les GET (F12 → Network pour voir les requêtes)
4. **console JS** — `fetch()` et regarder la réponse

### Erreurs courantes
| Erreur | Cause | Solution |
|--------|-------|----------|
| `Headers already sent` | Sortie avant `header()` | Mettre `header()` avant tout `echo` |
| `Undefined variable` | Variable non définie | Utiliser `isset()` ou l'opérateur `??` |
| `SQL Syntax error` | Requête SQL mal formée | Tester la requête dans phpMyAdmin d'abord |
| `CORS error` | Front et back sur des domaines différents | Les mettre sur le même serveur local |
| `File not found` | Chemins de fichiers incorrects | Utiliser `__DIR__` ou des chemins absolus |

---

## 📚 Ressources externes

### Documentation officielle
- 📖 [PHP Manual](https://www.php.net/manual/en/)
- 📖 [MySQLi Documentation](https://www.php.net/manual/en/book.mysqli.php)
- 📖 [W3Schools PHP Tutorial](https://www.w3schools.com/php/)

### Tutoriels vidéo
- 🎥 [PHP for Beginners](https://www.youtube.com/results?search_query=php+for+beginners)
- 🎥 [MySQL Database Tutorial](https://www.youtube.com/results?search_query=mysql+tutorial)

### Outils en ligne
- 🌐 [PHP Sandbox](https://www.phpsandbox.io/)
- 🌐 [SQL Fiddle](http://sqlfiddle.com/)
- 🌐 [Regex 101](https://regex101.com/)

---

## ✅ Checklist d'apprentissage

### Concepts PHP
- [ ] Variables et types
- [ ] Opérateurs (arithmétiques, logiques)
- [ ] Structures de contrôle (if, switch, for, foreach)
- [ ] Fonctions (définition, paramètres, return)
- [ ] Tableaux (indexés, associatifs)

### Formulaires & HTTP
- [ ] Différence GET vs POST
- [ ] Accéder aux données : `$_POST`, `$_GET`
- [ ] Validation basique
- [ ] Upload de fichiers
- [ ] Sécurité : validation, sanitization

### MySQL & Requêtes
- [ ] Connexion MySQLi
- [ ] Requêtes préparées
- [ ] CRUD complet
- [ ] Gestion d'erreurs
- [ ] Transactions (bonus)

### Sessions & Auth
- [ ] Démarrer une session
- [ ] Stocker données en session
- [ ] Hashing de password
- [ ] Login/logout
- [ ] Protection des pages

### TP Pratique
- [ ] Créer la base de données
- [ ] Configurer config.php
- [ ] Implémenter les APIs
- [ ] Connecter le front-end
- [ ] Tester et déboguer

---

## 🎓 Évaluation

**Contrôle continu :** Participation TP + Correctness APIs  
**Évaluation pratique :** Soutenance du projet (19/06/2025)  
**Critères :**
- ✅ Fonctionnalités implémentées (40%)
- ✅ Sécurité et bonnes pratiques (30%)
- ✅ Qualité du code et documentation (20%)
- ✅ Présentation (10%)

---

## 🚀 Après cette séance

Vous serez prêt pour :
- **Backend PHP/MySQL** pour tout projet web
- **Intégration front-end/back-end**
- **API REST** (évolution naturelle)
- **Sécurité web** (concepts fondamentaux)
- **Déploiement** sur un serveur (Heroku, etc.)

---

## 📞 Support

**Besoin d'aide ?**
- 💬 Posez vos questions en TP (instructeur disponible)
- 📧 Email à l'instructeur
- 🔗 Ressources des liens ci-dessus
- 👥 Travail en groupe (trinôme)

---

**Bonne chance ! 🎉 — Vous avez tous les outils pour réussir.**

*Dernière mise à jour : Séance 3, 2025*
