<?php
include 'config.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Dashboard</title>
<link rel="stylesheet" href="css/dashboard.css" />
</head>
<body>
<div class="dashboard-wrapper">
    <div class="dashboard-menu">
        <h1>Dashboard</h1>
        <nav>
            <ul>
                <?php if (in_array($_SESSION['role'], ['accueil', 'admin'])): ?>
                <li><a href="add_visitor.php">Enregistrer un visiteur</a></li>
                <li><a href="checkin.php">Check-in visiteur</a></li>
                <li><a href="badges.php">Gestion des badges</a></li>
                <?php endif; ?>

                <?php if (in_array($_SESSION['role'], ['visite', 'superviseur', 'admin'])): ?>
                <li><a href="checkout.php">Check-out visiteur</a></li>
                <?php endif; ?>
                <ul>
                <?php if (in_array($_SESSION['role'], ['accueil', 'admin'])): ?>
                <li><a href="liste_des_visiteurs.php">liste des visiteurs</a></li>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === 'admin'): ?>
                <li><a href="admin_users.php">Gestion utilisateurs</a></li>
                <?php endif; ?>

                <li><a href="logs.php">Journalisation</a></li>
                <li><a href="login1.php">Se déconnecter</a></li>
            </ul>
        </nav>
        <p>Bienvenue, <strong><?= htmlspecialchars($_SESSION['role']) ?></strong></p>
    </div>
    <div class="dashboard-image">
        <img src="images/logo.png" alt="Dashboard Image" />
    </div>
</div>
<script src="js/dashboard.js" defer></script>
</body>
</html>