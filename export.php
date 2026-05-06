<?php
include 'config.php';

header('Content-Type: text/html; charset=utf-8');

$search = $_GET['search'] ?? '';

// الاستعلام مع شرط البحث إذا كان موجود
if ($search) {
    $query = "SELECT * FROM visiteurs WHERE
        nom LIKE :search OR
        prenom LIKE :search OR
        cin LIKE :search
        ORDER BY date_arrivee DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute(['search' => "%$search%"]);
} else {
    $query = "SELECT * FROM visiteurs ORDER BY date_arrivee DESC";
    $stmt = $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Liste des visiteurs</title>
<style>
    table, th, td { border: 1px solid black; border-collapse: collapse; padding: 5px;}
    th { background-color: #f0f0f0; }
    .search-bar { margin-bottom: 15px; }
</style>
</head>
<body>

<h2>Recherche des visiteurs</h2>

<form method="get" action="" class="search-bar">
    <input type="text" name="search" placeholder="Tapez nom, prénom ou CIN" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Rechercher</button>
</form>

<table>
<tr>
    <th>ID</th><th>Nom</th><th>Prenom</th><th>CIN</th><th>Societe</th><th>Personne a visiter</th><th>Objet</th><th>Date arrivee</th><th>Heure entree</th><th>Heure depart</th><th>Statut</th>
</tr>

<?php
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $dateArrivee = $row['date_arrivee'] ? new DateTime($row['date_arrivee']) : null;
    $dateDepart = $row['date_depart'] ? new DateTime($row['date_depart']) : null;

    echo '<tr>';
    echo '<td>'.htmlspecialchars($row['id']).'</td>';
    echo '<td>'.htmlspecialchars($row['nom']).'</td>';
    echo '<td>'.htmlspecialchars($row['prenom']).'</td>';
    echo '<td>'.htmlspecialchars($row['cin']).'</td>';
    echo '<td>'.htmlspecialchars($row['societe']).'</td>';
    echo '<td>'.htmlspecialchars($row['personne_a_visiter']).'</td>';
    echo '<td>'.htmlspecialchars($row['objet']).'</td>';
    echo '<td>'.($dateArrivee ? $dateArrivee->format('Y-m-d') : '').'</td>';
    echo '<td>'.($dateArrivee ? $dateArrivee->format('H:i') : '').'</td>';
    echo '<td>'.($dateDepart ? $dateDepart->format('H:i') : '').'</td>';
    echo '<td>'.htmlspecialchars($row['statut']).'</td>';
    echo '</tr>';
}
?>

</table>

</body>
</html>
