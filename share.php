<?php
// Dossier de stockage des fichiers
$upload_dir = 'share/';

// Crée le dossier s'il n'existe pas
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Gestion du téléchargement de fichier
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $target_file = $upload_dir . basename($file['name']);

    // Vérifie que le fichier a bien été téléchargé
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        $message = "<p class='success'>Fichier téléchargé avec succès !</p>";
    } else {
        $message = "<p class='error'>Erreur lors du téléchargement du fichier.</p>";
    }
}

// Gestion de la suppression de fichier
if (isset($_GET['delete'])) {
    $file_to_delete = $upload_dir . basename($_GET['delete']);
    if (file_exists($file_to_delete)) {
        unlink($file_to_delete);
        $message = "<p class='success'>Fichier supprimé avec succès !</p>";
    } else {
        $message = "<p class='error'>Fichier introuvable.</p>";
    }
}

// Gestion du renommage de fichier
if (isset($_POST['rename']) && isset($_POST['new_name'])) {
    $old_name = $upload_dir . basename($_POST['rename']);
    $new_name = $upload_dir . basename($_POST['new_name']);

    if (file_exists($old_name) && !empty($_POST['new_name'])) {
        if (rename($old_name, $new_name)) {
            $message = "<p class='success'>Fichier renommé avec succès !</p>";
        } else {
            $message = "<p class='error'>Erreur lors du renommage du fichier.</p>";
        }
    } else {
        $message = "<p class='error'>Nom de fichier invalide ou fichier introuvable.</p>";
    }
}

// Liste des fichiers disponibles
$files = array_diff(scandir($upload_dir), array('.', '..'));

// Filtrage des fichiers selon la recherche
$search = isset($_GET['search']) ? $_GET['search'] : '';
if ($search) {
    $files = array_filter($files, function($file) use ($search) {
        return stripos($file, $search) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stockage de Fichiers</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Stockage de Fichiers</h1>

        <!-- Formulaire de téléchargement -->
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="file" required>
            <button type="submit">Télécharger</button>
        </form>

        <!-- Barre de recherche -->
        <form action="" method="get" class="search-form">
            <input type="text" name="search" placeholder="Rechercher un fichier" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Rechercher</button>
        </form>

        <!-- Affichage des fichiers disponibles -->
        <h2>Fichiers disponibles :</h2>
        <ul>
            <?php if (!empty($files)): ?>
                <?php foreach ($files as $file): ?>
                    <li>
                        <a href="<?= $upload_dir . $file ?>" target="_blank"><?= htmlspecialchars($file) ?></a>

                        <!-- Bouton pour supprimer un fichier -->
                        <form action="" method="get" class="delete-form">
                            <input type="hidden" name="delete" value="<?= htmlspecialchars($file) ?>">
                            <button type="submit" class="delete-btn">Supprimer</button>
                        </form>

                        <!-- Bouton pour afficher le formulaire de renommage -->
                        <button class="rename-btn" onclick="showRenameForm('rename-form-<?= htmlspecialchars($file) ?>')">Renommer</button>

                        <!-- Formulaire pour renommer un fichier (caché par défaut) -->
                        <form action="" method="post" class="rename-form" id="rename-form-<?= htmlspecialchars($file) ?>" style="display: none;">
                            <input type="hidden" name="rename" value="<?= htmlspecialchars($file) ?>">
                            <input type="text" name="new_name" placeholder="Nouveau nom">
                            <button type="submit">Valider</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Aucun fichier disponible.</li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Conteneur pour les messages -->
    <div id="message-container" class="message-container"></div>

    <script>
        // Fonction pour afficher le formulaire de renommage
        function showRenameForm(id) {
            document.getElementById(id).style.display = 'inline-block';
        }

        // Fonction pour afficher les messages
        function showMessage(message, type) {
            const container = document.getElementById('message-container');
            container.innerHTML = `<div class="message ${type}">${message}</div>`;
            container.style.display = 'block';
            setTimeout(() => {
                container.style.opacity = '0';
                setTimeout(() => {
                    container.style.display = 'none';
                    container.style.opacity = '1';
                }, 300);
            }, 3000);
        }

        // Appeler showMessage avec un message et un type approprié
        <?php if (isset($message)): ?>
            showMessage(<?= json_encode($message) ?>, '<?= strpos($message, 'error') !== false ? 'error' : 'success' ?>');
        <?php endif; ?>
    </script>
</body>
</html>
