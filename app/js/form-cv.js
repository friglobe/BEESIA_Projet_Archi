/**
 * form-cv.js — Formulaire CV étudiant.
 *  - Construit les lignes dynamiques (formations, expériences, compétences)
 *  - Ajout / suppression de lignes
 *  - Aperçu de la photo
 *  - Soumission AJAX (multipart) vers /api/enregistrer-cv.php
 *  - Suppression de compte (RGPD)
 */

// ---------- Helpers ----------
function message(texte, type = "error") {
  const zone = document.getElementById("message");
  zone.textContent = texte;
  zone.className = "alert " + (type === "ok" ? "alert-ok" : "alert-error");
  zone.classList.remove("hidden");
  window.scrollTo({ top: 0, behavior: "smooth" });
}

function el(html) {
  const t = document.createElement("template");
  t.innerHTML = html.trim();
  return t.content.firstElementChild;
}

// ---------- Modèles de lignes ----------
function ligneFormation(d = {}) {
  return el(`
    <div class="card repeat-row" data-type="formation" style="margin-bottom:1rem">
      <div class="field-row">
        <div class="field"><label>École</label><input class="f-ecole" value="${esc(d.ecole)}"></div>
        <div class="field"><label>Diplôme</label><input class="f-diplome" value="${esc(d.diplome)}"></div>
      </div>
      <div class="field-row">
        <div class="field"><label>Spécialisation</label><input class="f-specialisation" value="${esc(d.specialisation)}"></div>
        <div class="field"><label>Année début</label><input class="f-annee_debut" type="number" min="1990" max="2099" value="${esc(d.annee_debut)}"></div>
        <div class="field"><label>Année fin</label><input class="f-annee_fin" type="number" min="1990" max="2099" value="${esc(d.annee_fin)}"></div>
      </div>
      <div class="field"><label>Description</label><textarea class="f-description">${esc(d.description)}</textarea></div>
      <button type="button" class="btn btn-danger btn-sm" data-remove>Supprimer</button>
    </div>`);
}

function ligneExperience(d = {}) {
  return el(`
    <div class="card repeat-row" data-type="experience" style="margin-bottom:1rem">
      <div class="field-row">
        <div class="field"><label>Entreprise</label><input class="f-entreprise" value="${esc(d.entreprise)}"></div>
        <div class="field"><label>Poste</label><input class="f-poste" value="${esc(d.poste)}"></div>
      </div>
      <div class="field-row">
        <div class="field"><label>Date début</label><input class="f-date_debut" type="date" value="${esc(d.date_debut)}"></div>
        <div class="field"><label>Date fin</label><input class="f-date_fin" type="date" value="${esc(d.date_fin)}"></div>
      </div>
      <div class="field"><label>Description</label><textarea class="f-description">${esc(d.description)}</textarea></div>
      <button type="button" class="btn btn-danger btn-sm" data-remove>Supprimer</button>
    </div>`);
}

function ligneCompetence(d = {}) {
  const cat = (v) => (d.categorie === v ? "selected" : "");
  const niv = (v) => (d.niveau === v ? "selected" : "");
  return el(`
    <div class="card repeat-row" data-type="competence" style="margin-bottom:1rem">
      <div class="field-row">
        <div class="field"><label>Compétence</label><input class="f-libelle" value="${esc(d.libelle)}"></div>
        <div class="field"><label>Catégorie</label>
          <select class="f-categorie">
            <option value="technique" ${cat("technique")}>Technique</option>
            <option value="langue" ${cat("langue")}>Langue</option>
            <option value="soft-skill" ${cat("soft-skill")}>Soft skill</option>
          </select>
        </div>
        <div class="field"><label>Niveau</label>
          <select class="f-niveau">
            <option value="debutant" ${niv("debutant")}>Débutant</option>
            <option value="intermediaire" ${niv("intermediaire")}>Intermédiaire</option>
            <option value="avance" ${niv("avance")}>Avancé</option>
            <option value="expert" ${niv("expert")}>Expert</option>
          </select>
        </div>
      </div>
      <button type="button" class="btn btn-danger btn-sm" data-remove>Supprimer</button>
    </div>`);
}

