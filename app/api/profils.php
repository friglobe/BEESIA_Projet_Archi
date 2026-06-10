<?php
/**
 * api/profils.php — Lecture d'un profil + suppression de compte (RGPD).
 *
 *   GET  ?id=N                       → renvoie le profil complet (JSON)
 *   GET  (étudiant connecté, sans id)→ renvoie son propre profil
 *   POST {action:"supprimer_compte"} → l'étudiant supprime son compte + données
 */

declare(strict_types=1);

require_once __DIR__ . '/../inc/profil.php';

// --- Suppression de compte (RGPD) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exiger_connexion('etudiant', estApi: true);
    $data = corps_json();

    if (($data['action'] ?? '') !== 'supprimer_compte') {
        erreur_json('Action inconnue.');
    }

    // ON DELETE CASCADE supprime formations/expériences/compétences/convocations.
    $stmt = db()->prepare('DELETE FROM etudiants WHERE id = ?');
    $stmt->execute([id_utilisateur()]);

    $_SESSION = [];
    session_destroy();

    repondre_json(['success' => true, 'message' => 'Compte supprimé.', 'redirect' => '/index.php']);
}

// --- Listing catalogue filtré (entreprises / admins) ---
if (($_GET['action'] ?? '') === 'liste') {
    exiger_connexion(['entreprise', 'admin'], estApi: true);
    $profils = listerProfils([
        'domaine'    => $_GET['domaine']    ?? '',
        'competence' => $_GET['competence'] ?? '',
        'promo'      => $_GET['promo']      ?? '',
        'q'          => $_GET['q']          ?? '',
    ]);
    repondre_json(['success' => true, 'profils' => $profils, 'total' => count($profils)]);
}

// --- Lecture d'un profil (GET) ---
// Accessible aux utilisateurs connectés (étudiant pour son profil,
// entreprise/admin pour consulter le catalogue).
exiger_connexion([], estApi: true);

$id = isset($_GET['id']) ? (int) $_GET['id'] : id_utilisateur();

// Un étudiant ne peut lire que SON propre profil via cette API.
if (type_utilisateur() === 'etudiant' && $id !== id_utilisateur()) {
    erreur_json('Accès refusé.', 403);
}

$profil = recupererProfilComplet((int) $id);
if (!$profil) {
    erreur_json('Profil introuvable.', 404);
}

repondre_json(['success' => true, 'profil' => $profil]);
