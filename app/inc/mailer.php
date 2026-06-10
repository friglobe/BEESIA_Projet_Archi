<?php
/**
 * mailer.php — Envoi de courriels.
 *
 * En environnement de cours (pas de serveur SMTP), on écrit le courriel
 * dans un fichier .txt sous /mails pour pouvoir le montrer en démonstration.
 * Un vrai envoi SMTP (PHPMailer) est une fonctionnalité bonus.
 */

declare(strict_types=1);

/**
 * "Envoie" un courriel. Renvoie true si l'opération a réussi
 * (envoi réel OU écriture du fichier de démonstration).
 */
function envoyerCourriel(string $destinataire, string $sujet, string $corps): bool
{
    $entetes = "From: no-reply@junia.com\r\n"
             . "Content-Type: text/plain; charset=utf-8\r\n";

    // Tentative d'envoi réel (échoue silencieusement sans MTA configuré).
    $envoye = @mail($destinataire, $sujet, $corps, $entetes);

    // Trace de démonstration : un fichier par courriel.
    $dossier = __DIR__ . '/../mails';
    if (!is_dir($dossier)) {
        mkdir($dossier, 0775, true);
    }
    $nom = $dossier . '/' . date('Ymd_His') . '_' . preg_replace('/[^a-z0-9]/i', '_', $destinataire) . '.txt';
    $contenu = "À      : $destinataire\n"
             . "Objet  : $sujet\n"
             . "Date   : " . date('d/m/Y H:i') . "\n"
             . str_repeat('-', 50) . "\n"
             . $corps . "\n";
    file_put_contents($nom, $contenu);

    return $envoye || file_exists($nom);
}
