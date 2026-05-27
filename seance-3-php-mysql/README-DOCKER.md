# 🐳 Docker pour JUNIA Séance 3 — Guide Complet

**Environnement Docker complètement configuré pour PHP/MySQL**

---

## 🎯 Qu'est-ce que Docker?

Docker crée des **conteneurs** (mini-serveurs) qui contiennent tout ce dont vous avez besoin :
- Apache (serveur web)
- PHP 8.1
- MySQL 8.0
- phpMyAdmin
- Logs centralisés

**Avantage :** Pas d'installation manuelle, tout fonctionne identique sur tous les ordinateurs! ✅

---

## 📋 Prérequis

### Windows
1. **Docker Desktop** : https://www.docker.com/products/docker-desktop
   - Incluant Docker Compose
   
2. **WSL 2** (Windows Subsystem for Linux)
   - Installer depuis le Microsoft Store
   - Ou via la commande : `wsl --install`

### Mac
1. **Docker Desktop** : https://www.docker.com/products/docker-desktop
   - Incluant Docker Compose

### Linux
```bash
# Ubuntu/Debian
sudo apt-get update
sudo apt-get install docker.io docker-compose

# Ajouter votre utilisateur au groupe docker
sudo usermod -aG docker $USER
newgrp docker

# Tester
docker --version
docker-compose --version
```

---

## 🚀 Démarrage rapide

### 1️⃣ Préparer le projet

```bash
# Cloner ou télécharger le projet
git clone <repo-url> junia-seance3
cd junia-seance3

# Créer le fichier .env
cp .env.example .env

# Créer les dossiers nécessaires
mkdir -p app/frontend
mkdir -p app/backend/api
mkdir -p app/uploads
mkdir -p database
mkdir -p logs/apache
mkdir -p logs/mysql
```

### 2️⃣ Copier les fichiers de la Séance 3

```bash
# Copier les fichiers frontend (Seance 2)
cp -r <chemin-seance2>/frontend/* app/frontend/

# Copier le script SQL
cp bdd-cv.sql database/

# Copier les API PHP
cp -r <chemin-seance3>/backend/* app/backend/
```

### 3️⃣ Démarrer les services

```bash
# Démarrer Docker Compose
docker-compose up -d

# ou avec --build si première fois ou changements
docker-compose up -d --build
```

✅ **C'est tout!** Les services vont démarrer.

### 4️⃣ Vérifier le statut

```bash
# Voir les services actifs
docker-compose ps

# Voir les logs
docker-compose logs -f web

# Arrêter les services
docker-compose down
```

---

## 🌐 Accéder aux services

Une fois démarrés, accédez à :

| Service | URL | Identifiants |
|---------|-----|--------------|
| **Application** | http://localhost | - |
| **phpMyAdmin** | http://localhost:8081 | `cv_user` / `password123` |
| **MailHog** | http://localhost:8025 | - |
| **MySQL** | localhost:3306 | `cv_user` / `password123` |

---

## 📁 Structure du projet

```
junia-seance3/
├── docker-compose.yml          ← Orchestration
├── Dockerfile                  ← Image PHP/Apache
├── .env.example               ← Variables d'environnement
├── .dockerignore
│
├── config/
│   ├── php.ini                ← Config PHP
│   ├── apache.conf            ← Config Apache
│   ├── apache-ssl.conf        ← HTTPS (optionnel)
│   └── docker-entrypoint.sh   ← Script init
│
├── app/
│   ├── frontend/              ← HTML/CSS/JS
│   │   ├── index.html
│   │   ├── style.css
│   │   └── app.js
│   ├── backend/               ← PHP API
│   │   ├── config.php
│   │   ├── api/
│   │   │   ├── login.php
│   │   │   ├── enregistrer-cv.php
│   │   │   └── profils.php
│   │   └── uploads/           ← Photos
│   └── index.php              ← Accueil
│
├── database/
│   └── bdd-cv.sql             ← Script initial
│
└── logs/
    ├── apache/
    └── mysql/
```

---

## 🔧 Commandes Docker courantes

### Démarrer/Arrêter

```bash
# Démarrer les services
docker-compose up -d

# Arrêter les services
docker-compose down

# Arrêter et supprimer les volumes (ATTENTION: perte données!)
docker-compose down -v

# Redémarrer les services
docker-compose restart

# Redémarrer un service spécifique
docker-compose restart web
```

### Logs et Débogage

