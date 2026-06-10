<?php
/**
 * api/contact.php — Réception des demandes de partenariat (formulaire public).
 *
 * POST {nom_entreprise, nom_contact, email, telephone, message}
 *   → enregistre la demande + notifie l'administration par courriel.
 */

declare(strict_types=1);

require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    erreur_json('Méthode non autorisée', 405);
}

$data          = corps_json();
$nomEntreprise = trim($data['nom_entreprise'] ?? '');
$nomContact    = trim($data['nom_contact'] ?? '');
$email         = trim($data['email'] ?? '');
$telephone     = trim($data['telephone'] ?? '') ?: null;
$message       = trim($data['message'] ?? '') ?: null;

if ($nomEntreprise === '' || $nomContact === '' || $email === '') {
    erreur_json('Entreprise, contact et e-mail sont obligatoires.');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    erreur_json('Adresse e-mail invalide.');
}

$stmt = db()->prepare(
    'INSERT INTO demandes_contact (nom_entreprise, nom_contact, email, telephone, message)
     VALUES (?, ?, ?, ?, ?)'
);
$stmt->execute([$nomEntreprise, $nomContact, $email, $telephone, $message]);

// Notification à l'administration
envoyerCourriel(
    'admin@junia.com',
    "Nouvelle demande de partenariat — $nomEntreprise",
    "Entreprise : $nomEntreprise\nContact : $nomContact\nE-mail : $email\n"
    . ($telephone ? "Téléphone : $telephone\n" : '')
    . ($message ? "\nMessage :\n$message\n" : '')
);

repondre_json([
    'success' => true,
    'message' => 'Votre demande a bien été envoyée. L\'équipe JUNIA vous recontactera.',
], 201);
