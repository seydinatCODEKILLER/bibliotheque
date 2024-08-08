<?php
session_start();
require_once "../../includes/database.php";
require_once "../../includes/function.php";

// Assurez-vous que l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../views/user/auth/connexion.php");
    exit();
}

$errors = [];

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["picture"]) && $_FILES["picture"]["error"] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES["picture"]["tmp_name"];
        $fileName = $_FILES["picture"]["name"];
        $fileSize = $_FILES["picture"]["size"];
        $fileType = $_FILES["picture"]["type"];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Taille maximale du fichier en octets (5 Mo)
        $maxFileSize = 5 * 1024 * 1024; // 5 Mo

        // Extensions autorisées
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

        // Validation des extensions
        if (in_array($fileExtension, $allowedExts)) {
            // Validation de la taille du fichier
            if ($fileSize <= $maxFileSize) {
                // Définir le chemin du fichier de destination
                $uploadFileDir = '../../uploads/profiles/';
                $dest_path = $uploadFileDir . $_SESSION["user_id"] . '.' . $fileExtension;

                // Déplacer le fichier téléchargé
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    // Mettre à jour la base de données avec le nouveau nom de fichier
                    $stmt = $pdo->prepare("UPDATE users SET profil = :profile_picture WHERE id_user = :id");
                    $stmt->execute([
                        ':profile_picture' => $_SESSION["user_id"] . '.' . $fileExtension,
                        ':id' => $_SESSION["user_id"]
                    ]);
                } else {
                    $errors[] = 'Erreur lors du déplacement du fichier.';
                }
            } else {
                $errors[] = 'Le fichier dépasse la taille maximale autorisée de 5 Mo.';
            }
        } else {
            $errors[] = 'Type de fichier non autorisé. Veuillez télécharger une image au format jpg, jpeg, png ou gif.';
        }
    }

    // Si des erreurs se produisent, les stocker dans la session
    if (!empty($errors)) {
        $_SESSION["errors"] = $errors;
        header("Location: ../../views/user/auth/upload_profil.php");
        exit();
    } else {
        // Redirection vers le tableau de bord après la mise à jour ou si aucune image n'est téléchargée
        $_SESSION["errors"] = [];
        $_SESSION["success"] = "Inscription reussit avec success !!";
        header("Location: ../../index.php");
        exit();
    }
}
