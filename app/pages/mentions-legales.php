<?php
/**
 * mentions-legales.php — Mentions légales et politique de confidentialité (RGPD).
 */
require_once __DIR__ . '/../inc/db.php';

$titrePage = 'Mentions légales & RGPD';
require __DIR__ . '/../inc/header.php';
?>

<section class="card form-large">
  <h1>Mentions légales & protection des données</h1>

  <h2>Éditeur du site</h2>
  <p>Plateforme CV JUNIA — projet pédagogique, module Architecture Web AP3.</p>

  <h2>Données collectées</h2>
  <p>Dans le cadre de la mise en relation avec les entreprises partenaires, nous collectons :</p>
  <ul>
    <li>Vos données d'identité (nom, prénom, date de naissance, photo) ;</li>
    <li>Vos coordonnées (e-mail, téléphone, ville) ;</li>
    <li>Les éléments de votre CV (formations, expériences, compétences, biographie) ;</li>
    <li>Vos domaines de recherche d'opportunités.</li>
  </ul>

  <h2>Finalité du traitement</h2>
  <p>
    Ces données sont affichées dans un catalogue consultable par les entreprises
    partenaires JUNIA (réseau ALUMNI inclus), dans le seul but de faciliter votre
    recherche de stage, d'alternance, de CDI ou de mobilité.
  </p>

  <h2>Consentement</h2>
  <p>
    La création de votre compte nécessite votre consentement explicite à cette collecte.
    Aucune donnée n'est transmise à des tiers en dehors des entreprises partenaires.
  </p>

  <h2>Sécurité</h2>
  <p>
    Vos mots de passe sont stockés de façon sécurisée (hachés et non réversibles).
    Les échanges avec le serveur passent par des requêtes protégées contre les injections.
  </p>

  <h2>Vos droits</h2>
  <p>
    Conformément au RGPD, vous disposez d'un droit d'accès, de rectification et de
    suppression de vos données. Vous pouvez à tout moment
    <strong>supprimer votre compte et l'intégralité de vos données</strong> depuis
    votre page profil (rubrique « Supprimer mon compte »).
  </p>

  <?php if (type_utilisateur() === 'etudiant'): ?>
    <a href="/pages/profil.php" class="btn btn-ghost">Gérer mes données</a>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/../inc/footer.php'; ?>