function esc(v) {
  if (v === null || v === undefined) return "";
  return String(v).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
}

// ---------- Initialisation des lignes existantes ----------
const conteneurs = {
  formation: document.getElementById("formations"),
  experience: document.getElementById("experiences"),
  competence: document.getElementById("competences"),
};
const fabriques = {
  formation: ligneFormation,
  experience: ligneExperience,
  competence: ligneCompetence,
};

(window.PROFIL.formations || []).forEach((d) => conteneurs.formation.appendChild(ligneFormation(d)));
(window.PROFIL.experiences || []).forEach((d) => conteneurs.experience.appendChild(ligneExperience(d)));
(window.PROFIL.competences || []).forEach((d) => conteneurs.competence.appendChild(ligneCompetence(d)));

// ---------- Ajout de lignes ----------
document.querySelectorAll("[data-add]").forEach((btn) => {
  btn.addEventListener("click", () => {
    const type = btn.dataset.add;
    conteneurs[type].appendChild(fabriques[type]());
  });
});

// ---------- Suppression de lignes (délégation) ----------
document.getElementById("form-cv").addEventListener("click", (e) => {
  if (e.target.matches("[data-remove]")) {
    e.target.closest(".repeat-row").remove();
  }
});

// ---------- Aperçu photo ----------
document.getElementById("photo").addEventListener("change", (e) => {
  const file = e.target.files[0];
  const img = document.getElementById("photo-preview");
  if (file) {
    img.src = URL.createObjectURL(file);
    img.classList.remove("hidden");
  }
});

// ---------- Collecte des lignes ----------
function collecter(type) {
  return [...conteneurs[type].querySelectorAll(".repeat-row")].map((row) => {
    const obj = {};
    row.querySelectorAll("input, textarea, select").forEach((champ) => {
      const cle = [...champ.classList].find((c) => c.startsWith("f-"));
      if (cle) obj[cle.slice(2)] = champ.value;
    });
    return obj;
  });
}

// ---------- Soumission ----------
document.getElementById("form-cv").addEventListener("submit", async (e) => {
  e.preventDefault();
  const form = e.target;

  const fd = new FormData();
  ["nom", "prenom", "date_naissance", "telephone", "ville", "ecole", "promo", "biographie"]
    .forEach((champ) => fd.append(champ, form[champ].value));

  // Domaines cochés
  form.querySelectorAll('input[name="domaines[]"]:checked')
    .forEach((cb) => fd.append("domaines[]", cb.value));

  // Sous-collections en JSON
  fd.append("cv_json", JSON.stringify({
    formations: collecter("formation"),
    experiences: collecter("experience"),
    competences: collecter("competence"),
  }));

  // Photo
  if (form.photo.files[0]) fd.append("photo", form.photo.files[0]);

  try {
    const rep = await fetch("/api/enregistrer-cv.php", { method: "POST", body: fd });
    const body = await rep.json();
    if (rep.ok && body.success) {
      message(body.message, "ok");
    } else {
      message(body.error || "Erreur lors de l'enregistrement.");
    }
  } catch (err) {
    message("Erreur réseau. Réessayez.");
  }
});

// ---------- Suppression de compte (RGPD) ----------
document.getElementById("btn-supprimer").addEventListener("click", async () => {
  if (!confirm("Supprimer définitivement votre compte et toutes vos données ?")) return;
  try {
    const rep = await fetch("/api/profils.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ action: "supprimer_compte" }),
    });
    const body = await rep.json();
    if (rep.ok && body.success) {
      window.location.href = body.redirect;
    } else {
      message(body.error || "Suppression impossible.");
    }
  } catch (err) {
    message("Erreur réseau.");
  }
});
