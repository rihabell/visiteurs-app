<?php
include 'config.php';

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['accueil', 'admin'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['export']) && $_GET['export'] === 'pdf') {
    // Générer le PDF
    $search = $_GET['search'] ?? '';

    if ($search) {
        $query = "SELECT * FROM visiteurs WHERE (nom LIKE :search OR prenom LIKE :search OR cin LIKE :search OR personne_a_visiter LIKE :search)";
        $params = ['search' => "%$search%"];
        $query .= " ORDER BY date_arrivee DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
    } else {
        $query = "SELECT * FROM visiteurs ORDER BY date_arrivee DESC";
        $stmt = $conn->query($query);
    }

    // Créer le contenu HTML pour le PDF
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Liste des visiteurs</title>
        <style>
            body { font-family: Arial, sans-serif; font-size: 12px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; font-weight: bold; }
            h1 { color: #333; text-align: center; }
            .header { text-align: center; margin-bottom: 20px; }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Liste des visiteurs</h1>
            <p>Date d\'export: ' . date('d/m/Y H:i') . '</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>CIN</th>
                    <th>Société</th>
                    <th>Personne à visiter</th>
                    <th>Objet</th>
                    <th>Date arrivée</th>
                    <th>Heure entrée</th>
                    <th>Heure départ</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>';

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $dateArrivee = $row['date_arrivee'] ? new DateTime($row['date_arrivee']) : null;
        $dateDepart = $row['date_depart'] ? new DateTime($row['date_depart']) : null;

        $html .= '<tr>
            <td>' . htmlspecialchars($row['id']) . '</td>
            <td>' . htmlspecialchars($row['nom']) . '</td>
            <td>' . htmlspecialchars($row['prenom']) . '</td>
            <td>' . htmlspecialchars($row['cin']) . '</td>
            <td>' . htmlspecialchars($row['societe']) . '</td>
            <td>' . htmlspecialchars($row['personne_a_visiter']) . '</td>
            <td>' . htmlspecialchars($row['objet']) . '</td>
            <td>' . ($dateArrivee ? $dateArrivee->format('Y-m-d') : '') . '</td>
            <td>' . ($dateArrivee ? $dateArrivee->format('H:i') : '') . '</td>
            <td>' . ($dateDepart ? $dateDepart->format('H:i') : '') . '</td>
            <td>' . htmlspecialchars($row['statut']) . '</td>
        </tr>';
    }

    $html .= '</tbody></table></body></html>';

    // Headers pour le téléchargement PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="liste_visiteurs.pdf"');
    
    // Utiliser wkhtmltopdf ou une alternative simple
    // Pour l'instant, on va créer un fichier HTML que l'utilisateur peut imprimer en PDF
    header('Content-Type: text/html; charset=utf-8');
    header('Content-Disposition: attachment; filename="liste_visiteurs.html"');
    echo $html;
    exit();
}

$search = $_GET['search'] ?? '';

if ($search) {
    $query = "SELECT * FROM visiteurs WHERE (nom LIKE :search OR prenom LIKE :search OR cin LIKE :search OR personne_a_visiter LIKE :search)";
    $params = ['search' => "%$search%"];
    $query .= " ORDER BY date_arrivee DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
} else {
    $query = "SELECT * FROM visiteurs ORDER BY date_arrivee DESC";
    $stmt = $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Liste des visiteurs</title>
  <link rel="stylesheet" href="css/liste_des_visiteurs.css" />
</head>
<body>
  <div class="list-wrapper">
    <header class="list-header">
      <h1>Liste des visiteurs</h1>
    </header>

    <form method="get" action="" class="search-form">
      <input type="text" name="search" placeholder="Tapez nom, prénom, CIN ou personne à visiter" value="<?= htmlspecialchars($search) ?>" />
      <div class="buttons">
        <button type="submit" class="btn search-btn">Rechercher</button>
      </div>
    </form>

    <!-- Formulaire séparé pour l'export PDF -->
    <form method="get" action="" class="export-form">
      <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>" />
      <button type="submit" name="export" value="pdf" class="btn export-btn">Exporter PDF</button>
    </form>

    <div class="table-container">
      <table class="visitors-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>CIN</th>
            <th>Société</th>
            <th>Personne à visiter</th>
            <th>Objet</th>
            <th>Date arrivée</th>
            <th>Heure entrée</th>
            <th>Heure départ</th>
            <th>Statut</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            $dateArrivee = $row['date_arrivee'] ? new DateTime($row['date_arrivee']) : null;
            $dateDepart = $row['date_depart'] ? new DateTime($row['date_depart']) : null;
        ?>
          <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['nom']) ?></td>
            <td><?= htmlspecialchars($row['prenom']) ?></td>
            <td><?= htmlspecialchars($row['cin']) ?></td>
            <td><?= htmlspecialchars($row['societe']) ?></td>
            <td><?= htmlspecialchars($row['personne_a_visiter']) ?></td>
            <td><?= htmlspecialchars($row['objet']) ?></td>
            <td><?= $dateArrivee ? $dateArrivee->format('Y-m-d') : '' ?></td>
            <td><?= $dateArrivee ? $dateArrivee->format('H:i') : '' ?></td>
            <td><?= $dateDepart ? $dateDepart->format('H:i') : '' ?></td>
            <td><?= htmlspecialchars($row['statut']) ?></td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <a href="dashboard.php" class="back-link">&larr; Retour au tableau de bord</a>
  </div>
  <script src="js/liste_des_visiteurs.js?v=2" defer></script>
</body>
</html>
