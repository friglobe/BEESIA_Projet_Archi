<?php
/**
 * convocations.php — Historique des convocations émises par l'entreprise.
 */
require_once __DIR__ . '/../inc/db.php';

exiger_connexion('entreprise');

$stmt = db()->prepare(
    'SELECT c.date_entretien, c.lieu, c.message, c.statut, c.date_convocation, c.email_envoye,
            e.prenom, e.nom, e.email
     FROM convocations c
     JOIN etudiants e ON e.id = c.etudiant_id
     WHERE c.entreprise_id = ?
     ORDER BY c.date_convocation DESC'
);
$stmt->execute([id_utilisateur()]);
$convocations = $stmt->fetchAll();

$statutsLabels = [
    'en_attente'   => 'En attente',
    'accepte'      => 'Accepté',
    'refuse'       => 'Refusé',
    'sans_reponse' => 'Sans réponse',
];

$titrePage = 'Mes convocations';
require __DIR__ . '/../inc/header.php';
?>

<h1>Historique des convocations</h1>
<p class="profil-meta">Candidats que vous avez invités en entretien.</p>

<?php if (!$convocations): ?>
  <div class="card">
    <p>Vous n'avez encore convoqué aucun candidat.</p>
    <a class="btn btn-orange" href="/pages/catalogue.php">Parcourir le catalogue</a>
  </div>
<?php else: ?>
  <table class="data">
    <thead>
      <tr>
        <th>Candidat</th>
        <th>E-mail</th>
        <th>Date entretien</th>
        <th>Lieu</th>
        <th>Statut</th>
        <th>Courriel</th>
        <th>Convoqué le</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($convocations as $c): ?>
        <tr>
          <td><?= e($c['prenom'] . ' ' . $c['nom']) ?></td>
          <td><?= e($c['email']) ?></td>
          <td><?= e(date('d/m/Y H:i', strtotime($c['date_entretien']))) ?></td>
          <td><?= e($c['lieu'] ?: '—') ?></td>
          <td><span class="badge"><?= e($statutsLabels[$c['statut']] ?? $c['statut']) ?></span></td>
          <td><?= $c['email_envoye'] ? '✅' : '—' ?></td>
          <td><?= e(date('d/m/Y', strtotime($c['date_convocation']))) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?php require __DIR__ . '/../inc/footer.php'; ?>
