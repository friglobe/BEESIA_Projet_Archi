<?php
/**
 * admin/index.php — Tableau de bord d'administration JUNIA.
 */
require_once __DIR__ . '/../../inc/db.php';

exiger_connexion('admin');

$pdo = db();
$stats       = $pdo->query('SELECT * FROM vue_stats')->fetch();
$etudiants   = $pdo->query('SELECT id, prenom, nom, email, statut, date_creation FROM etudiants ORDER BY date_creation DESC')->fetchAll();
$entreprises = $pdo->query('SELECT id, nom, email_contact, secteur, statut FROM entreprises ORDER BY nom')->fetchAll();
$demandes    = $pdo->query('SELECT * FROM demandes_contact ORDER BY traite ASC, date_demande DESC')->fetchAll();

$titrePage = 'Administration';
require __DIR__ . '/../../inc/header.php';
?>

<h1>Tableau de bord — Administration</h1>
<div id="message" class="hidden"></div>

<!-- ===== Statistiques ===== -->
<div class="grid">
  <div class="card"><h3><?= (int) $stats['etudiants_actifs'] ?></h3><p>Étudiants actifs</p></div>
  <div class="card"><h3><?= (int) $stats['entreprises_actives'] ?></h3><p>Entreprises actives</p></div>
  <div class="card"><h3><?= (int) $stats['total_convocations'] ?></h3><p>Convocations</p></div>
  <div class="card"><h3><?= (int) $stats['convocations_acceptees'] ?></h3><p>Convoc. acceptées</p></div>
  <div class="card"><h3><?= (int) $stats['demandes_en_attente'] ?></h3><p>Demandes en attente</p></div>
</div>

<!-- ===== Créer un compte entreprise ===== -->
<section class="card" style="margin-top:2rem">
  <h2>Créer un compte entreprise</h2>
  <form id="form-entreprise">
    <div class="field-row">
      <div class="field"><label>Nom *</label><input id="ent-nom" required></div>
      <div class="field"><label>E-mail *</label><input id="ent-email" type="email" required></div>
    </div>
    <div class="field-row">
      <div class="field"><label>Mot de passe *</label><input id="ent-password" type="text" required minlength="8" placeholder="min. 8 caractères"></div>
      <div class="field"><label>Secteur</label><input id="ent-secteur"></div>
    </div>
    <div class="field"><label>Description</label><textarea id="ent-description"></textarea></div>
    <button type="submit" class="btn btn-orange">Créer le compte</button>
  </form>
</section>

<!-- ===== Étudiants ===== -->
<h2 class="section-titre">Comptes étudiants</h2>
<table class="data">
  <thead><tr><th>Nom</th><th>E-mail</th><th>Statut</th><th>Inscrit le</th><th>Actions</th></tr></thead>
  <tbody>
    <?php foreach ($etudiants as $e): ?>
      <tr>
        <td><?= e($e['prenom'] . ' ' . $e['nom']) ?></td>
        <td><?= e($e['email']) ?></td>
        <td><span class="badge <?= $e['statut'] === 'suspendu' ? 'badge-orange' : '' ?>"><?= e($e['statut']) ?></span></td>
        <td><?= e(date('d/m/Y', strtotime($e['date_creation']))) ?></td>
        <td>
          <?php if ($e['statut'] === 'actif'): ?>
            <button class="btn btn-sm btn-ghost" data-statut="suspendu" data-type="etudiant" data-id="<?= $e['id'] ?>">Suspendre</button>
          <?php else: ?>
            <button class="btn btn-sm" data-statut="actif" data-type="etudiant" data-id="<?= $e['id'] ?>">Réactiver</button>
          <?php endif; ?>
          <button class="btn btn-sm btn-danger" data-supprimer data-type="etudiant" data-id="<?= $e['id'] ?>">Supprimer</button>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- ===== Entreprises ===== -->
