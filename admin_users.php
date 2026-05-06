<?php
include 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $mdp_clair = $_POST['mot_de_passe'];

    // Vérifier les champs
    if (empty($nom) || empty($prenom) || empty($email) || empty($role) || empty($mdp_clair)) {
        $message = "Merci de remplir tous les champs.";
    } else {
        // Vérifier si l'email existe déjà
        $stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $message = "Email déjà utilisé.";
        } else {
            // Hasher le mot de passe
            $mdp = password_hash($mdp_clair, PASSWORD_DEFAULT);

            // Insérer
            $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, actif) VALUES (?, ?, ?, ?, ?, 1)");
            $result = $stmt->execute([$nom, $prenom, $email, $mdp, $role]);

            if ($result) {
                logAction($conn, "Utilisateur $email créé avec rôle $role", $_SESSION['user_id']);
                // Rediriger pour vider POST et voir le nouvel utilisateur
                header("Location: admin_users.php?created=1");
                exit();
            } else {
                $error = $stmt->errorInfo();
                $message = "Erreur SQL : " . $error[2];
            }
        }
    }
}

if (isset($_GET['created'])) {
    $message = "Utilisateur créé avec succès.";
}

// Changer statut
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];

    $stmt = $conn->prepare("SELECT actif FROM utilisateurs WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $newStatus = $user['actif'] ? 0 : 1;
        $statusText = $newStatus ? 'activé' : 'désactivé';
        
        $stmt = $conn->prepare("UPDATE utilisateurs SET actif = ? WHERE id = ?");
        $stmt->execute([$newStatus, $id]);
        
        logAction($conn, "Utilisateur ID $id $statusText", $_SESSION['user_id']);

        header("Location: admin_users.php");
        exit();
    }
}

// Charger la liste
$stmt = $conn->query("SELECT * FROM utilisateurs ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Gestion des utilisateurs</title>
  <link rel="stylesheet" href="css/admin_users.css" />
</head>
<body>
  <div class="admin-wrapper">
    <div class="admin-container">
      <h1>Gestion des utilisateurs</h1>

      <?php if ($message): ?>
        <p class="error"><?= htmlspecialchars($message) ?></p>
      <?php endif; ?>

      <form method="post" class="form-create">
        <h2>Créer un nouvel utilisateur</h2>
        <input type="text" name="nom" placeholder="Nom" required />
        <input type="text" name="prenom" placeholder="Prénom" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required />
        <select name="role" required>
          <option value="">Sélectionner rôle</option>
          <option value="accueil">Agent d'accueil</option>
          <option value="visite">Personne visitée</option>
          <option value="superviseur">Superviseur</option>
          <option value="admin">Administrateur</option>
        </select>
        <button type="submit">Créer</button>
      </form>

      <h2>Liste des utilisateurs</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Actif</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($users as $u): ?>
          <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['nom']) ?></td>
            <td><?= htmlspecialchars($u['prenom']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['role'] ?></td>
            <td><?= $u['actif'] ? 'Oui' : 'Non' ?></td>
            <td><a href="?toggle=<?= $u['id'] ?>"><?= $u['actif'] ? 'Désactiver' : 'Activer' ?></a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <a href="dashboard.php" class="back-link">Retour au tableau de bord</a>
    </div>
  </div>
</body>
</html>
