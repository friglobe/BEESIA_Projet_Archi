/**
 * auth.js — Gère l'envoi AJAX des formulaires de connexion et d'inscription
 * vers /api/auth.php, l'affichage des messages et la redirection.
 */

/**
 * Affiche un message d'état dans l'élément #message du formulaire.
 */
function afficherMessage(texte, type = "error") {
  const zone = document.getElementById("message");
  if (!zone) return;
  zone.textContent = texte;
  zone.className = "alert " + (type === "ok" ? "alert-ok" : "alert-error");
  zone.classList.remove("hidden");
}

/**
 * Envoie un objet en POST JSON vers l'API et renvoie {status, body}.
 */
async function envoyerAuth(payload) {
  const reponse = await fetch("/api/auth.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload),
  });
  const body = await reponse.json();
  return { status: reponse.status, body };
}

// ---------- Formulaire de CONNEXION ----------
const formConnexion = document.getElementById("form-connexion");
if (formConnexion) {
  formConnexion.addEventListener("submit", async (e) => {
    e.preventDefault();
    const payload = {
      action: "login",
      email: formConnexion.email.value.trim(),
      password: formConnexion.password.value,
    };

    try {
      const { status, body } = await envoyerAuth(payload);
      if (status === 200 && body.success) {
        afficherMessage(body.message, "ok");
        window.location.href = body.redirect;
      } else {
        afficherMessage(body.error || "Échec de la connexion.");
      }
    } catch (err) {
      afficherMessage("Erreur réseau. Réessayez.");
    }
  });
}

// ---------- Formulaire d'INSCRIPTION ----------
const formInscription = document.getElementById("form-inscription");
if (formInscription) {
  formInscription.addEventListener("submit", async (e) => {
    e.preventDefault();

    const password = formInscription.password.value;
    const confirmation = formInscription.password_confirm.value;

    // Validation côté client (en plus de la validation serveur)
    if (password !== confirmation) {
      afficherMessage("Les deux mots de passe ne correspondent pas.");
      return;
    }
    if (!formInscription.consentement_rgpd.checked) {
      afficherMessage("Vous devez accepter la politique de confidentialité.");
      return;
    }

    const payload = {
      action: "register",
      nom: formInscription.nom.value.trim(),
      prenom: formInscription.prenom.value.trim(),
      email: formInscription.email.value.trim(),
      password: password,
      consentement_rgpd: formInscription.consentement_rgpd.checked,
    };

    try {
      const { status, body } = await envoyerAuth(payload);
      if (status === 201 && body.success) {
        afficherMessage(body.message, "ok");
        setTimeout(() => (window.location.href = body.redirect), 800);
      } else {
        afficherMessage(body.error || "Échec de l'inscription.");
      }
    } catch (err) {
      afficherMessage("Erreur réseau. Réessayez.");
    }
  });
}
