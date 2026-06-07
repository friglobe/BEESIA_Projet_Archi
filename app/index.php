<?php
/**
 * index.php — Page d'accueil de la plateforme CV JUNIA.
 */
require_once __DIR__ . '/inc/db.php';

// Entreprises partenaires à mettre en avant
$entreprises = db()->query(
    'SELECT nom, secteur, description FROM entreprises WHERE statut = "actif" ORDER BY nom LIMIT 6'
)->fetchAll();

$titrePage = 'Accueil';
require __DIR__ . '/inc/header.php';
?>

<!-- ===== Hero ===== -->
<section class="hero">
  <h1>Votre CV, vu par les bonnes entreprises</h1>
  <p>
    La plateforme JUNIA qui centralise les CV étudiants et les met en relation
    avec les entreprises partenaires et le réseau ALUMNI.
  </p>
  <?php if (!est_connecte()): ?>
    <a href="/pages/inscription.php" class="btn btn-orange">Créer mon profil étudiant</a>
    <a href="/pages/connexion.php" class="btn btn-ghost" style="color:#fff;border-color:#fff">Se connecter</a>
  <?php elseif (type_utilisateur() === 'etudiant'): ?>
    <a href="/pages/profil.php" class="btn btn-orange">Compléter mon CV</a>
  <?php elseif (type_utilisateur() === 'entreprise'): ?>
    <a href="/pages/catalogue.php" class="btn btn-orange">Parcourir le catalogue</a>
  <?php endif; ?>
</section>

<!-- ===== Fonctionnalités ===== -->
<h2 class="section-titre">Trouvez l'opportunité qui vous correspond</h2>
<div class="grid">
  <div class="card">
    <h3>🎓 Stages</h3>
    <p>Stages de 1re et 2e année pour découvrir le monde de l'entreprise.</p>
  </div>
  <div class="card">
    <h3>🔄 Alternance</h3>
    <p>Contrats d'apprentissage et de professionnalisation en 5e année.</p>
  </div>
  <div class="card">
    <h3>💼 CDI</h3>
    <p>Lancez votre carrière auprès des entreprises partenaires JUNIA.</p>
  </div>
  <div class="card">
    <h3>🌍 Mobilité</h3>
    <p>Opportunités à l'international pour une expérience à l'étranger.</p>
  </div>
</div>

<!-- ===== Entreprises partenaires ===== -->
<h2 class="section-titre">Nos entreprises partenaires</h2>
<?php if ($entreprises): ?>
  <div class="grid">
    <?php foreach ($entreprises as $ent): ?>
      <div class="card">
        <h3><?= e($ent['nom']) ?></h3>
        <?php if ($ent['secteur']): ?><span class="badge badge-orange"><?= e($ent['secteur']) ?></span><?php endif; ?>
        <p><?= e($ent['description']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <p style="text-align:center">Nos partenaires seront bientôt présentés ici.</p>
<?php endif; ?>

<!-- ===== Appel entreprises ===== -->
<section class="card" style="margin-top:2.5rem;text-align:center">
  <h2>Vous êtes une entreprise ?</h2>
  <p>Rejoignez le réseau de partenaires JUNIA et accédez aux meilleurs profils étudiants.</p>
  <a href="/pages/contact.php" class="btn">Devenir partenaire</a>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
