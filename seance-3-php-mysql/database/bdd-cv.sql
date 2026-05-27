-- =====================================================
-- Base de données pour la plateforme CV — JUNIA AP3
-- =====================================================
-- Dates: Créé pour la Séance 3 (PHP & MySQL)
-- Scope: Étudiants, Entreprises, Convocations, CV

-- =====================================================
-- TABLE: etudiants
-- =====================================================
-- Contient les informations de base des étudiants
-- et les paramètres de leur recherche d'opportunités

CREATE TABLE etudiants (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  photo VARCHAR(255) COMMENT 'Chemin vers la photo de profil',
  biographie TEXT COMMENT 'Lettre de motivation / À propos de moi',
  domaines_recherche JSON COMMENT 'Domaines: ["stage", "alternance", "cdi", "mobilité"]',
  
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX idx_email (email),
  INDEX idx_date_creation (date_creation)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Table des étudiants inscrits sur la plateforme';

-- =====================================================
-- TABLE: experiences
-- =====================================================
-- Historique des expériences professionnelles

CREATE TABLE experiences (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  
  entreprise VARCHAR(100) NOT NULL,
  poste VARCHAR(100) NOT NULL,
  date_debut DATE,
  date_fin DATE,
  description TEXT,
  
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
  INDEX idx_etudiant_id (etudiant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Expériences professionnelles des étudiants';

-- =====================================================
-- TABLE: formations
-- =====================================================
-- Parcours académique

CREATE TABLE formations (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  
  ecole VARCHAR(100) NOT NULL,
  diplome VARCHAR(100),
  specialisation VARCHAR(100),
  date_fin DATE,
  description TEXT,
  
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
  INDEX idx_etudiant_id (etudiant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Formations et diplômes';

-- =====================================================
-- TABLE: competences
-- =====================================================
-- Compétences techniques et soft skills

CREATE TABLE competences (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  
  competence VARCHAR(100) NOT NULL,
  categorie VARCHAR(50) COMMENT 'technique, langue, soft-skill',
  niveau VARCHAR(20) COMMENT 'débutant, intermédiaire, avancé, expert',
  
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
  INDEX idx_etudiant_id (etudiant_id),
  INDEX idx_competence (competence)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Compétences des étudiants';

-- =====================================================
-- TABLE: entreprises
-- =====================================================
-- Entreprises partenaires ayant accès à la plateforme

CREATE TABLE entreprises (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  email_contact VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  
  logo VARCHAR(255),
  secteur VARCHAR(100),
  description TEXT,
  site_web VARCHAR(255),
  
  adresse VARCHAR(255),
  telephone VARCHAR(20),
  
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  INDEX idx_email_contact (email_contact),
  INDEX idx_secteur (secteur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Entreprises partenaires';

-- =====================================================
-- TABLE: offres
-- =====================================================
-- Offres d'opportunités créées par les entreprises

CREATE TABLE offres (
  id INT PRIMARY KEY AUTO_INCREMENT,
  entreprise_id INT NOT NULL,
  
  titre VARCHAR(100) NOT NULL,
  type_contrat VARCHAR(50) COMMENT 'stage, alternance, cdi, mobilité',
  description TEXT,
  competences_requises JSON,
  
  date_publication TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_expiration DATE,
  statut VARCHAR(20) DEFAULT 'actif' COMMENT 'actif, fermé, archivé',
  
  FOREIGN KEY (entreprise_id) REFERENCES entreprises(id) ON DELETE CASCADE,
  INDEX idx_entreprise_id (entreprise_id),
  INDEX idx_type_contrat (type_contrat),
  INDEX idx_statut (statut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Offres d\'opportunités';

-- =====================================================
-- TABLE: convocations
-- =====================================================
-- Lorsqu'une entreprise convoque un étudiant

CREATE TABLE convocations (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  entreprise_id INT NOT NULL,
  offre_id INT,
  
  type_contrat VARCHAR(50),
  message TEXT COMMENT 'Message de convocation personnalisé',
  
  statut VARCHAR(20) DEFAULT 'en attente' 
    COMMENT 'en attente, accepté, refusé, sans réponse',
  
  date_convocation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_reponse TIMESTAMP NULL,
  
  email_envoye TINYINT DEFAULT 1 COMMENT 'Email de notification envoyé?',
  
  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
  FOREIGN KEY (entreprise_id) REFERENCES entreprises(id) ON DELETE CASCADE,
  FOREIGN KEY (offre_id) REFERENCES offres(id) ON DELETE SET NULL,
  
  INDEX idx_etudiant_id (etudiant_id),
  INDEX idx_entreprise_id (entreprise_id),
  INDEX idx_statut (statut),
  INDEX idx_date_convocation (date_convocation)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Convocations émises par les entreprises vers les étudiants';

-- =====================================================
-- TABLE: candidatures
-- =====================================================
-- Candidatures des étudiants à des offres

CREATE TABLE candidatures (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  offre_id INT NOT NULL,
  
  message_motivation TEXT,
  statut VARCHAR(20) DEFAULT 'en attente'
    COMMENT 'en attente, accepté, refusé',
  
  date_candidature TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_reponse TIMESTAMP NULL,
  
  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
  FOREIGN KEY (offre_id) REFERENCES offres(id) ON DELETE CASCADE,
  
  INDEX idx_etudiant_id (etudiant_id),
  INDEX idx_offre_id (offre_id),
  INDEX idx_statut (statut),
  
  UNIQUE KEY unique_candidature (etudiant_id, offre_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Candidatures des étudiants aux offres';

-- =====================================================
-- TABLE: admin_users
-- =====================================================
-- Comptes administrateur pour modérer la plateforme

CREATE TABLE admin_users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  
  role VARCHAR(50) DEFAULT 'modérateur'
    COMMENT 'modérateur, administrateur',
  
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  dernier_login TIMESTAMP NULL,
  
  INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Comptes administrateur';

-- =====================================================
-- TABLE: logs
-- =====================================================
-- Trace d'audit pour la sécurité et le débogage

CREATE TABLE logs (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  user_type VARCHAR(20) COMMENT 'student, company, admin',
  
  action VARCHAR(100),
  ressource VARCHAR(100),
  details JSON,
  
  ip_address VARCHAR(45),
  user_agent TEXT,
  
  date_action TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  INDEX idx_user_id (user_id),
  INDEX idx_date_action (date_action),
  INDEX idx_action (action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Logs d\'audit pour traçabilité et sécurité';

-- =====================================================
-- TABLE: messages
-- =====================================================
-- Système de messagerie interne

CREATE TABLE messages (
  id INT PRIMARY KEY AUTO_INCREMENT,
  
  emetteur_id INT NOT NULL,
  emetteur_type VARCHAR(20) COMMENT 'student, company, admin',
  recepteur_id INT NOT NULL,
  recepteur_type VARCHAR(20),
  
  sujet VARCHAR(200),
  contenu TEXT,
  
  lu TINYINT DEFAULT 0,
  date_envoi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_lecture TIMESTAMP NULL,
  
  INDEX idx_emetteur (emetteur_id),
  INDEX idx_recepteur (recepteur_id),
  INDEX idx_lu (lu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Messagerie interne de la plateforme';

-- =====================================================
-- DONNÉES DE TEST
-- =====================================================

-- Étudiant test (password: password123)
INSERT INTO etudiants (nom, email, password_hash, biographie, domaines_recherche) 
VALUES (
  'Alice Dupont',
  'alice@junia.fr',
  '$2y$10$N9qo8uLOickgx2ZMRZoMyu1n5Xn8F4x2nVWJ6XzA9.1.2C0yO5UoS',
  'Je suis une étudiante GEC motivée à la recherche d\'un stage ou d\'une alternance dans le domaine de l\'informatique.',
  '["stage", "alternance"]'
);

-- Entreprise test (password: company123)
INSERT INTO entreprises (nom, email_contact, password_hash, secteur, description)
VALUES (
  'TechCorp Lille',
  'contact@techcorp.fr',
  '$2y$10$N9qo8uLOickgx2ZMRZoMyu1n5Xn8F4x2nVWJ6XzA9.1.2C0yO5UoS',
  'Informatique',
  'Entreprise innovante spécialisée dans les solutions web et cloud.'
);

-- Admin test (password: admin123)
INSERT INTO admin_users (nom, email, password_hash, role)
VALUES (
  'Admin JUNIA',
  'admin@junia.fr',
  '$2y$10$N9qo8uLOickgx2ZMRZoMyu1n5Xn8F4x2nVWJ6XzA9.1.2C0yO5UoS',
  'administrateur'
);

-- =====================================================
-- VUE: vue_stats_etudiants
-- =====================================================
-- Statiques rapides sur les étudiants

CREATE VIEW vue_stats_etudiants AS
SELECT 
  COUNT(DISTINCT e.id) as total_etudiants,
  COUNT(DISTINCT c.id) as total_convocations,
  COUNT(DISTINCT CASE WHEN c.statut = 'accepté' THEN c.id END) as convocations_acceptees
FROM etudiants e
LEFT JOIN convocations c ON e.id = c.etudiant_id;

-- =====================================================
-- VUE: vue_stats_entreprises
-- =====================================================

CREATE VIEW vue_stats_entreprises AS
SELECT 
  ent.id,
  ent.nom,
  COUNT(DISTINCT o.id) as total_offres,
  COUNT(DISTINCT c.id) as total_convocations,
  COUNT(DISTINCT ca.id) as total_candidatures
FROM entreprises ent
LEFT JOIN offres o ON ent.id = o.entreprise_id AND o.statut = 'actif'
LEFT JOIN convocations c ON ent.id = c.entreprise_id
LEFT JOIN candidatures ca ON o.id = ca.offre_id
GROUP BY ent.id;

-- =====================================================
-- FIN DU SCRIPT
-- =====================================================
-- Nombre de tables créées: 12
-- Vues créées: 2
-- Indexes créés: 25+
--
-- Pour charger ce script dans phpMyAdmin:
-- 1. Créer la base "cv_platform"
-- 2. Aller à l'onglet SQL
-- 3. Copier/coller ce fichier
-- 4. Cliquer "Exécuter"
-- =====================================================
