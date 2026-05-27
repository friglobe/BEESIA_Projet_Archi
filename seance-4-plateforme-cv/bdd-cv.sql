-- =====================================================
-- SEANCE 4 - Base de données Plateforme CV
-- =====================================================

CREATE DATABASE IF NOT EXISTS cv_platform;
USE cv_platform;

-- =====================================================
-- Table Etudiants
-- =====================================================
CREATE TABLE IF NOT EXISTS etudiants (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  prenom VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  formation TEXT,
  experience TEXT,
  competences TEXT,
  photo VARCHAR(255),
  domaines_recherche VARCHAR(255),
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- Table Entreprises
-- =====================================================
CREATE TABLE IF NOT EXISTS entreprises (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  secteur VARCHAR(100),
  logo VARCHAR(255),
  description TEXT,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- Table Convocations (entretiens)
-- =====================================================
CREATE TABLE IF NOT EXISTS convocations (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  entreprise_id INT NOT NULL,
  date_convocation DATE,
  lieu VARCHAR(255),
  description TEXT,
  status ENUM('pending', 'accepted', 'refused', 'completed') DEFAULT 'pending',
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
  FOREIGN KEY (entreprise_id) REFERENCES entreprises(id) ON DELETE CASCADE
);

-- =====================================================
-- Table Candidatures
-- =====================================================
CREATE TABLE IF NOT EXISTS candidatures (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  offre_id INT,
  type_contrat ENUM('stage', 'alternance', 'cdi', 'mobilite') NOT NULL,
  date_candidature TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status ENUM('en_attente', 'accepte', 'refuse') DEFAULT 'en_attente',
  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE
);

-- =====================================================
-- Données de test
-- =====================================================

-- Insérer des étudiants test
INSERT INTO etudiants (nom, prenom, email, password, formation, experience, competences, domaines_recherche) VALUES
('Dupont', 'Jean', 'jean.dupont@isen.fr', '$2y$10$DummyHashedPassword1', 'Master Informatique ISEN', '6 mois stage chez TechCorp', 'PHP, MySQL, JavaScript, Docker', 'stage,alternance'),
('Martin', 'Marie', 'marie.martin@isen.fr', '$2y$10$DummyHashedPassword2', 'Bachelor Informatique ISEN', '1 an en alternance', 'React, Node.js, MongoDB', 'cdi,alternance'),
('Bernard', 'Pierre', 'pierre.bernard@isen.fr', '$2y$10$DummyHashedPassword3', 'Ingénieur Informatique ISEN', '2 ans expérience DevOps', 'Kubernetes, Docker, AWS, Python', 'cdi');

-- Insérer des entreprises test
INSERT INTO entreprises (nom, email, password, secteur, description) VALUES
('TechCorp', 'hr@techcorp.fr', '$2y$10$DummyHashedPassword4', 'Technologie', 'Startup innovante en solutions cloud'),
('DataSoft', 'contact@datasoft.fr', '$2y$10$DummyHashedPassword5', 'Data Science', 'Spécialiste en IA et machine learning'),
('WebDev Solutions', 'jobs@webdevsolutions.fr', '$2y$10$DummyHashedPassword6', 'Web', 'Agence de développement web et mobile');

-- Insérer des candidatures test
INSERT INTO candidatures (etudiant_id, type_contrat, status) VALUES
(1, 'stage', 'en_attente'),
(1, 'alternance', 'accepte'),
(2, 'cdi', 'en_attente'),
(3, 'cdi', 'accepte');

-- =====================================================
-- Vues utiles
-- =====================================================

-- Vue: Profils avec nombre de convocations
CREATE OR REPLACE VIEW v_profils_convocations AS
SELECT 
  e.id,
  CONCAT(e.prenom, ' ', e.nom) as nom_complet,
  e.email,
  e.competences,
  e.domaines_recherche,
  COUNT(c.id) as nb_convocations
FROM etudiants e
LEFT JOIN convocations c ON e.id = c.etudiant_id
GROUP BY e.id;

-- Vue: Offres par entreprise
CREATE OR REPLACE VIEW v_candidatures_par_entreprise AS
SELECT 
  ent.id,
  ent.nom as nom_entreprise,
  COUNT(cand.id) as nb_candidatures,
  SUM(CASE WHEN cand.status = 'accepte' THEN 1 ELSE 0 END) as acceptes
FROM entreprises ent
LEFT JOIN candidatures cand ON cand.offre_id = ent.id
GROUP BY ent.id;

-- =====================================================
-- Index pour performances
-- =====================================================
CREATE INDEX idx_etudiants_email ON etudiants(email);
CREATE INDEX idx_etudiants_domaines ON etudiants(domaines_recherche);
CREATE INDEX idx_convocations_etudiant ON convocations(etudiant_id);
CREATE INDEX idx_convocations_entreprise ON convocations(entreprise_id);
CREATE INDEX idx_candidatures_etudiant ON candidatures(etudiant_id);

-- =====================================================
-- Permissions (optionnel)
-- =====================================================
GRANT ALL PRIVILEGES ON cv_platform.* TO 'cv_user'@'%' IDENTIFIED BY 'password123';
FLUSH PRIVILEGES;
