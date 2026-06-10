/**
 * catalogue.js — Catalogue entreprise : chargement filtré des profils,
 * rendu des cartes, et convocation via une modale.
 */

const DOMAINES = {
  stage: "Stage",
  alternance: "Alternance",
  cdi: "CDI",
  mobilite: "Mobilité",
};

function esc(v) {
  if (v === null || v === undefined) return "";
  return String(v).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
}

// ---------- Chargement des profils ----------
async function chargerProfils() {
  const params = new URLSearchParams({ action: "liste" });
  const domaine = document.getElementById("f-domaine").value;
  const competence = document.getElementById("f-competence").value.trim();
  const promo = document.getElementById("f-promo").value.trim();
  const q = document.getElementById("f-q").value.trim();
  if (domaine) params.set("domaine", domaine);
  if (competence) params.set("competence", competence);
  if (promo) params.set("promo", promo);
  if (q) params.set("q", q);

  const conteneur = document.getElementById("catalogue");
  conteneur.innerHTML = "<p>Chargement...</p>";

  try {
    const rep = await fetch("/api/profils.php?" + params.toString());
    const body = await rep.json();
    if (!body.success) {
      conteneur.innerHTML = `<p class="alert alert-error">${esc(body.error || "Erreur")}</p>`;
      return;
    }
    afficherProfils(body.profils);
    document.getElementById("compteur").textContent =
      body.total + " profil(s) trouvé(s)";
  } catch (e) {
    conteneur.innerHTML = `<p class="alert alert-error">Erreur réseau.</p>`;
  }
}

// ---------- Rendu des cartes ----------
function afficherProfils(profils) {
  const conteneur = document.getElementById("catalogue");
  if (!profils.length) {
    conteneur.innerHTML = "<p>Aucun profil ne correspond à votre recherche.</p>";
    return;
  }

  conteneur.innerHTML = profils
    .map((p) => {
      const photo = p.photo
        ? `<img class="profil-photo" src="${esc(p.photo)}" alt="">`
        : `<div class="profil-photo" style="background:var(--violet-clair);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700">
             ${esc((p.prenom[0] || "") + (p.nom[0] || ""))}
           </div>`;

      const badges = (p.domaines_recherche || [])
        .map((d) => `<span class="badge">${esc(DOMAINES[d] || d)}</span>`)
        .join("");

      const bio = p.biographie
        ? `<p>${esc(p.biographie.slice(0, 120))}${p.biographie.length > 120 ? "…" : ""}</p>`
        : "";

      return `
      <article class="card profil-card">
        ${photo}
        <h3>${esc(p.prenom)} ${esc(p.nom)}</h3>
        <div class="profil-meta">${esc(p.promo || p.ecole || "")}${p.ville ? " · " + esc(p.ville) : ""}</div>
        <div class="badges">${badges}</div>
        ${bio}
        ${p.competences ? `<p><strong>Compétences :</strong> ${esc(p.competences)}</p>` : ""}
        <div style="display:flex;gap:.5rem;margin-top:auto">
          <a class="btn btn-ghost btn-sm" href="/pages/cv.php?id=${p.id}">Voir le CV</a>
          <button class="btn btn-orange btn-sm btn-convoquer"
                  data-id="${p.id}" data-nom="${esc(p.prenom)} ${esc(p.nom)}">
            Convoquer
          </button>
        </div>
      </article>`;
    })
    .join("");
}

// ---------- Filtres ----------
document.getElementById("btn-filtrer").addEventListener("click", chargerProfils);
document.getElementById("btn-reset").addEventListener("click", () => {
  document.getElementById("form-filtres").reset();
  chargerProfils();
});

// ---------- Modale de convocation ----------
const modal = document.getElementById("modal-convocation");

document.getElementById("catalogue").addEventListener("click", (e) => {
  const btn = e.target.closest(".btn-convoquer");
  if (!btn) return;
  document.getElementById("conv-etudiant-id").value = btn.dataset.id;
  document.getElementById("modal-nom").textContent = "Candidat : " + btn.dataset.nom;
  document.getElementById("modal-message").className = "hidden";
  document.getElementById("form-convocation").reset();
  modal.classList.remove("hidden");
});

document.getElementById("btn-fermer-modal").addEventListener("click", () => modal.classList.add("hidden"));
modal.addEventListener("click", (e) => {
  if (e.target === modal) modal.classList.add("hidden");
});

// ---------- Envoi de la convocation ----------
document.getElementById("form-convocation").addEventListener("submit", async (e) => {
  e.preventDefault();
  const zone = document.getElementById("modal-message");

  const payload = {
    action: "convoquer",
    etudiant_id: parseInt(document.getElementById("conv-etudiant-id").value, 10),
    date_entretien: document.getElementById("conv-date").value,
    lieu: document.getElementById("conv-lieu").value.trim(),
    message: document.getElementById("conv-msg").value.trim(),
  };

  try {
    const rep = await fetch("/api/convoquer.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });
    const body = await rep.json();
    zone.textContent = body.success ? body.message : (body.error || "Erreur");
    zone.className = "alert " + (body.success ? "alert-ok" : "alert-error");
    if (body.success) {
      setTimeout(() => modal.classList.add("hidden"), 1500);
    }
  } catch (err) {
    zone.textContent = "Erreur réseau.";
    zone.className = "alert alert-error";
  }
});

// ---------- Chargement initial ----------
chargerProfils();
