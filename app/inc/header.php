<?php
/**
 * header.php — En-tête + barre de navigation, commun à toutes les pages.
 *
 * Variable optionnelle attendue AVANT l'inclusion :
 *   $titrePage (string) → titre de l'onglet.
 *
 * La navigation s'adapte au type d'utilisateur connecté.
 */

require_once __DIR__ . '/db.php';

$titrePage = $titrePage ?? 'Plateforme CV JUNIA';
$type      = type_utilisateur();      // null | etudiant | entreprise | admin
$nom       = $_SESSION['nom'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($titrePage) ?> — JUNIA</title>

  <!-- Polices de la charte : Montserrat (titres) + Open Sans (corps) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="/css/style.css">
</head>
<body>
  <header class="site-header">
    <div class="header-inner">
      <a class="logo" href="/index.php">
        <span class="logo-junia">JUNIA</span>
        <span class="logo-sub">Plateforme CV</span>
      </a>

      <!-- Bouton menu mobile -->
      <button class="nav-toggle" aria-label="Menu" onclick="document.querySelector('.main-nav').classList.toggle('open')">
        ☰
      </button>

      <nav class="main-nav">
        <a href="/index.php">Accueil</a>

        <?php if ($type === 'entreprise'): ?>
          <a href="/pages/catalogue.php">Catalogue</a>
          <a href="/pages/convocations.php">Mes convocations</a>
        <?php elseif ($type === 'etudiant'): ?>
          <a href="/pages/profil.php">Mon profil</a>
        <?php elseif ($type === 'admin'): ?>
          <a href="/pages/admin/index.php">Administration</a>
        <?php endif; ?>

        <a href="/pages/contact.php">Contact</a>

        <?php if (est_connecte()): ?>
          <span class="nav-user">Bonjour, <?= e($nom) ?></span>
          <a class="btn-nav" href="/api/auth.php?action=logout">Déconnexion</a>
        <?php else: ?>
          <a href="/pages/connexion.php">Connexion</a>
          <a class="btn-nav" href="/pages/inscription.php">Inscription</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <main class="site-main">
