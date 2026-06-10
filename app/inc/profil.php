<?php
/**
 * profil.php (inc) — Fonctions de lecture des profils étudiants.
 * Source unique réutilisée par l'API, la page profil et le catalogue.
 */

declare(strict_types=1);

require_once __DIR__ . '/db.php';

/**
 * Récupère un profil étudiant complet (CV) par son id.
 * Renvoie un tableau structuré ou null si introuvable.
 */
function recupererProfilComplet(int $id): ?array
{
    $pdo = db();

    $stmt = $pdo->prepare('SELECT * FROM etudiants WHERE id = ?');
    $stmt->execute([$id]);
    $etu = $stmt->fetch();
    if (!$etu) {
        return null;
    }

    // On n'expose jamais le hash du mot de passe.
    unset($etu['password_hash']);

    // Domaines : JSON -> tableau PHP
    $etu['domaines_recherche'] = json_decode($etu['domaines_recherche'] ?? '[]', true) ?: [];

    // Sous-collections
    $form = $pdo->prepare('SELECT * FROM formations  WHERE etudiant_id = ? ORDER BY annee_fin DESC, id DESC');
    $form->execute([$id]);
    $etu['formations'] = $form->fetchAll();

    $exp = $pdo->prepare('SELECT * FROM experiences WHERE etudiant_id = ? ORDER BY date_debut DESC, id DESC');
    $exp->execute([$id]);
    $etu['experiences'] = $exp->fetchAll();

    $comp = $pdo->prepare('SELECT * FROM competences WHERE etudiant_id = ? ORDER BY categorie, libelle');
    $comp->execute([$id]);
    $etu['competences'] = $comp->fetchAll();

    return $etu;
}

/** Libellés lisibles des domaines de recherche. */
const DOMAINES_LABELS = [
    'stage'      => 'Stage',
    'alternance' => 'Alternance',
    'cdi'        => 'CDI',
    'mobilite'   => 'Mobilité internationale',
];

/**
 * Liste les profils étudiants pour le catalogue, avec filtres optionnels.
 *
 * @param array $filtres { domaine, competence, promo, q }
 * @return array Liste de profils "résumés" (sans détails sensibles).
 */
function listerProfils(array $filtres = []): array
{
    $pdo = db();

    $where  = ["e.statut = 'actif'"];
    $params = [];

    // Domaine de recherche (colonne JSON)
    if (!empty($filtres['domaine'])) {
        $where[]  = 'JSON_CONTAINS(e.domaines_recherche, ?)';
        $params[] = json_encode($filtres['domaine']);
    }

    // École / promotion
    if (!empty($filtres['promo'])) {
        $where[]  = '(e.promo LIKE ? OR e.ecole LIKE ?)';
        $params[] = '%' . $filtres['promo'] . '%';
        $params[] = '%' . $filtres['promo'] . '%';
    }

    // Recherche libre (nom, prénom, ville)
    if (!empty($filtres['q'])) {
        $where[]  = '(e.nom LIKE ? OR e.prenom LIKE ? OR e.ville LIKE ?)';
        $like = '%' . $filtres['q'] . '%';
        array_push($params, $like, $like, $like);
    }

    // Compétence : l'étudiant doit posséder au moins une compétence correspondante
    if (!empty($filtres['competence'])) {
        $where[]  = 'EXISTS (SELECT 1 FROM competences c WHERE c.etudiant_id = e.id AND c.libelle LIKE ?)';
        $params[] = '%' . $filtres['competence'] . '%';
    }

    $sql = 'SELECT e.id, e.nom, e.prenom, e.ville, e.promo, e.ecole, e.photo,
                   e.biographie, e.domaines_recherche,
                   GROUP_CONCAT(DISTINCT c.libelle ORDER BY c.libelle SEPARATOR ", ") AS competences
            FROM etudiants e
            LEFT JOIN competences c ON c.etudiant_id = e.id
            WHERE ' . implode(' AND ', $where) . '
            GROUP BY e.id
            ORDER BY e.date_modification DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $profils = $stmt->fetchAll();

    // Décode les domaines JSON pour chaque profil
    foreach ($profils as &$p) {
        $p['domaines_recherche'] = json_decode($p['domaines_recherche'] ?? '[]', true) ?: [];
    }
    return $profils;
}
