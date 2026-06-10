<?php
/**
 * inscription.php — Création de compte étudiant (avec consentement RGPD).
 */
require_once __DIR__ . '/../inc/db.php';

if (est_connecte()) {
    header('Location: /pages/profil.php');
    exit;
}

$titrePage = 'Inscription étudiant';
require __DIR__ . '/../inc/header.php';
?>

<section class="card form-card">
  <h1>Créer un compte étudiant</h1>
  <p class="profil-meta">Réservé aux étudiants JUNIA (adresse <strong>@junia.com</strong>).</p>

  <div id="message" class="hidden"></div>

  <form id="form-inscription" novalidate>
    <div class="field-row">
      <div class="field">
        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" required>
      </div>
      <div class="field">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" required>
      </div>
    </div>

    <div class="field">
      <label for="email">Adresse e-mail JUNIA</label>
      <input type="email" id="email" name="email" required placeholder="prenom.nom@junia.com">
    </div>

    <div class="field-row">
      <div class="field">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password">
      </div>
      <div class="field">
        <label for="password_confirm">Confirmation</label>
        <input type="password" id="password_confirm" name="password_confirm" required minlength="8" autocomplete="new-password">
      </div>
    </div>

    <div class="field field-inline">
      <input type="checkbox" id="consentement_rgpd" name="consentement_rgpd" required>
      <label for="consentement_rgpd" style="font-weight:400">
        J'accepte que mes données soient collectées et affichées dans le catalogue,
        conformément à la <a href="/pages/mentions-legales.php" target="_blank">politique de confidentialité</a>.
      </label>
    </div>

    <button type="submit" class="btn btn-orange">Créer mon compte</button>
  </form>

  <p style="margin-top:1.2rem">
    Déjà inscrit ? <a href="/pages/connexion.php">Connectez-vous</a>.
  </p>
</section>

<script src="/js/auth.js"></script>

<?php require __DIR__ . '/../inc/footer.php'; ?>
