<?php
include 'config.php';

$message = '';
$message_type = '';

// Récupérer les badges disponibles (non attribués)
$badges_disponibles = $conn->query("SELECT id, code FROM badges WHERE etat = 'disponible' OR etat IS NULL")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $cin = trim($_POST['cin']); 
    $societe = trim($_POST['societe']);
    $personne = trim($_POST['personne']);
    $objet = trim($_POST['objet']);
    $badge_id = isset($_POST['badge_id']) ? intval($_POST['badge_id']) : 0;

    // Champs obligatoires
    if (empty($nom) || empty($prenom) || empty($cin) || empty($personne) || empty($objet) || empty($badge_id)) {
        $message = 'Veuillez remplir tous les champs obligatoires.';
        $message_type = 'error';
    } else {
        $stmt = $conn->prepare("INSERT INTO visiteurs (nom, prenom, cin, societe, personne_a_visiter, objet, badge_id, statut) VALUES (?, ?, ?, ?, ?, ?, ?, 'en_attente')");
        $stmt->execute([$nom, $prenom, $cin, $societe, $personne, $objet, $badge_id]);

        $visiteurId = $conn->lastInsertId();

        // Passer le badge à 'attribué'
        $stmt = $conn->prepare("UPDATE badges SET etat = 'attribue' WHERE id = ?");
        $stmt->execute([$badge_id]);

        // Récupérer le code du badge pour l'afficher
        $stmt = $conn->prepare("SELECT code FROM badges WHERE id = ?");
        $stmt->execute([$badge_id]);
        $badge = $stmt->fetch(PDO::FETCH_ASSOC);
        $code = $badge ? $badge['code'] : '';

        logAction($conn, "Visiteur $nom $prenom enregistré avec badge $code", $_SESSION['user_id']);
        $message = "Visiteur ($nom $prenom) est enregistré avec succès.";
        $message_type = 'success';
        // Redirection pour recharger la liste des badges disponibles
        header('Location: add_visitor.php?success=1');
        exit;
        // Vider les champs du formulaire après succès
        unset($_POST['nom'], $_POST['prenom'], $_POST['cin'], $_POST['societe'], $_POST['personne'], $_POST['objet'], $_POST['badge_id']);
        // Ne pas rediriger, rester sur la même page
    }
}

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Visiteur enregistré avec succès.";
    $message_type = 'success';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Ajouter visiteur</title>
<link rel="stylesheet" href="css/add_visitor.css">
<script src="add_visitor.js" defer></script>
</head>
<body>
<div class="container">
    <h2>Enregistrer un visiteur</h2>
    <?php if ($message): ?>
        <p class="<?= $message_type === 'success' ? 'success' : 'error' ?>"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="post" class="form-visitor" novalidate>
        <input type="text" name="nom" placeholder="Nom" required value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>" />
        <input type="text" name="prenom" placeholder="Prénom" required value="<?= isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '' ?>" />
        <input type="text" name="cin" placeholder="CIN" required value="<?= isset($_POST['cin']) ? htmlspecialchars($_POST['cin']) : '' ?>" /> 
        <input type="text" name="societe" placeholder="Société" value="<?= isset($_POST['societe']) ? htmlspecialchars($_POST['societe']) : '' ?>" />
        <input type="text" name="personne" placeholder="Personne à visiter" required value="<?= isset($_POST['personne']) ? htmlspecialchars($_POST['personne']) : '' ?>" />
        <input type="text" name="objet" placeholder="Objet de la visite" required value="<?= isset($_POST['objet']) ? htmlspecialchars($_POST['objet']) : '' ?>" />
        <!-- Select des badges disponibles -->
        <select name="badge_id" required>
            <option value="">Sélectionner un badge</option>
            <?php foreach ($badges_disponibles as $badge): ?>
                <option value="<?= $badge['id'] ?>" <?= (isset($_POST['badge_id']) && $_POST['badge_id'] == $badge['id']) ? 'selected' : '' ?>><?= htmlspecialchars($badge['code']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Enregistrer</button>
    </form>
    <p><a href="dashboard.php">Retour au tableau de bord</a></p>
</div>
 <script src="js/add_visitor.js" defer></script>
</body>
</html>