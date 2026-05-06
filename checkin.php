<?php
include 'config.php';
$message = '';

// Récupérer les badges attribués à un visiteur en attente ou présent
$badges = $conn->query("SELECT b.code, v.nom, v.prenom FROM badges b JOIN visiteurs v ON b.etat = 'attribue' AND (v.statut = 'en_attente' OR v.statut = 'present') AND b.id = v.badge_id")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $badgeCode = trim($_POST['badge_code']);

    $stmt = $conn->prepare("SELECT b.id, v.id as visiteur_id, v.statut FROM badges b JOIN visiteurs v ON b.id = v.badge_id WHERE b.code = ?");
    $stmt->execute([$badgeCode]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        if ($data['statut'] == 'en_attente') {
            $stmt = $conn->prepare("UPDATE visiteurs SET statut='present', date_arrivee=NOW() WHERE id = ?");
            $stmt->execute([$data['visiteur_id']]);
            logAction($conn, "Check-in visiteur badge $badgeCode", $_SESSION['user_id']);
            $message = "Check-in réussi pour le badge <strong>$badgeCode</strong>";
        } else {
            $message = "Visiteur déjà enregistré ou visite terminée.";
        }
    } else {
        $message = "Badge inconnu.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Check-in visiteur</title>
  <link rel="stylesheet" href="css/checkin.css">
</head>
<body>
  <div class="container">
    <?php if ($message): ?>
      <div class="flash-message <?= strpos($message, 'réussi') !== false ? 'success' : 'error' ?>">
        <?= $message ?>
      </div>
    <?php endif; ?>
    <h2>Check-in visiteur</h2>
    <form method="post" novalidate>
      <select name="badge_code" required style="padding: 12px 16px; border: 2px solid #e0b3c1; border-radius: 10px; font-size: 1rem; background: #fff; color: #333; cursor: pointer; width: 100%; appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23333%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22%3e%3cpolyline points=%226,9 12,15 18,9%22%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 40px;">
        <option value="">Sélectionner un badge</option>
        <?php foreach ($badges as $badge): ?>
          <option value="<?= htmlspecialchars($badge['code']) ?>">
            <?= htmlspecialchars($badge['code']) ?> - <?= htmlspecialchars($badge['nom']) ?> <?= htmlspecialchars($badge['prenom']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button type="submit">Valider Check-in</button>
    </form>
    <p><a href="dashboard.php">Retour au tableau de bord</a></p>
  </div>
  <script src="js/checkin.js" defer></script>
</body>
</html>