```bash
# Voir les logs en direct
docker-compose logs -f

# Logs d'un service spécifique
docker-compose logs -f web
docker-compose logs -f db

# 100 dernières lignes
docker-compose logs --tail=100 web

# Arrêter de suivre (Ctrl+C)
```

### Exécuter des commandes

```bash
# Entrer dans le conteneur PHP
docker-compose exec web bash

# Exécuter une commande PHP
docker-compose exec web php -v

# Entrer dans MySQL
docker-compose exec db mysql -u cv_user -p cv_platform
# Password: password123

# Exécuter un script PHP
docker-compose exec web php /var/www/html/test.php
```

### Construire une nouvelle image

```bash
# Reconstruire après changement du Dockerfile
docker-compose up -d --build

# Voir les images
docker images

# Supprimer les images inutilisées
docker image prune
```

---

## 🗄️ Gestion de la base de données

### Via phpMyAdmin (GUI)

1. Allez à : http://localhost:8081
2. Connexion :
   - Serveur: `db`
   - Utilisateur: `cv_user`
   - Mot de passe: `password123`
3. Sélectionnez la base `cv_platform`

### Via ligne de commande

```bash
# Entrer dans MySQL
docker-compose exec db mysql -u cv_user -p cv_platform

# Une fois connecté:
mysql> SHOW TABLES;
mysql> SELECT * FROM etudiants;
mysql> EXIT;
```

### Sauvegarder la base

```bash
# Exporter la base en fichier SQL
docker-compose exec db mysqldump -u cv_user -p cv_platform > backup.sql
# Password: password123

# Importer depuis un fichier
docker-compose exec -T db mysql -u cv_user -p cv_platform < backup.sql
```

---

## 💾 Volumes et Persistance

Les volumes Docker persistent les données même si les conteneurs s'arrêtent :

```yaml
volumes:
  db_data:        # ← Données MySQL persistent
  ./app:/var/www/html  # ← Code en local sync
```

**Attention :** Si vous supprimez `docker-compose down -v`, les données de `db_data` seront perdues!

Pour sauvegarder avant suppression :
```bash
docker-compose exec db mysqldump -u root -p --all-databases > full-backup.sql
```

---

## 🔐 Sécurité

### En développement (actuel)
- ✅ Développement facile
- ✅ Logs visibles
- ⚠️ Pas d'authentification forte
- ⚠️ Erreurs affichées

### En production (modifications)

1. **Changer les mots de passe :**
   ```bash
   # Éditer .env
   MYSQL_ROOT_PASSWORD=unMotDePasseComplexe123!
   DB_PASSWORD=unAutreMotDePasse456!
   ```

2. **Désactiver xdebug :**
   ```bash
   # Éditer Dockerfile, retirer xdebug
   ```

3. **Activer HTTPS :**
   ```bash
   # Les certificats SSL sont générés automatiquement
   # En production, utiliser Let's Encrypt
   ```

4. **Limiter les logs :**
   ```bash
   # Éditer config/php.ini
   display_errors = Off
   error_reporting = E_ALL
   log_errors = On
   ```

---

## 🐛 Dépannage

### "Port 80 already in use"

```bash
# Trouver qui utilise le port
sudo lsof -i :80

# Changer le port dans docker-compose.yml
ports:
  - "8080:80"  # ← Utiliser le port 8080

# Accès: http://localhost:8080
```

### MySQL ne démarre pas

```bash
# Voir les logs
docker-compose logs db

# Vérifier les permissions
docker-compose exec db ls -la /var/lib/mysql

# Réinitialiser (ATTENTION: perte données!)
docker-compose down -v
docker-compose up -d
```

### PHP ne trouve pas MySQL

```bash
# Vérifier la connexion
docker-compose exec web ping db

# Vérifier les variables d'environnement
docker-compose exec web env | grep DB_

# Test de connexion
docker-compose exec web mysql -h db -u cv_user -p cv_platform
```

### Fichiers uploadés non visibles

```bash
# Vérifier les permissions
docker-compose exec web ls -la /var/www/html/uploads

# Fixer les permissions
docker-compose exec web chown -R www-data:www-data /var/www/html/uploads
```

### Performance lente

```bash
# Vérifier l'utilisation des ressources
docker stats

# Augmenter la mémoire Docker :
# Windows/Mac : Docker Desktop → Settings → Resources

# Réduire les logs
docker-compose logs --tail=10 web
```

---

## 📊 Monitoring

### Voir les statistiques en temps réel

```bash
# Utilisation CPU/Mémoire des conteneurs
docker stats

# Détails conteneurs
docker-compose ps

# Inspect un service
docker-compose exec web php -r "phpinfo();" | head -20
```

