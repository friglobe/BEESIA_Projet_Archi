<?php
/**
 * api/enregistrer-cv.php — Enregistre / met à jour le CV de l'étudiant connecté.
 *
 * Reçoit un POST multipart/form-data :
 *   - champs scalaires : nom, prenom, date_naissance, telephone, ville,
 *                        biographie, ecole, promo
 *   - domaines[]       : cases cochées (stage|alternance|cdi|mobilite)
 *   - cv_json          : JSON {formations:[], experiences:[], competences:[]}
 *   - photo            : fichier image (optionnel)
 *
 * Stratégie : "remplacement complet" des sous-collections dans une transaction.
 */

declare(strict_types=1);

require_once __DIR__ . '/../inc/db.php';

header('Content-Type: application/json; charset=utf-8');

exiger_connexion('etudiant', estApi: true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    erreur_json('Méthode non autorisée', 405);
}

$id  = id_utilisateur();
$pdo = db();

// --- Champs scalaires ---
$nom            = trim($_POST['nom'] ?? '');
$prenom         = trim($_POST['prenom'] ?? '');
$date_naissance = ($_POST['date_naissance'] ?? '') ?: null;
$telephone      = trim($_POST['telephone'] ?? '') ?: null;
$ville          = trim($_POST['ville'] ?? '') ?: null;
$biographie     = trim($_POST['biographie'] ?? '') ?: null;
$ecole          = trim($_POST['ecole'] ?? '') ?: 'JUNIA';
$promo          = trim($_POST['promo'] ?? '') ?: null;

if ($nom === '' || $prenom === '') {
    erreur_json('Le nom et le prénom sont obligatoires.');
}

// --- Domaines de recherche (liste blanche) ---
$domainesValides = ['stage', 'alternance', 'cdi', 'mobilite'];
$domaines = array_values(array_intersect($_POST['domaines'] ?? [], $domainesValides));

// --- Sous-collections (JSON) ---
$cv = json_decode($_POST['cv_json'] ?? '[]', true);
$formations   = $cv['formations']   ?? [];
$experiences  = $cv['experiences']  ?? [];
$competences  = $cv['competences']  ?? [];

// --- Photo (optionnelle) ---
$cheminPhoto = traiterPhoto($id);

try {
    $pdo->beginTransaction();

    // 1) Mise à jour de la fiche étudiant
    $sql = 'UPDATE etudiants SET
              nom = ?, prenom = ?, date_naissance = ?, telephone = ?, ville = ?,
              biographie = ?, ecole = ?, promo = ?, domaines_recherche = ?';
    $params = [
        $nom, $prenom, $date_naissance, $telephone, $ville,
        $biographie, $ecole, $promo, json_encode($domaines),
    ];
    if ($cheminPhoto !== null) {
        $sql .= ', photo = ?';
        $params[] = $cheminPhoto;
    }
    $sql .= ' WHERE id = ?';
    $params[] = $id;
    $pdo->prepare($sql)->execute($params);

    // 2) Remplacement des formations
    $pdo->prepare('DELETE FROM formations WHERE etudiant_id = ?')->execute([$id]);
    $insForm = $pdo->prepare(
        'INSERT INTO formations (etudiant_id, ecole, diplome, specialisation, annee_debut, annee_fin, description)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    foreach ($formations as $f) {
        if (trim($f['ecole'] ?? '') === '') continue;
        $insForm->execute([
            $id,
            trim($f['ecole']),
            trim($f['diplome'] ?? '') ?: null,
            trim($f['specialisation'] ?? '') ?: null,
            ctype_digit((string)($f['annee_debut'] ?? '')) ? (int)$f['annee_debut'] : null,
            ctype_digit((string)($f['annee_fin'] ?? ''))   ? (int)$f['annee_fin']   : null,
            trim($f['description'] ?? '') ?: null,
        ]);
    }

    // 3) Remplacement des expériences
    $pdo->prepare('DELETE FROM experiences WHERE etudiant_id = ?')->execute([$id]);
    $insExp = $pdo->prepare(
        'INSERT INTO experiences (etudiant_id, entreprise, poste, date_debut, date_fin, description)
         VALUES (?, ?, ?, ?, ?, ?)'
    );
    foreach ($experiences as $x) {
        if (trim($x['entreprise'] ?? '') === '' || trim($x['poste'] ?? '') === '') continue;
        $insExp->execute([
            $id,
            trim($x['entreprise']),
            trim($x['poste']),
            ($x['date_debut'] ?? '') ?: null,
            ($x['date_fin'] ?? '') ?: null,
            trim($x['description'] ?? '') ?: null,
        ]);
    }

    // 4) Remplacement des compétences
    $pdo->prepare('DELETE FROM competences WHERE etudiant_id = ?')->execute([$id]);
    $insComp = $pdo->prepare(
        'INSERT INTO competences (etudiant_id, libelle, categorie, niveau) VALUES (?, ?, ?, ?)'
    );
    $categoriesOk = ['technique', 'langue', 'soft-skill'];
    $niveauxOk    = ['debutant', 'intermediaire', 'avance', 'expert'];
    foreach ($competences as $c) {
        if (trim($c['libelle'] ?? '') === '') continue;
        $insComp->execute([
            $id,
            trim($c['libelle']),
            in_array($c['categorie'] ?? '', $categoriesOk, true) ? $c['categorie'] : 'technique',
            in_array($c['niveau'] ?? '', $niveauxOk, true) ? $c['niveau'] : 'intermediaire',
        ]);
    }

    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    erreur_json('Erreur lors de l\'enregistrement.', 500);
}

// Met à jour le nom affiché dans la session
$_SESSION['nom'] = $prenom . ' ' . $nom;

repondre_json(['success' => true, 'message' => 'CV enregistré avec succès !']);


/**
 * Valide et déplace la photo uploadée. Renvoie le chemin web ou null.
 */
function traiterPhoto(int $id): ?string
{
    if (empty($_FILES['photo']) || $_FILES['photo']['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $f = $_FILES['photo'];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        erreur_json('Échec de l\'upload de la photo.');
    }
    if ($f['size'] > 2 * 1024 * 1024) {
        erreur_json('La photo ne doit pas dépasser 2 Mo.');
    }

    // Vérifie le vrai type MIME (pas seulement l'extension)
    $mimes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $mime  = mime_content_type($f['tmp_name']);
    if (!isset($mimes[$mime])) {
        erreur_json('Format de photo non supporté (JPEG, PNG ou WebP).');
    }

    $dossier = __DIR__ . '/../uploads';
    if (!is_dir($dossier)) {
        mkdir($dossier, 0775, true);
    }
    $nomFichier = 'etu_' . $id . '_' . uniqid() . '.' . $mimes[$mime];
    $destination = $dossier . '/' . $nomFichier;

    if (!move_uploaded_file($f['tmp_name'], $destination)) {
        erreur_json('Impossible d\'enregistrer la photo.', 500);
    }

    return '/uploads/' . $nomFichier;
}
