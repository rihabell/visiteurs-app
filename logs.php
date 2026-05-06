<?php
include 'config.php';

// Suppression des logs sélectionnés
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_selected']) && !empty($_POST['log_ids'])) {
    $ids = array_map('intval', $_POST['log_ids']);
    $in  = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $conn->prepare("DELETE FROM logs WHERE id IN ($in)");
    $stmt->execute($ids);
}

$filterUser = $_GET['user'] ?? '';
$filterAction = $_GET['action'] ?? '';

$query = "SELECT l.*, u.email 
          FROM logs l 
          LEFT JOIN utilisateurs u ON l.utilisateur_id = u.id 
          WHERE 1=1";
$params = [];

if (!empty($filterUser)) {
    $query .= " AND u.email LIKE ?";
    $params[] = "%$filterUser%";
}

if (!empty($filterAction)) {
    $query .= " AND l.action LIKE ?";
    $params[] = "%$filterAction%";
}

$query .= " ORDER BY l.date_action DESC LIMIT 100";

$stmt = $conn->prepare($query);
$stmt->execute($params);
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Journal des Actions</title>
    <link rel="stylesheet" href="css/logs.css">
    <style>
      .checkbox-col, .log-checkbox, #delete-selected-btn { display: none; }
      .select-mode .checkbox-col, .select-mode .log-checkbox, .select-mode #delete-selected-btn { display: table-cell !important; }
      .select-mode #delete-selected-btn { display: inline-block !important; margin-top: 10px; }
      #select-logs-btn { margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Journal des Actions</h1>
        <form method="get" action="">
            <div>
                <label for="user">Filtrer par utilisateur:</label>
                <input type="text" id="user" name="user" value="<?= htmlspecialchars($filterUser) ?>">
            </div>
            <div>
                <label for="action">Filtrer par action:</label>
                <input type="text" id="action" name="action" value="<?= htmlspecialchars($filterAction) ?>">
            </div>
            <button type="submit">Appliquer les filtres</button>
            <button type="button" onclick="window.location.href='?'">Réinitialiser</button>
        </form>
        <?php if (empty($logs)): ?>
            <p class="no-logs">Aucune action enregistrée pour le moment.</p>
        <?php else: ?>
            <button id="select-logs-btn" onclick="toggleSelectMode(event)">Sélectionner les logs</button>
            <form method="post" action="" id="logs-form">
            <table id="logs-table">
                <thead>
                    <tr>
                        <th class="checkbox-col"><input type="checkbox" id="select-all" onclick="toggleAll(this)"></th>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="checkbox-col"><input type="checkbox" class="log-checkbox" name="log_ids[]" value="<?= $log['id'] ?>" onchange="updateDeleteBtn()"></td>
                            <td><?= htmlspecialchars($log['date_action'] ?? '') ?></td>
                            <td><?= htmlspecialchars($log['email'] ?? 'Système') ?></td>
                            <td><?= htmlspecialchars($log['action'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" id="delete-selected-btn" name="delete_selected" onclick="return confirm('Supprimer les logs sélectionnés ?')">Supprimer la sélection</button>
            </form>
        <?php endif; ?>
        <p><a href="dashboard.php">Retour au tableau de bord</a></p>
    </div>
    <script>
    function toggleSelectMode(e) {
        e.preventDefault();
        var container = document.querySelector('.container');
        var table = document.getElementById('logs-table');
        var btn = document.getElementById('select-logs-btn');
        if (table.classList.contains('select-mode')) {
            table.classList.remove('select-mode');
            btn.textContent = 'Sélectionner les logs';
            // Décocher tout
            document.getElementById('select-all').checked = false;
            toggleAll(document.getElementById('select-all'));
        } else {
            table.classList.add('select-mode');
            btn.textContent = 'Annuler la sélection';
        }
        updateDeleteBtn();
    }
    function toggleAll(source) {
        var checkboxes = document.querySelectorAll('.log-checkbox');
        for(var i=0; i<checkboxes.length; i++) {
            checkboxes[i].checked = source.checked;
        }
        updateDeleteBtn();
    }
    function updateDeleteBtn() {
        var table = document.getElementById('logs-table');
        var btn = document.getElementById('delete-selected-btn');
        var anyChecked = false;
        var checkboxes = document.querySelectorAll('.log-checkbox');
        for(var i=0; i<checkboxes.length; i++) {
            if (checkboxes[i].checked) { anyChecked = true; break; }
        }
        if (table.classList.contains('select-mode') && anyChecked) {
            btn.style.display = 'inline-block';
        } else {
            btn.style.display = 'none';
        }
    }
    </script>
    <script src="logs.js" defer></script>
</body>
</html>
