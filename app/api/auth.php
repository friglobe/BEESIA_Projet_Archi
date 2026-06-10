<?php
/**
 * api/auth.php — Authentification (inscription, connexion, déconnexion).
 *
 * Actions :
 *   POST {action:"register", ...}  → inscription étudiant
 *   POST {action:"login", ...}     → connexion (auto-détection du rôle)
 *   GET  ?action=logout            → déconnexion + redirection accueil
 *
 * Les entreprises et admins ne s'inscrivent pas : leurs comptes sont
 * créés par l'administration (cf. cahier des charges).
 */

declare(strict_types=1);

require_once __DIR__ . '/../inc/db.php';

$action = $_GET['action'] ?? null;

// ----- Déconnexion (lien GET depuis le header) -----
if ($action === 'logout') {
    $_SESSION = [];
    session_destroy();
    header('Location: /index.php');
    exit;
}

// ----- Le reste se fait en POST JSON -----
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    erreur_json('Méthode non autorisée', 405);
}

$data   = corps_json();
$action = $data['action'] ?? '';

switch ($action) {
    case 'register':
        inscrireEtudiant($data);
        break;
    case 'login':
        connecter($data);
        break;
    default:
        erreur_json('Action inconnue');
}

/**
 * Inscription d'un étudiant.
 */
function inscrireEtudiant(array $data): void
{
    $nom      = trim($data['nom'] ?? '');
    $prenom   = trim($data['prenom'] ?? '');
    $email    = trim(strtolower($data['email'] ?? ''));
    $password = $data['password'] ?? '';
    $rgpd     = !empty($data['consentement_rgpd']);

    // --- Validation ---
    if ($nom === '' || $prenom === '' || $email === '' || $password === '') {
        erreur_json('Tous les champs sont obligatoires.');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        erreur_json('Adresse e-mail invalide.');
    }
    if (!str_ends_with($email, '@junia.com')) {
        erreur_json('Vous devez utiliser une adresse @junia.com.');
    }
    if (strlen($password) < 8) {
        erreur_json('Le mot de passe doit contenir au moins 8 caractères.');
    }
    if (!$rgpd) {
        erreur_json('Vous devez accepter la politique de confidentialité (RGPD).');
    }

    $pdo = db();

    // E-mail déjà utilisé ?
    $stmt = $pdo->prepare('SELECT id FROM etudiants WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        erreur_json('Un compte existe déjà avec cette adresse.', 409);
    }

    // --- Création (mot de passe HACHÉ) ---
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare(
        'INSERT INTO etudiants (email, password_hash, nom, prenom, consentement_rgpd)
         VALUES (?, ?, ?, ?, 1)'
    );
    $stmt->execute([$email, $hash, $nom, $prenom]);
    $id = (int) $pdo->lastInsertId();

    // Connexion automatique
    ouvrirSession($id, 'etudiant', $prenom . ' ' . $nom);

    repondre_json([
        'success'  => true,
        'message'  => 'Compte créé avec succès !',
        'user_type' => 'etudiant',
        'redirect' => '/pages/profil.php',
    ], 201);
}

/**
 * Connexion : cherche l'e-mail dans les 3 tables et vérifie le mot de passe.
 */
function connecter(array $data): void
{
    $email    = trim(strtolower($data['email'] ?? ''));
    $password = $data['password'] ?? '';

    if ($email === '' || $password === '') {
        erreur_json('E-mail et mot de passe requis.');
    }

    $pdo = db();

    // Tables candidates : [table, colonne e-mail, type, redirection]
    $cibles = [
        ['etudiants',   'email',         'etudiant',   '/pages/profil.php'],
        ['entreprises', 'email_contact', 'entreprise', '/pages/catalogue.php'],
        ['admins',      'email',         'admin',      '/pages/admin/index.php'],
    ];

    foreach ($cibles as [$table, $col, $type, $redirect]) {
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE $col = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            continue;
        }

        // Compte suspendu ?
        if (($user['statut'] ?? 'actif') === 'suspendu') {
            erreur_json('Ce compte a été suspendu. Contactez l\'administration.', 403);
        }

        if (!password_verify($password, $user['password_hash'])) {
            erreur_json('E-mail ou mot de passe incorrect.', 401);
        }

        // Nom affiché : "prenom nom" pour étudiant, "nom" sinon
        $nomAffiche = isset($user['prenom'])
            ? $user['prenom'] . ' ' . $user['nom']
            : $user['nom'];

        ouvrirSession((int) $user['id'], $type, $nomAffiche);

        repondre_json([
            'success'   => true,
            'message'   => 'Connexion réussie !',
            'user_type' => $type,
            'redirect'  => $redirect,
        ]);
    }

    // Aucun compte trouvé pour cet e-mail
    erreur_json('E-mail ou mot de passe incorrect.', 401);
}

/**
 * Initialise la session utilisateur.
 */
function ouvrirSession(int $id, string $type, string $nom): void
{
    session_regenerate_id(true); // anti fixation de session
    $_SESSION['user_id']   = $id;
    $_SESSION['user_type'] = $type;
    $_SESSION['nom']       = $nom;
}