<h2 class="section-titre">Comptes entreprises</h2>
<table class="data">
  <thead><tr><th>Nom</th><th>E-mail</th><th>Secteur</th><th>Statut</th><th>Actions</th></tr></thead>
  <tbody>
    <?php foreach ($entreprises as $ent): ?>
      <tr>
        <td><?= e($ent['nom']) ?></td>
        <td><?= e($ent['email_contact']) ?></td>
        <td><?= e($ent['secteur'] ?: '—') ?></td>
        <td><span class="badge <?= $ent['statut'] === 'suspendu' ? 'badge-orange' : '' ?>"><?= e($ent['statut']) ?></span></td>
        <td>
          <?php if ($ent['statut'] === 'actif'): ?>
            <button class="btn btn-sm btn-ghost" data-statut="suspendu" data-type="entreprise" data-id="<?= $ent['id'] ?>">Suspendre</button>
          <?php else: ?>
            <button class="btn btn-sm" data-statut="actif" data-type="entreprise" data-id="<?= $ent['id'] ?>">Réactiver</button>
          <?php endif; ?>
          <button class="btn btn-sm btn-danger" data-supprimer data-type="entreprise" data-id="<?= $ent['id'] ?>">Supprimer</button>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- ===== Demandes de partenariat ===== -->
<h2 class="section-titre">Demandes de partenariat</h2>
<?php if (!$demandes): ?>
  <p>Aucune demande pour le moment.</p>
<?php else: ?>
  <table class="data">
    <thead><tr><th>Entreprise</th><th>Contact</th><th>E-mail</th><th>Message</th><th>Statut</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($demandes as $d): ?>
        <tr>
          <td><?= e($d['nom_entreprise']) ?></td>
          <td><?= e($d['nom_contact']) ?></td>
          <td><?= e($d['email']) ?></td>
          <td><?= e($d['message'] ?: '—') ?></td>
          <td><?= $d['traite'] ? '<span class="badge">Traitée</span>' : '<span class="badge badge-orange">En attente</span>' ?></td>
          <td><?php if (!$d['traite']): ?>
            <button class="btn btn-sm" data-traiter data-id="<?= $d['id'] ?>">Marquer traitée</button>
          <?php endif; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<script>
function notifier(texte, ok) {
  const z = document.getElementById("message");
  z.textContent = texte;
  z.className = "alert " + (ok ? "alert-ok" : "alert-error");
  z.classList.remove("hidden");
  window.scrollTo({ top: 0, behavior: "smooth" });
}

async function appelerAdmin(payload) {
  const rep = await fetch("/api/admin.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload),
  });
  return { ok: rep.ok, body: await rep.json() };
}

// Création entreprise
document.getElementById("form-entreprise").addEventListener("submit", async (e) => {
  e.preventDefault();
  const { ok, body } = await appelerAdmin({
    action: "creer_entreprise",
    nom: document.getElementById("ent-nom").value.trim(),
    email: document.getElementById("ent-email").value.trim(),
    password: document.getElementById("ent-password").value,
    secteur: document.getElementById("ent-secteur").value.trim(),
    description: document.getElementById("ent-description").value.trim(),
  });
  notifier(ok ? body.message : body.error, ok && body.success);
  if (ok && body.success) setTimeout(() => location.reload(), 900);
});

// Délégation : statut, suppression, traiter
document.addEventListener("click", async (e) => {
  const t = e.target;
  let payload = null;

  if (t.dataset.statut) {
    payload = { action: "changer_statut", type: t.dataset.type, id: +t.dataset.id, statut: t.dataset.statut };
  } else if (t.hasAttribute("data-supprimer")) {
    if (!confirm("Supprimer définitivement ce compte et ses données ?")) return;
    payload = { action: "supprimer", type: t.dataset.type, id: +t.dataset.id };
  } else if (t.hasAttribute("data-traiter")) {
    payload = { action: "traiter_demande", id: +t.dataset.id };
  }

  if (!payload) return;
  const { ok, body } = await appelerAdmin(payload);
  notifier(ok ? body.message : body.error, ok && body.success);
  if (ok && body.success) setTimeout(() => location.reload(), 700);
});
</script>

<?php require __DIR__ . '/../../inc/footer.php'; ?>
