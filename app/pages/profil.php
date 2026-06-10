<?php
/**
 * profil.php — Consultation et modification du CV de l'étudiant connecté.
 */
require_once __DIR__ . '/../inc/profil.php';

exiger_connexion('etudiant');

$profil = recupererProfilComplet(id_utilisateur());
if (!$profil) {
    http_response_code(404);
    exit('Profil introuvable.');
}

$titrePage = 'Mon profil';
require __DIR__ . '/../inc/header.php';
?>

<section class="card form-large">
  <h1>Mon CV</h1>
  <p class="profil-meta">Complétez votre profil : il sera visible par les entreprises partenaires dans le catalogue.</p>

  <div id="message" class="hidden"></div>

  <form id="form-cv" enctype="multipart/form-data" novalidate>

    <!-- ===== Identité ===== -->
    <h2>Informations personnelles</h2>

    <div class="field-row">
      <div class="field">
        <label for="prenom">Prénom *</label>
        <input type="text" id="prenom" name="prenom" required value="<?= e($profil['prenom']) ?>">
      </div>
      <div class="field">
        <label for="nom">Nom *</label>
        <input type="text" id="nom" name="nom" required value="<?= e($profil['nom']) ?>">
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label for="date_naissance">Date de naissance</label>
        <input type="date" id="date_naissance" name="date_naissance" value="<?= e($profil['date_naissance']) ?>">
      </div>
      <div class="field">
        <label for="telephone">Téléphone</label>
        <input type="tel" id="telephone" name="telephone" value="<?= e($profil['telephone']) ?>">
      </div>
      <div class="field">
        <label for="ville">Ville</label>
        <input type="text" id="ville" name="ville" value="<?= e($profil['ville']) ?>">
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label for="ecole">École</label>
        <input type="text" id="ecole" name="ecole" value="<?= e($profil['ecole'] ?: 'JUNIA') ?>">
      </div>
      <div class="field">
        <label for="promo">Promotion</label>
        <input type="text" id="promo" name="promo" placeholder="ISEN-AP3" value="<?= e($profil['promo']) ?>">
      </div>
    </div>

    <div class="field">
      <label for="photo">Photo de profil (JPEG/PNG/WebP, max 2 Mo)</label>
      <?php if (!empty($profil['photo'])): ?>
        <img id="photo-preview" src="<?= e($profil['photo']) ?>" alt="Photo" class="profil-photo">
      <?php else: ?>
        <img id="photo-preview" src="" alt="" class="profil-photo hidden">
      <?php endif; ?>
      <input type="file" id="photo" name="photo" accept="image/png,image/jpeg,image/webp">
    </div>

    <!-- ===== Biographie ===== -->
    <h2>À propos / Lettre de motivation</h2>
    <div class="field">
      <textarea id="biographie" name="biographie" placeholder="Présentez-vous, vos objectifs..."><?= e($profil['biographie']) ?></textarea>
    </div>

    <!-- ===== Domaines de recherche ===== -->
    <h2>Domaines de recherche</h2>
    <div class="field checkbox-group">
      <?php foreach (DOMAINES_LABELS as $cle => $libelle): ?>
        <label>
          <input type="checkbox" name="domaines[]" value="<?= $cle ?>"
            <?= in_array($cle, $profil['domaines_recherche'], true) ? 'checked' : '' ?>>
          <?= $libelle ?>
        </label>
      <?php endforeach; ?>
    </div>

    <!-- ===== Formations ===== -->
    <h2>Parcours académique</h2>
    <div id="formations"></div>
    <button type="button" class="btn btn-ghost btn-sm" data-add="formation">+ Ajouter une formation</button>

    <!-- ===== Expériences ===== -->
    <h2>Expériences professionnelles</h2>
    <div id="experiences"></div>
    <button type="button" class="btn btn-ghost btn-sm" data-add="experience">+ Ajouter une expérience</button>

    <!-- ===== Compétences ===== -->
    <h2>Compétences</h2>
    <div id="competences"></div>
    <button type="button" class="btn btn-ghost btn-sm" data-add="competence">+ Ajouter une compétence</button>

    <div style="margin-top:2rem">
      <button type="submit" class="btn btn-orange">💾 Enregistrer mon CV</button>
    </div>
  </form>
</section>

<!-- ===== RGPD : suppression de compte ===== -->
<section class="card" style="margin-top:2rem;border:1px solid #f4c2cb">
  <h2 style="color:var(--erreur)">Supprimer mon compte (RGPD)</h2>
  <p>Cette action supprime <strong>définitivement</strong> votre compte et toutes vos données
     (CV, formations, expériences, compétences, convocations). Irréversible.</p>
  <button id="btn-supprimer" class="btn btn-danger">Supprimer mon compte</button>
</section>

<!-- Données existantes injectées pour le JS -->
<script>
  window.PROFIL = {
    formations:  <?= json_encode($profil['formations']) ?>,
    experiences: <?= json_encode($profil['experiences']) ?>,
    competences: <?= json_encode($profil['competences']) ?>
  };
</script>
<script src="/js/form-cv.js"></script>

<?php require __DIR__ . '/../inc/footer.php'; ?>