### Vérifier la santé des services

```bash
# Docker compose affiche le statut
docker-compose ps

# Résultats:
# web       : Up (healthy)
# db        : Up (healthy)
# phpmyadmin: Up
```

---

## 🔄 Mise à jour du projet

### Récupérer les dernières fichiers

```bash
# Si Git
git pull origin main

# Reconstruire les images si changements Dockerfile
docker-compose up -d --build
```

### Appliquer les changements

```bash
# Redémarrer tous les services
docker-compose restart

# Ou reconstruire si changeemts Dockerfile/docker-compose.yml
docker-compose down
docker-compose up -d --build
```

---

## 🛑 Arrêter et nettoyer

### Arrêter sans supprimer

```bash
docker-compose stop
```

Les données persistent. Pour recommencer :
```bash
docker-compose start
```

### Arrêter et supprimer

```bash
# Supprimer les conteneurs (données persistent)
docker-compose down

# Supprimer les conteneurs ET volumes (⚠️ perte données!)
docker-compose down -v
```

### Nettoyer l'espace disque

```bash
# Supprimer images/conteneurs/networks inutilisés
docker system prune

# Supprimer aussi les volumes
docker system prune -a --volumes
```

---

## 📚 Fichiers importants

### docker-compose.yml
Orchestre les services (web, db, phpmyadmin). À modifier pour :
- Changer les ports
- Ajouter des services
- Modifier les volumes

### Dockerfile
Construit l'image PHP/Apache. À modifier pour :
- Ajouter des extensions PHP
- Changer les versions
- Modifier les packages système

### config/docker-entrypoint.sh
Script d'initialisation exécuté au démarrage. Responsable de :
- Créer les dossiers
- Générer les certificats SSL
- Attendre MySQL
- Créer config.php

### .env
Variables d'environnement à **personnaliser** avant de démarrer.

---

## 🚀 Prochaines étapes

1. **Démarrer l'environnement :**
   ```bash
   docker-compose up -d
   ```

2. **Vérifier que tout fonctionne :**
   - Accédez à http://localhost
   - Connectez-vous à phpMyAdmin

3. **Copier les fichiers de la Seance 3 :**
   - Frontend → `app/frontend/`
   - Backend → `app/backend/`
   - SQL → `database/`

4. **Tester les APIs :**
   - Utiliser Postman
   - Ou tester dans le navigateur

5. **Développer et tester :**
   - Les fichiers `app/` sont synchronisés avec votre ordinateur
   - Modifiez, sauvegardez, rafraîchissez le navigateur
   - Les changements sont immédiats!

---

## 📞 Support

### Vérifier que Docker fonctionne

```bash
docker --version
docker-compose --version
```

### Voir les erreurs

```bash
docker-compose logs web
docker-compose logs db
```

### Réinitialiser complètement

```bash
# Supprimer tout
docker-compose down -v

# Reconstruire
docker-compose up -d --build
```

---

## 🎓 Intégration avec la Seance 3

### Structure attendue

```
app/
├── frontend/         ← Seance 2 (formulaire CV)
├── backend/
│   ├── config.php    ← Généré automatiquement
│   ├── api/
│   │   ├── login.php
│   │   ├── enregistrer-cv.php
│   │   └── profils.php
│   └── uploads/
└── index.php         ← Accueil (créé auto)
```

### Configuration automatique

Le script d'entrypoint crée automatiquement :
- ✅ `/var/www/html/backend/config.php`
- ✅ `/var/www/html/uploads/` avec permissions
- ✅ `/var/www/html/index.php` (page d'accueil)
- ✅ Base de données MySQL initialisée

**Vous n'avez rien à configurer manuellement!** 🎉

---

## ✅ Checklist de démarrage

- [ ] Docker Desktop installé
- [ ] `.env` créé (copié de `.env.example`)
- [ ] `docker-compose up -d` exécuté
- [ ] Services au statut `Up` (commande `docker-compose ps`)
- [ ] http://localhost accessible
- [ ] http://localhost:8081 (phpMyAdmin) accessible
- [ ] Base de données `cv_platform` visible dans phpMyAdmin
- [ ] Fichiers copiés dans `app/`
- [ ] API testable (GET http://localhost/backend/api/profils.php)

---

**Vous avez un environnement Docker complètement fonctionnel! 🐳✨**

Pour toute question, consultez les logs : `docker-compose logs -f`

*Dernière mise à jour : Seance 3 | 2025*
