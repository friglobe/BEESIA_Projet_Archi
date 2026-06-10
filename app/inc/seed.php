<?php
/**
 * seed.php — Insère des comptes et données de démonstration.
 *
 * À exécuter UNE fois, dans le conteneur web, après le démarrage :
 *   docker compose exec web php inc/seed.php
 *
 * Idempotent : on vide d'abord les tables (TRUNCATE) pour pouvoir
 * relancer le seed sans doublons. NE PAS exécuter en production.
 *
 * Mots de passe de tous les comptes de test : "password123"
 */

declare(strict_types=1);

require_once __DIR__ . '/db.php';

$pdo = db();
$MDP = password_hash('password123', PASSWORD_BCRYPT);

echo "Seed en cours...\n";

// --- Nettoyage (ordre inverse des dépendances FK) ---
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
foreach (['convocations', 'competences', 'experiences', 'formations',
          'demandes_contact', 'etudiants', 'entreprises', 'admins'] as $t) {
    $pdo->exec("TRUNCATE TABLE $t");
}
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

// =====================================================
// Admin
// =====================================================
$pdo->prepare(
    'INSERT INTO admins (email, password_hash, nom, role)
     VALUES (?, ?, ?, ?)'
)->execute(['admin@junia.com', $MDP, 'Admin JUNIA', 'administrateur']);

// =====================================================
// Entreprises partenaires
// =====================================================
$entreprises = [
    ['contact@techcorp.fr', 'TechCorp Lille', 'Informatique', 'Éditeur de solutions web & cloud.', 'https://techcorp.example', 'Lille'],
    ['rh@datasoft.fr',      'DataSoft',       'Data / IA',    'Spécialiste IA et machine learning.', 'https://datasoft.example', 'Paris'],
    ['jobs@buildit.fr',     'BuildIT',        'BTP',          'Construction et génie civil innovant.', 'https://buildit.example', 'Lyon'],
];
$insEnt = $pdo->prepare(
    'INSERT INTO entreprises (email_contact, password_hash, nom, secteur, description, site_web, adresse)
     VALUES (?, ?, ?, ?, ?, ?, ?)'
);
foreach ($entreprises as $ent) {
    $insEnt->execute([$ent[0], $MDP, $ent[1], $ent[2], $ent[3], $ent[4], $ent[5]]);
}

// =====================================================
// Étudiants (avec CV complet)
// =====================================================
$etudiants = [
    [
        'email' => 'alice.dupont@junia.com', 'nom' => 'Dupont', 'prenom' => 'Alice',
        'ville' => 'Lille', 'promo' => 'ISEN-AP3',
        'bio' => "Étudiante en informatique passionnée par le développement web et les architectures cloud. À la recherche d'une alternance ou d'un stage.",
        'domaines' => ['stage', 'alternance'],
        'formations' => [['JUNIA ISEN', 'Ingénieur informatique', 'Architecture logicielle', 2022, 2025]],
        'experiences' => [['TechCorp', 'Développeur web stagiaire', '2024-06-01', '2024-08-31', 'Développement d\'une API REST en PHP.']],
        'competences' => [['PHP', 'technique', 'avance'], ['JavaScript', 'technique', 'avance'], ['MySQL', 'technique', 'intermediaire'], ['Anglais', 'langue', 'avance']],
    ],
    [
        'email' => 'marc.martin@junia.com', 'nom' => 'Martin', 'prenom' => 'Marc',
        'ville' => 'Paris', 'promo' => 'ISEN-AP3',
        'bio' => "Futur ingénieur orienté DevOps et infrastructure. Curieux, rigoureux, à la recherche d'un CDI.",
        'domaines' => ['cdi'],
        'formations' => [['JUNIA ISEN', 'Ingénieur informatique', 'Systèmes & réseaux', 2021, 2024]],
        'experiences' => [['DataSoft', 'Ingénieur DevOps (alternance)', '2022-09-01', null, 'Mise en place de pipelines CI/CD.']],
        'competences' => [['Docker', 'technique', 'expert'], ['Kubernetes', 'technique', 'avance'], ['Python', 'technique', 'intermediaire'], ['Allemand', 'langue', 'intermediaire']],
    ],
    [
        'email' => 'sofia.benali@junia.com', 'nom' => 'Benali', 'prenom' => 'Sofia',
        'ville' => 'Lyon', 'promo' => 'ISEN-AP3',
        'bio' => "Intéressée par la mobilité internationale et les projets à fort impact. Ouverte stage ou mobilité à l'étranger.",
        'domaines' => ['stage', 'mobilite'],
        'formations' => [['JUNIA ISEN', 'Bachelor informatique', 'Développement mobile', 2023, 2026]],
        'experiences' => [],
        'competences' => [['React', 'technique', 'avance'], ['Node.js', 'technique', 'intermediaire'], ['Espagnol', 'langue', 'expert'], ['Anglais', 'langue', 'avance']],
    ],
];

$insEtu = $pdo->prepare(
    'INSERT INTO etudiants (email, password_hash, nom, prenom, ville, promo, biographie, domaines_recherche, consentement_rgpd)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)'
);
$insForm = $pdo->prepare(
    'INSERT INTO formations (etudiant_id, ecole, diplome, specialisation, annee_debut, annee_fin)
     VALUES (?, ?, ?, ?, ?, ?)'
);
$insExp = $pdo->prepare(
    'INSERT INTO experiences (etudiant_id, entreprise, poste, date_debut, date_fin, description)
     VALUES (?, ?, ?, ?, ?, ?)'
);
$insComp = $pdo->prepare(
    'INSERT INTO competences (etudiant_id, libelle, categorie, niveau)
     VALUES (?, ?, ?, ?)'
);

foreach ($etudiants as $etu) {
    $insEtu->execute([
        $etu['email'], $MDP, $etu['nom'], $etu['prenom'],
        $etu['ville'], $etu['promo'], $etu['bio'],
        json_encode($etu['domaines']),
    ]);
    $id = (int) $pdo->lastInsertId();

    foreach ($etu['formations'] as $f) {
        $insForm->execute([$id, $f[0], $f[1], $f[2], $f[3], $f[4]]);
    }
    foreach ($etu['experiences'] as $x) {
        $insExp->execute([$id, $x[0], $x[1], $x[2], $x[3], $x[4]]);
    }
    foreach ($etu['competences'] as $c) {
        $insComp->execute([$id, $c[0], $c[1], $c[2]]);
    }
}

echo "Seed terminé !\n";
echo "Comptes de test (mot de passe : password123)\n";
echo "  - Admin      : admin@junia.com\n";
echo "  - Entreprise : contact@techcorp.fr\n";
echo "  - Étudiant   : alice.dupont@junia.com\n";
