<?php
session_start();
// Redirige vers index.php si déjà connecté
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue</title>
    <link rel="stylesheet" href="styles.php">
</head>
<body>
    <div class="home-container">
        <h1>Bienvenue sur notre plateforme de stockage</h1>
        <div class="home-buttons">
            <a href="login.php" class="btn btn-blue">Connexion</a>
            <a href="register.php" class="btn btn-green">Inscription</a>
        </div>
    </div>
</body>
</html>
