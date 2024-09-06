<?php
session_start();
include 'db.php'; // Connexion à la base de données

// Redirige vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Gestion des messages d'alerte
$message = '';

// Gestion de l'upload de fichier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $filename = $_FILES['file']['name'];
    $uploader_id = $_SESSION['user_id'];
    $target_dir = 'uploads/';
    $target_file = $target_dir . basename($filename);

    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
        // Insertion du fichier dans la base de données
        $stmt = $pdo->prepare("INSERT INTO files (filename, uploader_id) VALUES (?, ?)");
        $stmt->execute([$filename, $uploader_id]);
        $message = 'Fichier téléchargé avec succès.';
    } else {
        $message = 'Erreur lors du téléchargement du fichier.';
    }
}

// Gestion de la suppression de fichier
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $file_id = $_GET['delete'];
    $stmt = $pdo->prepare("SELECT filename, uploader_id FROM files WHERE id = ?");
    $stmt->execute([$file_id]);
    $file = $stmt->fetch();

    // Vérifie que l'utilisateur est bien le propriétaire du fichier
    if ($file && $file['uploader_id'] == $_SESSION['user_id']) {
        $file_path = 'uploads/' . $file['filename'];
        if (unlink($file_path)) {
            $stmt = $pdo->prepare("DELETE FROM files WHERE id = ?");
            $stmt->execute([$file_id]);
            $message = 'Fichier supprimé avec succès.';
        } else {
            $message = 'Erreur lors de la suppression du fichier.';
        }
    } else {
        $message = 'Vous n\'êtes pas autorisé à supprimer ce fichier.';
    }
}

// Gestion du renommage de fichier
if (isset($_POST['rename']) && is_numeric($_POST['file_id']) && !empty($_POST['new_name'])) {
    $file_id = $_POST['file_id'];
    $new_name = $_POST['new_name'];

    $stmt = $pdo->prepare("SELECT filename, uploader_id FROM files WHERE id = ?");
    $stmt->execute([$file_id]);
    $file = $stmt->fetch();

    if ($file && $file['uploader_id'] == $_SESSION['user_id']) {
        $old_path = 'uploads/' . $file['filename'];
        $new_path = 'uploads/' . $new_name;

        if (rename($old_path, $new_path)) {
            $stmt = $pdo->prepare("UPDATE files SET filename = ? WHERE id = ?");
            $stmt->execute([$new_name, $file_id]);
            $message = 'Fichier renommé avec succès.';
        } else {
            $message = 'Erreur lors du renommage du fichier.';
        }
    } else {
        $message = 'Vous n\'êtes pas autorisé à renommer ce fichier.';
    }
}

// Gestion de la recherche de fichier
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Récupération des fichiers de l'utilisateur ou publics
$stmt = $pdo->prepare("SELECT * FROM files WHERE (filename LIKE ? AND uploader_id = ?) OR (is_shared = 1 AND filename LIKE ?)");
$stmt->execute(["%$search%", $_SESSION['user_id'], "%$search%"]);
$files = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Fichiers</title>
    <link rel="stylesheet" href="styles.php">
</head>
<body>
    <div class="container">
        <!-- Lien vers le dossier partagé -->
        <a href="share.php" class="btn btn-blue">Dossier Partagé</a>

        <h1>Gestion de Fichiers</h1>

        <!-- Affichage des messages -->
        <?php if (!empty($message)): ?>
            <div class="popup-message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <!-- Formulaire d'upload de fichier -->
        <form action="index.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" required>
            <button type="submit" class="btn btn-blue">Télécharger</button>
        </form>

        <!-- Barre de recherche -->
        <form action="index.php" method="get">
            <input type="text" name="search" placeholder="Rechercher un fichier..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-blue">Rechercher</button>
        </form>

        <!-- Liste des fichiers -->
        <table>
            <thead>
                <tr>
                    <th>Nom du fichier</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file): ?>
                    <tr>
                        <td><?= htmlspecialchars($file['filename']) ?></td>
                        <td>
                            <a href="uploads/<?= urlencode($file['filename']) ?>" download class="btn btn-blue">Télécharger</a>
                            <?php if ($file['uploader_id'] == $_SESSION['user_id']): ?>
                                <form action="index.php?delete=<?= $file['id'] ?>" method="post" style="display: inline;">
                                    <button type="submit" class="btn btn-red">Supprimer</button>
                                </form>
                                <button class="btn btn-green rename-btn">Renommer</button>
                                <form action="index.php" method="post" class="rename-form" style="display: none;">
                                    <input type="hidden" name="file_id" value="<?= $file['id'] ?>">
                                    <input type="text" name="new_name" placeholder="Nouveau nom" required>
                                    <button type="submit" name="rename" class="btn btn-green">Valider</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Afficher le formulaire de renommage au clic sur le bouton Renommer
        document.querySelectorAll('.rename-btn').forEach(button => {
            button.addEventListener('click', () => {
                button.nextElementSibling.style.display = 'inline';
            });
        });

        // Animation pour les messages popup
        const popup = document.querySelector('.popup-message');
        if (popup) {
            setTimeout(() => {
                popup.style.display = 'none';
            }, 3000); // Cache le message après 3 secondes
        }
    </script>
</body>
</html>