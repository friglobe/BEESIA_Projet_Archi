<?php
/**
 * catalogue.php — Catalogue des profils étudiants (entreprises / admins).
 */
require_once __DIR__ . '/../inc/db.php';

exiger_connexion(['entreprise', 'admin']);

$titrePage = 'Catalogue des profils';
require __DIR__ . '/../inc/header.php';
?>

<h1>Catalogue des profils étudiants</h1>
<p class="profil-meta">Filtrez les profils puis convoquez les candidats qui vous intéressent.</p>

<!-- ===== Filtres ===== -->
<form class="filtres card" id="form-filtres" onsubmit="return false">
  <div class="field">
    <label for="f-domaine">Domaine recherché</label>
    <select id="f-domaine">
      <option value="">Tous</option>
      <option value="stage">Stage</option>
      <option value="alternance">Alternance</option>
      <option value="cdi">CDI</option>
      <option value="mobilite">Mobilité internationale</option>
    </select>
  </div>
  <div class="field">
    <label for="f-competence">Compétence</label>
    <input type="text" id="f-competence" placeholder="ex : PHP, Docker...">
  </div>
  <div class="field">
    <label for="f-promo">École / promotion</label>
    <input type="text" id="f-promo" placeholder="ex : ISEN-AP3">
  </div>
  <div class="field">
    <label for="f-q">Recherche libre</label>
    <input type="text" id="f-q" placeholder="nom, ville...">
  </div>
  <button type="button" class="btn" id="btn-filtrer">Filtrer</button>
  <button type="button" class="btn btn-ghost" id="btn-reset">Réinitialiser</button>
</form>

<p id="compteur" class="profil-meta"></p>
<div id="catalogue" class="grid"></div>

<!-- ===== Modale de convocation ===== -->
<div id="modal-convocation" class="modal-overlay hidden">
  <div class="modal">
    <h2>Convoquer un candidat</h2>
    <p id="modal-nom" class="profil-meta"></p>

    <div id="modal-message" class="hidden"></div>

    <form id="form-convocation">
      <input type="hidden" id="conv-etudiant-id">
      <div class="field">
        <label for="conv-date">Date et heure de l'entretien *</label>
        <input type="datetime-local" id="conv-date" required>
      </div>
      <div class="field">
        <label for="conv-lieu">Lieu</label>
        <input type="text" id="conv-lieu" placeholder="Visio, adresse...">
      </div>
      <div class="field">
        <label for="conv-msg">Message au candidat</label>
        <textarea id="conv-msg" placeholder="Détails de l'entretien..."></textarea>
      </div>
      <div style="display:flex;gap:.6rem">
        <button type="submit" class="btn btn-orange">Envoyer la convocation</button>
        <button type="button" class="btn btn-ghost" id="btn-fermer-modal">Annuler</button>
      </div>
    </form>
  </div>
</div>

<script src="/js/catalogue.js"></script>

<?php require __DIR__ . '/../inc/footer.php'; ?>
