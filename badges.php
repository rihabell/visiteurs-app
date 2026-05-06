<?php
include 'config.php';

$message = '';
$message_type = '';

// Ajouter un Badge
if (isset($_POST['ajouter']) && !empty($_POST['code'])) {
  $code = trim($_POST['code']);
  // Vérifier si le code existe déjà
  $stmt = $conn->prepare("SELECT COUNT(*) FROM badges WHERE code = ?");
  $stmt->execute([$code]);
  $exists = $stmt->fetchColumn();
  if ($exists > 0) {
    $message = "Erreur : Ce code de badge existe déjà !";
    $message_type = 'error';
  } else {
    $stmt = $conn->prepare("INSERT INTO badges (code) VALUES (?)");
    $stmt->execute([$code]);
    $message = "Badge ajouté avec succès.";
    $message_type = 'success';
  }
}

// Supprimer (libérer) un Badge
if (isset($_GET['liberer'])) {
  $id = intval($_GET['liberer']);
  $stmt = $conn->prepare("DELETE FROM badges WHERE id=?");
  $stmt->execute([$id]);
}

// Modifier un Badge
$badge_a_modifier = null;
if (isset($_GET['modifier'])) {
  $id = intval($_GET['modifier']);
  $stmt = $conn->prepare("SELECT * FROM badges WHERE id=?");
  $stmt->execute([$id]);
  $badge_a_modifier = $stmt->fetch();
}

if (isset($_POST['modifier_badge']) && !empty($_POST['code_modif']) && !empty($_POST['id_modif'])) {
  $id_modif = intval($_POST['id_modif']);
  $code_modif = trim($_POST['code_modif']);
  $stmt = $conn->prepare("UPDATE badges SET code=? WHERE id=?");
  $stmt->execute([$code_modif, $id_modif]);
  // Après modification, on redirige pour éviter le repost
  header('Location: badges.php');
  exit;
}

// Badges List avec nom/prenom du visiteur si occupé
$badges = $conn->query("SELECT b.*, v.nom, v.prenom FROM badges b LEFT JOIN visiteurs v ON b.id = v.badge_id AND v.statut IN ('en_attente', 'present')")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Badges</title>
  <link rel="stylesheet" href="css/badges.css">
</head>
<body>
  <div class="container">
    <h1>Gestion des Badges</h1>

    <?php if ($message): ?>
      <div class="message <?= $message_type ?>">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <form method="post" class="add-form">
      <input type="text" name="code" placeholder="Code du badge" required>
      <button type="submit" name="ajouter">Ajouter</button>
    </form>

    <div class="flex-container">
      <div class="badges-table">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Code</th>
              <th>État</th>
              <th>Date Création</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($badges as $badge): ?>
            <tr>
              <td><?= $badge['id'] ?></td>
              <td><?= isset($badge['code']) ? htmlspecialchars($badge['code']) : '' ?></td>
              <td>
                <?php if (isset($badge['etat']) && $badge['etat'] == 'attribue'): ?>
                  <span style="color: red; font-weight: bold;">Occupé</span>
                  <?php if (!empty($badge['nom']) && !empty($badge['prenom'])): ?>
                    <br><small>(<?= htmlspecialchars($badge['nom'] . ' ' . $badge['prenom']) ?>)</small>
                  <?php endif; ?>
                <?php else: ?>
                  <span style="color: green; font-weight: bold;">Libre</span>
                <?php endif; ?>
              </td>
              <td><?= isset($badge['date_creation']) ? $badge['date_creation'] : '' ?></td>
              <td>
                <div class="action-links">
                  <a href="badges.php?modifier=<?= $badge['id'] ?>">Modifier</a>
                  <a href="badges.php?liberer=<?= $badge['id'] ?>" onclick="return confirm('Supprimer ce badge ?');">Libérer</a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php if ($badge_a_modifier): ?>
        <div class="modif-box">
          <h3>Modifier le badge</h3>
          <form method="post">
            <input type="hidden" name="id_modif" value="<?= $badge_a_modifier['id'] ?>">
            <label>Nouveau code :</label>
            <input type="text" name="code_modif" value="<?= htmlspecialchars($badge_a_modifier['code']) ?>" required>
            <button type="submit" name="modifier_badge">Enregistrer</button>
          </form>
        </div>
      <?php endif; ?>
    </div>

    <a href="dashboard.php" class="back-link">Retour Dashboard</a>
  </div>
</body>
</html>