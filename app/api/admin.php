<?php
/**
 * api/admin.php — Actions d'administration (réservé aux admins).
 *
 * POST JSON, champ "action" :
 *   - creer_entreprise : {nom, email, password, secteur, description, site_web}
 *   - changer_statut   : {type:"etudiant"|"entreprise", id, statut:"actif"|"suspendu"}
 *   - supprimer        : {type:"etudiant"|"entreprise", id}
 *   - traiter_demande  : {id}
 */

declare(strict_types=1);

require_once __DIR__ . '/../inc/db.php';

exiger_connexion('admin', estApi: true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    erreur_json('Méthode non autorisée', 405);
}

$data = corps_json();
$pdo  = db();

switch ($data['action'] ?? '') {

    // ----- Création d'un compte entreprise -----
    case 'creer_entreprise':
        $nom      = trim($data['nom'] ?? '');
        $email    = trim(strtolower($data['email'] ?? ''));
        $password = $data['password'] ?? '';

        if ($nom === '' || $email === '' || $password === '') {
            erreur_json('Nom, e-mail et mot de passe requis.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            erreur_json('E-mail invalide.');
        }
        if (strlen($password) < 8) {
            erreur_json('Le mot de passe doit faire au moins 8 caractères.');
        }

        $check = $pdo->prepare('SELECT id FROM entreprises WHERE email_contact = ?');
        $check->execute([$email]);
        if ($check->fetch()) {
            erreur_json('Une entreprise utilise déjà cet e-mail.', 409);
        }

        $stmt = $pdo->prepare(
            'INSERT INTO entreprises (email_contact, password_hash, nom, secteur, description, site_web)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $email,
            password_hash($password, PASSWORD_BCRYPT),
            $nom,
            trim($data['secteur'] ?? '') ?: null,
            trim($data['description'] ?? '') ?: null,
            trim($data['site_web'] ?? '') ?: null,
        ]);
        repondre_json(['success' => true, 'message' => "Compte entreprise « $nom » créé."], 201);

    // ----- Suspendre / réactiver un compte -----
    case 'changer_statut':
        [$table, $id] = cibler($data);
        $statut = $data['statut'] ?? '';
        if (!in_array($statut, ['actif', 'suspendu'], true)) {
            erreur_json('Statut invalide.');
        }
        $pdo->prepare("UPDATE $table SET statut = ? WHERE id = ?")->execute([$statut, $id]);
        repondre_json(['success' => true, 'message' => 'Statut mis à jour.']);

    // ----- Supprimer un compte (+ données liées via CASCADE) -----
    case 'supprimer':
        [$table, $id] = cibler($data);
        $pdo->prepare("DELETE FROM $table WHERE id = ?")->execute([$id]);
        repondre_json(['success' => true, 'message' => 'Compte supprimé.']);

    // ----- Marquer une demande de contact comme traitée -----
    case 'traiter_demande':
        $id = (int) ($data['id'] ?? 0);
        $pdo->prepare('UPDATE demandes_contact SET traite = 1 WHERE id = ?')->execute([$id]);
        repondre_json(['success' => true, 'message' => 'Demande traitée.']);

    default:
        erreur_json('Action inconnue.');
}

/**
 * Valide le couple {type, id} et renvoie [nom_table, id].
 */
function cibler(array $data): array
{
    $tables = ['etudiant' => 'etudiants', 'entreprise' => 'entreprises'];
    $type   = $data['type'] ?? '';
    $id     = (int) ($data['id'] ?? 0);

    if (!isset($tables[$type]) || $id <= 0) {
        erreur_json('Cible invalide.');
    }
    return [$tables[$type], $id];
}
