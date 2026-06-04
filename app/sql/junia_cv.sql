-- =====================================================
-- Plateforme CV JUNIA — Schéma de base de données
-- =====================================================
-- Projet final Architecture Web AP3
-- Moteur : MySQL 8 / InnoDB / utf8mb4
--
-- Ce fichier est exécuté automatiquement au premier
-- démarrage du conteneur MySQL (docker-entrypoint-initdb.d).
-- La base "cv_platform" est déjà créée par docker-compose
-- (variable MYSQL_DATABASE), on se contente de la sélectionner.
-- =====================================================

USE cv_platform;

SET NAMES utf8mb4;

-- =====================================================
-- TABLE : etudiants
-- -----------------------------------------------------
-- Compte + données personnelles + bloc "CV" principal.
-- Les détails multi-lignes (formations, expériences,
-- compétences) sont dans des tables dédiées (1-N).
-- =====================================================
CREATE TABLE etudiants (
  id                 INT PRIMARY KEY AUTO_INCREMENT,

  -- Authentification
  email              VARCHAR(150) UNIQUE NOT NULL COMMENT 'Adresse @junia',
  password_hash      VARCHAR(255) NOT NULL        COMMENT 'Haché avec password_hash()',

  -- Identité
  nom                VARCHAR(100) NOT NULL,
  prenom             VARCHAR(100) NOT NULL,
  date_naissance     DATE,
  telephone          VARCHAR(20),
  ville              VARCHAR(100),
  photo              VARCHAR(255) COMMENT 'Chemin relatif vers la photo de profil',

  -- Contenu du CV
  biographie         TEXT COMMENT 'À propos / lettre de motivation',
  ecole              VARCHAR(120) DEFAULT 'JUNIA',
  promo              VARCHAR(20)  COMMENT 'Ex : ISEN-AP3, 2025',

  -- Recherche d'opportunités : liste de domaines
  -- (stage, alternance, cdi, mobilite) stockée en JSON
  domaines_recherche JSON COMMENT 'Ex : ["stage","alternance"]',

  -- RGPD & modération
  consentement_rgpd  TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Consentement explicite à la collecte',
  statut             ENUM('actif','suspendu') NOT NULL DEFAULT 'actif',

  date_creation      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_modification  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX idx_etu_email (email),
  INDEX idx_etu_statut (statut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Étudiants inscrits sur la plateforme';

-- =====================================================
-- TABLE : formations  (parcours académique, 1-N)
-- =====================================================
CREATE TABLE formations (
  id             INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id    INT NOT NULL,

  ecole          VARCHAR(120) NOT NULL,
  diplome        VARCHAR(120),
  specialisation VARCHAR(120),
  annee_debut    YEAR,
  annee_fin      YEAR,
  description    TEXT,

  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
  INDEX idx_form_etudiant (etudiant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Formations / diplômes des étudiants';

-- =====================================================
-- TABLE : experiences  (expériences pro, 1-N)
-- =====================================================
CREATE TABLE experiences (
  id          INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,

  entreprise  VARCHAR(120) NOT NULL,
  poste       VARCHAR(120) NOT NULL,
  date_debut  DATE,
  date_fin    DATE COMMENT 'NULL = poste en cours',
  description TEXT,

  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
  INDEX idx_exp_etudiant (etudiant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Expériences professionnelles des étudiants';

-- =====================================================
-- TABLE : competences  (techniques & langues, 1-N)
-- =====================================================
CREATE TABLE competences (
  id          INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,

  libelle     VARCHAR(100) NOT NULL,
  categorie   ENUM('technique','langue','soft-skill') NOT NULL DEFAULT 'technique',
  niveau      ENUM('debutant','intermediaire','avance','expert') DEFAULT 'intermediaire',

  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
  INDEX idx_comp_etudiant (etudiant_id),
  INDEX idx_comp_libelle (libelle)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Compétences des étudiants';

-- =====================================================
-- TABLE : entreprises  (partenaires)
-- -----------------------------------------------------
-- Comptes créés manuellement par l'admin JUNIA.
-- =====================================================
CREATE TABLE entreprises (
  id            INT PRIMARY KEY AUTO_INCREMENT,

  email_contact VARCHAR(150) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,

  nom           VARCHAR(120) NOT NULL,
  secteur       VARCHAR(100),
  description   TEXT,
  site_web      VARCHAR(255),
  logo          VARCHAR(255),
  adresse       VARCHAR(255),
  telephone     VARCHAR(20),

  statut        ENUM('actif','suspendu') NOT NULL DEFAULT 'actif',
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX idx_ent_email (email_contact),
  INDEX idx_ent_secteur (secteur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Entreprises partenaires';

-- =====================================================
-- TABLE : convocations
-- -----------------------------------------------------
-- Une entreprise convoque un étudiant à un entretien.
-- =====================================================
CREATE TABLE convocations (
  id            INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id   INT NOT NULL,
  entreprise_id INT NOT NULL,

  date_entretien DATETIME NOT NULL,
  lieu           VARCHAR(255),
  message        TEXT COMMENT 'Message personnalisé de l\'entreprise',

  statut         ENUM('en_attente','accepte','refuse','sans_reponse')
                   NOT NULL DEFAULT 'en_attente',
  email_envoye   TINYINT(1) NOT NULL DEFAULT 0,

  date_convocation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_reponse     TIMESTAMP NULL,

  FOREIGN KEY (etudiant_id)   REFERENCES etudiants(id)   ON DELETE CASCADE,
  FOREIGN KEY (entreprise_id) REFERENCES entreprises(id) ON DELETE CASCADE,
  INDEX idx_conv_etudiant (etudiant_id),
  INDEX idx_conv_entreprise (entreprise_id),
  INDEX idx_conv_statut (statut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Convocations émises par les entreprises vers les étudiants';

-- =====================================================
-- TABLE : admins  (équipe JUNIA)
-- =====================================================
CREATE TABLE admins (
  id            INT PRIMARY KEY AUTO_INCREMENT,
  email         VARCHAR(150) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  nom           VARCHAR(100) NOT NULL,
  role          ENUM('moderateur','administrateur') NOT NULL DEFAULT 'administrateur',
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  dernier_login TIMESTAMP NULL,

  INDEX idx_admin_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Comptes administrateur JUNIA';

-- =====================================================
-- TABLE : demandes_contact
-- -----------------------------------------------------
-- Formulaire de contact (3.4) : entreprises NON
-- partenaires souhaitant rejoindre la plateforme.
-- =====================================================
CREATE TABLE demandes_contact (
  id            INT PRIMARY KEY AUTO_INCREMENT,
  nom_entreprise VARCHAR(120) NOT NULL,
  nom_contact    VARCHAR(120) NOT NULL,
  email          VARCHAR(150) NOT NULL,
  telephone      VARCHAR(20),
  message        TEXT,
  traite         TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Demande traitée par l\'admin ?',
  date_demande   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX idx_dem_traite (traite)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Demandes de partenariat (entreprises non-partenaires)';

-- =====================================================
-- VUE : statistiques globales (tableau de bord admin)
-- =====================================================
CREATE OR REPLACE VIEW vue_stats AS
SELECT
  (SELECT COUNT(*) FROM etudiants WHERE statut = 'actif')      AS etudiants_actifs,
  (SELECT COUNT(*) FROM entreprises WHERE statut = 'actif')    AS entreprises_actives,
  (SELECT COUNT(*) FROM convocations)                          AS total_convocations,
  (SELECT COUNT(*) FROM convocations WHERE statut = 'accepte') AS convocations_acceptees,
  (SELECT COUNT(*) FROM demandes_contact WHERE traite = 0)     AS demandes_en_attente;

-- =====================================================
-- FIN DU SCHÉMA
-- -----------------------------------------------------
-- Les comptes de test (avec mots de passe hachés) sont
-- insérés par le script PHP de seed : inc/seed.php
-- (exécuté via `docker compose exec web php inc/seed.php`)
-- car le hachage doit être fait par PHP (password_hash).
-- =====================================================
