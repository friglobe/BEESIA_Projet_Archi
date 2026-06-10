<?php
/**
 * api/convoquer.php — Convocations (entreprise → étudiant).
 *
 *   POST {action:"convoquer", etudiant_id, date_entretien, lieu, message}
 *        → crée la convocation + envoie un courriel à l'étudiant
 *   GET  ?action=historique
 *        → liste les convocations émises par l'entreprise connectée
 */

declare(strict_types=1);

require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/mailer.php';

exiger_connexion('entreprise', estApi: true);

$pdo           = db();
$entreprise_id = id_utilisateur();

// --- Historique (GET) ---
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'historique') {
    $stmt = $pdo->prepare(
        'SELECT c.id, c.date_entretien, c.lieu, c.message, c.statut, c.date_convocation,
                e.prenom, e.nom, e.email
         FROM convocations c
         JOIN etudiants e ON e.id = c.etudiant_id
         WHERE c.entreprise_id = ?
         ORDER BY c.date_convocation DESC'
    );
    $stmt->execute([$entreprise_id]);
    repondre_json(['success' => true, 'convocations' => $stmt->fetchAll()]);
}

// --- Création d'une convocation (POST) ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    erreur_json('Méthode non autorisée', 405);
}

$data           = corps_json();
$etudiant_id    = (int) ($data['etudiant_id'] ?? 0);
$date_entretien = trim($data['date_entretien'] ?? '');
$lieu           = trim($data['lieu'] ?? '') ?: null;
$message        = trim($data['message'] ?? '') ?: null;

if ($etudiant_id <= 0 || $date_entretien === '') {
    erreur_json('Étudiant et date d\'entretien requis.');
}

// L'étudiant existe-t-il ?
$stmt = $pdo->prepare('SELECT prenom, nom, email FROM etudiants WHERE id = ? AND statut = "actif"');
$stmt->execute([$etudiant_id]);
$etudiant = $stmt->fetch();
if (!$etudiant) {
    erreur_json('Étudiant introuvable.', 404);
}

// Infos de l'entreprise (pour le courriel)
$stmt = $pdo->prepare('SELECT nom, secteur, email_contact FROM entreprises WHERE id = ?');
$stmt->execute([$entreprise_id]);
$entreprise = $stmt->fetch();

// --- Enregistrement ---
$stmt = $pdo->prepare(
    'INSERT INTO convocations (etudiant_id, entreprise_id, date_entretien, lieu, message, email_envoye)
     VALUES (?, ?, ?, ?, ?, 0)'
);
$stmt->execute([$etudiant_id, $entreprise_id, $date_entretien, $lieu, $message]);
$convocation_id = (int) $pdo->lastInsertId();

// --- Courriel automatique à l'étudiant ---
$sujet = "Invitation à un entretien — {$entreprise['nom']}";
$corps = "Bonjour {$etudiant['prenom']} {$etudiant['nom']},\n\n"
       . "L'entreprise {$entreprise['nom']}"
       . ($entreprise['secteur'] ? " (secteur : {$entreprise['secteur']})" : '')
       . " souhaite vous rencontrer en entretien.\n\n"
       . "Date proposée : " . date('d/m/Y à H:i', strtotime($date_entretien)) . "\n"
       . ($lieu ? "Lieu : $lieu\n" : '')
       . ($message ? "\nMessage de l'entreprise :\n$message\n" : '')
       . "\nContact entreprise : {$entreprise['email_contact']}\n\n"
       . "Cordialement,\nLa plateforme CV JUNIA";

$envoye = envoyerCourriel($etudiant['email'], $sujet, $corps);

if ($envoye) {
    $pdo->prepare('UPDATE convocations SET email_envoye = 1 WHERE id = ?')->execute([$convocation_id]);
}

repondre_json([
    'success'      => true,
    'message'      => "Convocation envoyée à {$etudiant['prenom']} {$etudiant['nom']}.",
    'email_envoye' => $envoye,
], 201);
