<?php
/**
 * cv.php — Consultation du CV complet d'un étudiant (lecture seule, mis en forme).
 *
 *   ?id=N  → affiche le CV de l'étudiant N
 *   (étudiant connecté sans id) → affiche son propre CV
 *
 * Accès : étudiant (son CV uniquement), entreprise et admin (n'importe quel CV).
 */
require_once __DIR__ . '/../inc/profil.php';

exiger_connexion();

$id = isset($_GET['id']) ? (int) $_GET['id'] : id_utilisateur();

// Un étudiant ne peut consulter que son propre CV.
if (type_utilisateur() === 'etudiant' && $id !== id_utilisateur()) {
    http_response_code(403);
    exit('Accès refusé.');
}

$cv = recupererProfilComplet($id);
if (!$cv) {
    http_response_code(404);
    exit('CV introuvable.');
}

$estProprietaire = (type_utilisateur() === 'etudiant' && $id === id_utilisateur());

// Regroupe les compétences par catégorie pour l'affichage
$competencesParCategorie = [];
foreach ($cv['competences'] as $c) {
    $competencesParCategorie[$c['categorie']][] = $c;
}
$categoriesLabels = ['technique' => 'Compétences techniques', 'langue' => 'Langues', 'soft-skill' => 'Soft skills'];
$niveauxLabels    = ['debutant' => 'Débutant', 'intermediaire' => 'Intermédiaire', 'avance' => 'Avancé', 'expert' => 'Expert'];

$titrePage = 'CV — ' . $cv['prenom'] . ' ' . $cv['nom'];
require __DIR__ . '/../inc/header.php';
?>

<!-- Barre d'actions (non imprimée) -->
<div class="cv-actions no-print">
  <?php if (type_utilisateur() === 'entreprise'): ?>
    <a href="/pages/catalogue.php" class="btn btn-ghost btn-sm">← Retour au catalogue</a>
  <?php elseif ($estProprietaire): ?>
    <a href="/pages/profil.php" class="btn btn-ghost btn-sm">✏️ Modifier mon CV</a>
  <?php endif; ?>
  <button onclick="window.print()" class="btn btn-sm">🖨️ Imprimer / PDF</button>
</div>

<article class="cv-sheet card">

  <!-- ===== En-tête du CV ===== -->
  <header class="cv-header">
    <?php if (!empty($cv['photo'])): ?>
      <img src="<?= e($cv['photo']) ?>" alt="Photo" class="cv-photo">
    <?php else: ?>
      <div class="cv-photo cv-photo-placeholder">
        <?= e(mb_substr($cv['prenom'], 0, 1) . mb_substr($cv['nom'], 0, 1)) ?>
      </div>
    <?php endif; ?>

    <div class="cv-identite">
      <h1><?= e($cv['prenom'] . ' ' . $cv['nom']) ?></h1>
      <p class="cv-sous-titre">
        <?= e($cv['promo'] ?: '') ?><?= $cv['ecole'] ? ' — ' . e($cv['ecole']) : '' ?>
      </p>
      <ul class="cv-coordonnees">
        <li>✉️ <?= e($cv['email']) ?></li>
        <?php if ($cv['telephone']): ?><li>📞 <?= e($cv['telephone']) ?></li><?php endif; ?>
        <?php if ($cv['ville']): ?><li>📍 <?= e($cv['ville']) ?></li><?php endif; ?>
        <?php if ($cv['date_naissance']): ?>
          <li>🎂 <?= e(date('d/m/Y', strtotime($cv['date_naissance']))) ?></li>
        <?php endif; ?>
      </ul>
      <div class="cv-domaines">
        <?php foreach ($cv['domaines_recherche'] as $d): ?>
          <span class="badge badge-orange"><?= e(DOMAINES_LABELS[$d] ?? $d) ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </header>

  <!-- ===== À propos ===== -->
  <?php if ($cv['biographie']): ?>
    <section class="cv-section">
      <h2>À propos</h2>
      <p><?= nl2br(e($cv['biographie'])) ?></p>
    </section>
  <?php endif; ?>

  <!-- ===== Expériences ===== -->
  <?php if ($cv['experiences']): ?>
    <section class="cv-section">
      <h2>Expériences professionnelles</h2>
      <?php foreach ($cv['experiences'] as $x): ?>
        <div class="cv-item">
          <div class="cv-item-tete">
            <strong><?= e($x['poste']) ?></strong> — <?= e($x['entreprise']) ?>
            <span class="cv-dates">
              <?= $x['date_debut'] ? e(date('m/Y', strtotime($x['date_debut']))) : '' ?>
              <?= $x['date_debut'] ? '→ ' . ($x['date_fin'] ? e(date('m/Y', strtotime($x['date_fin']))) : 'en cours') : '' ?>
            </span>
          </div>
          <?php if ($x['description']): ?><p><?= nl2br(e($x['description'])) ?></p><?php endif; ?>
        </div>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>

  <!-- ===== Formations ===== -->
  <?php if ($cv['formations']): ?>
    <section class="cv-section">
      <h2>Parcours académique</h2>
      <?php foreach ($cv['formations'] as $f): ?>
        <div class="cv-item">
          <div class="cv-item-tete">
            <strong><?= e($f['diplome'] ?: 'Formation') ?></strong> — <?= e($f['ecole']) ?>
            <span class="cv-dates">
              <?= e(trim(($f['annee_debut'] ?: '') . ($f['annee_fin'] ? ' → ' . $f['annee_fin'] : ''))) ?>
            </span>
          </div>
          <?php if ($f['specialisation']): ?><p><em><?= e($f['specialisation']) ?></em></p><?php endif; ?>
          <?php if ($f['description']): ?><p><?= nl2br(e($f['description'])) ?></p><?php endif; ?>
        </div>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>

  <!-- ===== Compétences ===== -->
  <?php if ($competencesParCategorie): ?>
    <section class="cv-section">
      <h2>Compétences</h2>
      <?php foreach ($competencesParCategorie as $categorie => $liste): ?>
        <div class="cv-competences-groupe">
          <h3><?= e($categoriesLabels[$categorie] ?? $categorie) ?></h3>
          <div class="cv-competences">
            <?php foreach ($liste as $c): ?>
              <span class="badge" title="<?= e($niveauxLabels[$c['niveau']] ?? '') ?>">
                <?= e($c['libelle']) ?>
                <small>· <?= e($niveauxLabels[$c['niveau']] ?? $c['niveau']) ?></small>
              </span>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </section>
  <?php endif; ?>

  <?php if (!$cv['biographie'] && !$cv['experiences'] && !$cv['formations'] && !$competencesParCategorie): ?>
    <p class="profil-meta">Ce CV est encore vide.<?= $estProprietaire ? ' <a href="/pages/profil.php">Complétez-le</a>.' : '' ?></p>
  <?php endif; ?>

</article>

<?php require __DIR__ . '/../inc/footer.php'; ?>
