<?php
session_start();
require_once '../../includes/database.php';
require_once '../../includes/function.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../views/user/pages/connexion.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $photo = $_FILES['photo'];

    // Validation et traitement de la photo de profil
    if ($photo['error'] === UPLOAD_ERR_OK) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            $_SESSION['error'] = "Extension de fichier non valide.";
            header('Location: ../../views/user/pages/profil.php');
            exit();
        }

        $new_filename = uniqid() . '.' . $file_extension;
        $upload_directory = '../../uploads/profiles/';
        $upload_path = $upload_directory . $new_filename;

        if (!move_uploaded_file($photo['tmp_name'], $upload_path)) {
            $_SESSION['error'] = "Erreur lors du téléchargement de la photo.";
            header('Location: ../../views/user/pages/profil.php');
            exit;
        }

        // Supprimer l'ancienne photo de profil si elle existe
        $stmt = $pdo->prepare("SELECT profil FROM users WHERE id_user = :id_user");
        $stmt->execute([':id_user' => $user_id]);
        $old_photo = $stmt->fetchColumn();
        if ($old_photo && file_exists($upload_directory . $old_photo)) {
            unlink($upload_directory . $old_photo);
        }

        // Mise à jour de la photo de profil
        $stmt = $pdo->prepare("UPDATE users SET profil = :photo WHERE id_user = :id_user");
        $result = $stmt->execute([
            ':photo' => $new_filename,
            ':id_user' => $user_id,
        ]);
    } else {
        $_SESSION['error'] = "Aucune photo sélectionnée ou une erreur est survenue.";
    }

    if ($result) {
        $_SESSION['message'] = "Photo de profil mise à jour avec succès.";
    } else {
        $_SESSION['error'] = "Une erreur s'est produite lors de la mise à jour de la photo.";
    }

    header('Location: ../../views/user/pages/profil.php');
    exit;
}
