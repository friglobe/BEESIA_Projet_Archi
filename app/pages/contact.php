<?php
/**
 * contact.php — Formulaire de demande de partenariat (entreprises non-partenaires).
 */
require_once __DIR__ . '/../inc/db.php';

$titrePage = 'Contact / Devenir partenaire';
require __DIR__ . '/../inc/header.php';
?>

<section class="card form-card">
  <h1>Devenir entreprise partenaire</h1>
  <p class="profil-meta">
    Vous n'êtes pas encore partenaire ? Laissez-nous vos coordonnées,
    l'équipe JUNIA vous recontactera pour créer votre accès.
  </p>

  <div id="message" class="hidden"></div>

  <form id="form-contact" novalidate>
    <div class="field">
      <label for="nom_entreprise">Nom de l'entreprise *</label>
      <input type="text" id="nom_entreprise" name="nom_entreprise" required>
    </div>
    <div class="field">
      <label for="nom_contact">Votre nom *</label>
      <input type="text" id="nom_contact" name="nom_contact" required>
    </div>
    <div class="field-row">
      <div class="field">
        <label for="email">E-mail *</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="field">
        <label for="telephone">Téléphone</label>
        <input type="tel" id="telephone" name="telephone">
      </div>
    </div>
    <div class="field">
      <label for="message">Message</label>
      <textarea id="message" name="message" placeholder="Présentez votre besoin..."></textarea>
    </div>
    <button type="submit" class="btn btn-orange">Envoyer ma demande</button>
  </form>
</section>

<script>
document.getElementById("form-contact").addEventListener("submit", async (e) => {
  e.preventDefault();
  const f = e.target;
  const zone = document.getElementById("message");
  const payload = {
    nom_entreprise: f.nom_entreprise.value.trim(),
    nom_contact: f.nom_contact.value.trim(),
    email: f.email.value.trim(),
    telephone: f.telephone.value.trim(),
    message: f.message.value.trim(),
  };
  try {
    const rep = await fetch("/api/contact.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });
    const body = await rep.json();
    zone.textContent = body.success ? body.message : (body.error || "Erreur");
    zone.className = "alert " + (body.success ? "alert-ok" : "alert-error");
    zone.classList.remove("hidden");
    if (body.success) f.reset();
  } catch (err) {
    zone.textContent = "Erreur réseau.";
    zone.className = "alert alert-error";
    zone.classList.remove("hidden");
  }
});
</script>

<?php require __DIR__ . '/../inc/footer.php'; ?>
