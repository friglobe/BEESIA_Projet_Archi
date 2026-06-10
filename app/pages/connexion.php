<?php
/**
 * connexion.php — Formulaire de connexion (étudiant / entreprise / admin).
 */
require_once __DIR__ . '/../inc/db.php';

// Déjà connecté ? On redirige vers l'espace adapté.
if (est_connecte()) {
    $dest = match (type_utilisateur()) {
        'entreprise' => '/pages/catalogue.php',
        'admin'      => '/pages/admin/index.php',
        default      => '/pages/profil.php',
    };
    header('Location: ' . $dest);
    exit;
}

$titrePage = 'Connexion';
require __DIR__ . '/../inc/header.php';
?>

<section class="card form-card">
  <h1>Connexion</h1>
  <p class="profil-meta">Étudiants, entreprises partenaires et administration.</p>

  <div id="message" class="hidden"></div>

  <form id="form-connexion" novalidate>
    <div class="field">
      <label for="email">Adresse e-mail</label>
      <input type="email" id="email" name="email" required autocomplete="email" placeholder="prenom.nom@junia.com">
    </div>

    <div class="field">
      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" required autocomplete="current-password">
    </div>

    <button type="submit" class="btn btn-orange">Se connecter</button>
  </form>

  <p style="margin-top:1.2rem">
    Pas encore de compte étudiant ?
    <a href="/pages/inscription.php">Créez-en un</a>.
  </p>
</section>

<script src="/js/auth.js"></script>

<?php require __DIR__ . '/../inc/footer.php'; ?>
