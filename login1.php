<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $query = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $query->execute([$email]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        if ($user['actif']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Compte désactivé.";
        }
    } else {
        $error = "Identifiants incorrects.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>visiTax - Connexion</title>
  <link rel="stylesheet" href="css/login1.css">
</head>

<body>
  <header class="main-header">
    <div class="logo">
      <img src="images/logo-2m.png" alt="visiTax Logo">
      <span>visiTax</span>
    </div>
  </header>

  <div class="container">
    <div class="form-box">
      <h2>Bienvenue sur visiTax</h2>

      <?php if (isset($error)) : ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="post">
        <div class="input-group">
          <input type="email" name="email" placeholder="Email" required />
        </div>
        <div class="input-group">
          <input type="password" name="mot_de_passe" placeholder="Mot de passe" required />
        </div>
        <button type="submit" class="btn">Se connecter</button>
      </form>
    </div>

    <div class="image-box">
      <img src="images/logo.png" width="500px" alt="Illustration" />
    </div>
  </div>
   <script src="js/login1.js" defer></script>
</body>
</html>
